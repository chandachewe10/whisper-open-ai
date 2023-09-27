<?php

namespace App\Jobs;

use App\Models\ip;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;

class TranscribeAudio implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $audio_path;
    public $transcription_status;
    public $ip;
    public $output;
    public $audio_duration;
    public $isMeetingMinutes;

    /**
     * Create a new job instance.
     */
    public function __construct($audio_path, $transcription_status, $ip, $audio_duration, $isMeetingMinutes)
    {
        $this->audio_path = $audio_path;
        $this->transcription_status = $transcription_status;
        $this->ip = $ip;
        $this->audio_duration = $audio_duration;
        $this->isMeetingMinutes = $isMeetingMinutes;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            ini_set('max_execution_time', 300); //5 minutes

            $processing = ip::create([
                'ip'                     => $this->ip,
                'audio_path'             => $this->audio_path,
                'transcription_status'   => 'TRANSCRIBING',
            ]);

            $response = Http::timeout(300)->attach(
                'file',
                fopen(public_path('AUDIOS/'.$this->audio_path), 'r'),
            )->withToken(config('openai.token'))->post(config('openai.base_uri').'audio/transcriptions', [

                'model'           => 'whisper-1',
                'response_format' => 'vtt',
                'temperature'     => 0.2,
            ]);
            $vtt_path = 'VTTFILES/'.Str::random(40).'.vtt';
            Storage::disk('webvtt')->put($vtt_path, $response);
            if ($response->status() == 200) {
                $this->output = $response;
                $this->transcription_status = 'TRANSCRIBED';

                /**
                 * Store Users IP Address with File Path and Vtt Path for each successfull transcription.
                 */
                if (!$this->isMeetingMinutes && $this->audio_duration <= 300) {
                    $processing->update([
                        'ip'                     => $this->ip,
                        'audio_path'             => $this->audio_path,
                        'vtt_path'               => $vtt_path,
                        'transcription_status'   => $this->transcription_status,
                    ]);
                }

                if (!$this->isMeetingMinutes && $this->audio_duration > 300) {
                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Define formatting styles
                    $boldFontStyle = ['bold' => true];
                    $Style = ['bold' => true, 'size' => 14, 'allCaps' => true];

                    // Add content to the document
                    $section = $phpWord->addSection();

                    $section->addText('Audio Transcriptin :', $Style, ['alignment' => 'center']);
                    $section->addText($response->body());

                    // Save the document as a Word file
                    $docx = Str::random(40).'.docx';
                    $pdf = Str::random(40).'.pdf';
                    $docx_path = public_path('DOCS/DOCX/'.$docx);
                    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
                    $objWriter->save($docx_path);

                    // Convert Word to PDF
                    $pdf_path = public_path('DOCS/PDF/'.$pdf);
                    $pdf = Pdf::loadFile($docx_path);
                    $pdf->save($pdf_path);

                    // Send Email with Word and PDF Attachments
                    Mail::send('email.audio_transcription', [], function ($message) use ($pdf_path, $docx_path) {
                        $message->to('chewec03@gmail.com')
                            ->subject('Your Audio Transcription Results')
                            ->attach($pdf_path)
                            ->attach($docx_path);
                    });
                }

                if ($this->isMeetingMinutes && $this->audio_duration > 300) {
                    // Get the meetings summary from GPT-4
                    $meeting_summary = Http::timeout(300)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                        'model'    => 'gpt-4',
                        'messages' => [
                            [
                                'role'    => 'system',
                                'content' => 'You are a highly skilled AI trained in language comprehension and summarization. I would like you to read the following text and summarize it into a concise abstract paragraph. Aim to retain the most important points, providing a coherent and readable summary that could help a person understand the main points of the discussion without needing to read the entire text. Please avoid unnecessary details or tangential points.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $response->body(),
                            ],
                        ],
                    ]);

                    $summary = $meeting_summary['choices'][0]['message']['content'];

                    // Get the meetings key points from GPT-4
                    $meeting_keypoints = Http::timeout(300)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                        'model'    => 'gpt-4',
                        'messages' => [
                            [
                                'role'    => 'system',
                                'content' => 'You are a proficient AI with a specialty in distilling information into key points. Based on the following text, identify and list the main points that were discussed or brought up. These should be the most important ideas, findings, or topics that are crucial to the essence of the discussion. Your goal is to provide a list that someone could read to quickly understand what was talked about.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $response->body(),
                            ],
                        ],
                    ]);

                    $keypoints = $meeting_keypoints['choices'][0]['message']['content'];

                    // Get the meetings action points from GPT-4
                    $meeting_actions = Http::timeout(300)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                        'model'    => 'gpt-4',
                        'messages' => [
                            [
                                'role'    => 'system',
                                'content' => 'You are an AI expert in analyzing conversations and extracting action items. Please review the text and identify any tasks, assignments, or actions that were agreed upon or mentioned as needing to be done. These could be tasks assigned to specific individuals, or general actions that the group has decided to take. Please list these action items clearly and concisely.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $response->body(),
                            ],
                        ],
                    ]);

                    $actions = $meeting_actions['choices'][0]['message']['content'];

                    // Get the meetings sentimemnts points from GPT-4
                    $meeting_sentiments = Http::timeout(300)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                        'model'    => 'gpt-4',
                        'messages' => [
                            [
                                'role'    => 'system',
                                'content' => 'As an AI with expertise in language and emotion analysis, your task is to analyze the sentiment of the following text. Please consider the overall tone of the discussion, the emotion conveyed by the language used, and the context in which words and phrases are used. Indicate whether the sentiment is generally positive, negative, or neutral, and provide brief explanations for your analysis where possible.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $response->body(),
                            ],
                        ],
                    ]);

                    $sentiments = $meeting_sentiments['choices'][0]['message']['content'];

                    // Get the meetings agenda points from GPT-4
                    $meeting_agenda = Http::timeout(300)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                        'model'    => 'gpt-4',
                        'messages' => [
                            [
                                'role'    => 'system',
                                'content' => 'Please come up with an agenda based on the following text and provide a concise summary. Aim to identify the key points and structure them into an agenda that outlines the main topics of the discussion.',
                            ],
                            [
                                'role'    => 'user',
                                'content' => $response->body(),
                            ],
                        ],
                    ]);

                    $agenda = $meeting_agenda['choices'][0]['message']['content'];

                    // Create a new PhpWord instance
                    $phpWord = new PhpWord();

                    // Define formatting styles
                    $boldFontStyle = ['bold' => true];
                    $agendaStyle = ['bold' => true, 'size' => 14, 'allCaps' => true];

                    // Add content to the document (agenda, summary, key points, sentiments)
                    $section = $phpWord->addSection();

                    // Agenda (bold, centered, uppercase, font size 14)
                    $section->addText('Agenda:', $agendaStyle, ['alignment' => 'center']);
                    $section->addText($agenda);

                    // Summary, Key Points, Sentiments (bold, font size 12)
                    $section->addText('Summary:', $boldFontStyle);
                    $section->addText($summary);

                    $section->addText('Key Points:', $boldFontStyle);
                    $section->addText($keypoints);

                    $section->addText('Actions:', $boldFontStyle);
                    $section->addText($actions);

                    $section->addText('Sentiments:', $boldFontStyle);
                    $section->addText($sentiments);

                    // Save the document as a Word file
                    $docx = Str::random(40).'.docx';
                    $pdf = Str::random(40).'.pdf';
                    $docx_path = public_path('DOCS/DOCX/'.$docx);
                    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
                    $objWriter->save($docx_path);

                    // Convert Word to PDF
                    $pdf_path = public_path('DOCS/PDF/'.$pdf);
                    $pdf = Pdf::loadFile($docx_path);
                    $pdf->save($pdf_path);

                    // Send Email with Word and PDF Attachments
                    Mail::send('email.meeting_transcription', [], function ($message) use ($pdf_path, $docx_path) {
                        $message->to('chewec03@gmail.com')
                            ->subject('Your Meeting Transcription Results')
                            ->attach($pdf_path)
                            ->attach($docx_path);
                    });

                    $processing->update([
                        'ip'                     => $this->ip,
                        'audio_path'             => $this->audio_path,
                        'vtt_path'               => $vtt_path,
                        'transcription_status'   => 'TRANSCRIBED',
                    ]);
                }
            } else {
                $processing->update([
                    'transcription_status'   => 'FAILED',
                ]);
            }
        } catch (Exception $e) {
            $processing->update([
                'transcription_status'   => 'FAILED '.$e->getMessage(),
            ]);
            log('An error occurred for this IP Address : '.$this->ip.' ERROR: '.$e->getMessage());
        }
    }
}
