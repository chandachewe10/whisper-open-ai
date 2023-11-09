<?php

namespace App\Http\Livewire;

use App\Models\ip;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class HomePage extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $audio;

    public $audio_path = 'TRACKS/sample.mp3';
    public int $transcription_status = 0;
    public string $exception = '';
    public string $output = 'TRACKS/sample.vtt';
    public string $ip;
    public string $response;
    public bool $isMeetingMinutes = false;
    public string $email;
    public string $phone;

    public function mount()
    {
        $this->email = '';
        $this->phone = '';
    }

    public function render()
    {
        return view('livewire.home-page')
            ->layout('welcome');
    }

    public function translate()
    {
        //# Check For Email and Phone Number if Minute Meeting is Selected
        if ($this->isMeetingMinutes && empty($this->email) || empty($this->phone)) {
            $this->alert('warning', 'Please Ensure your email and phone number are entered.');
            return;
        }

        ini_set('max_execution_time', 600); //10 minutes
        /**
         * Check if the user has transcribed more than twice.
         */
        $users_ip = '41.60.184.49'; //request()->ip; //
        $this->ip = $users_ip;
        $check = ip::where('ip', '=', $users_ip)->get();
        if (count($check) >= 100) {
            session()->flash('message', 'You can only transcribe 3 as at now, and you have reached the maximum!');
        } else {
            $this->validate([
                'audio' => 'file|max:25600', // 24 MB Max
            ]);

            $file_path = $this->audio->store('TRACKS');
            $this->audio_path = $file_path;

            try {
                $response = Http::timeout(300)
                    ->attach(
                        'file',
                        fopen(public_path('AUDIOS/'.$file_path), 'r')
                    )
                    ->withToken(config('openai.token'))

                    ->post(config('openai.base_uri').'audio/transcriptions', [
                        'model'           => 'whisper-1',
                        'response_format' => 'vtt',
                        'temperature'     => 0.2,
                    ]);

                $vtt_path = Str::random(40).'.vtt';
                Storage::disk('webvtt')->put('VTTFILES/'.$vtt_path, $response);
                if ($response->status() == 200) {
                    $this->output = $response;
                    $this->transcription_status = 2;

                    /**
                     * Store Users IP Address with File Path and Vtt Path for each successfull transcription.
                     */
                    ip::create([
                        'ip'         => $users_ip,
                        'audio_path' => $file_path,
                        'vtt_path'   => $vtt_path,
                    ]);
                    if ($this->isMeetingMinutes) {
                        $this->minutes();
                    } else {
                        return redirect(request()->header('Referer'));
                    }
                } else {
                    $this->response = $response;
                    $this->transcription_status = 3;
                }
            } catch (\Exception $e) {
                $this->transcription_status = 3;
                $this->exception = $e->getMessage();
            }
        }
    }

    private function minutes()
    {
        try {
            $Meetingresponse = Http::timeout(300)
                ->attach(
                    'file',
                    fopen(public_path('AUDIOS/'.$this->audio_path), 'r')
                )
                ->withToken(config('openai.token'))

                ->post(config('openai.base_uri').'audio/transcriptions', [
                    'model'           => 'whisper-1',
                    'response_format' => 'text',
                    'temperature'     => 0.2,
                ]);

            if ($Meetingresponse->status() == 200) {
                // Get the meetings summary from gpt-3.5-turbo
                $meeting_summary = Http::timeout(300)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'You are a highly skilled AI trained in language comprehension and summarization. I would like you to read the following text and summarize it into a concise abstract paragraph. Aim to retain the most important points, providing a coherent and readable summary that could help a person understand the main points of the discussion without needing to read the entire text. Please avoid unnecessary details or tangential points.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $Meetingresponse->body(),
                        ],
                    ],
                ]);

                $summary = $meeting_summary['choices'][0]['message']['content'];

                // Get the meetings key points from gpt-3.5-turbo
                $meeting_keypoints = Http::timeout(300)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'You are a proficient AI with a specialty in distilling information into key points. Based on the following text, identify and list the main points that were discussed or brought up. These should be the most important ideas, findings, or topics that are crucial to the essence of the discussion. Your goal is to provide a list that someone could read to quickly understand what was talked about.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $Meetingresponse->body(),
                        ],
                    ],
                ]);

                $keypoints = $meeting_keypoints['choices'][0]['message']['content'];

                // Get the meetings action points from gpt-3.5-turbo
                $meeting_actions = Http::timeout(300)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'You are an AI expert in analyzing conversations and extracting action items. Please review the text and identify any tasks, assignments, or actions that were agreed upon or mentioned as needing to be done. These could be tasks assigned to specific individuals, or general actions that the group has decided to take. Please list these action items clearly and concisely.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $Meetingresponse->body(),
                        ],
                    ],
                ]);

                $actions = $meeting_actions['choices'][0]['message']['content'];

                // Get the meetings sentimemnts points from gpt-3.5-turbo
                $meeting_sentiments = Http::timeout(300)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'As an AI with expertise in language and emotion analysis, your task is to analyze the sentiment of the following text. Please consider the overall tone of the discussion, the emotion conveyed by the language used, and the context in which words and phrases are used. Indicate whether the sentiment is generally positive, negative, or neutral, and provide brief explanations for your analysis where possible.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $Meetingresponse->body(),
                        ],
                    ],
                ]);

                $sentiments = $meeting_sentiments['choices'][0]['message']['content'];

                // Get the meetings agenda points from gpt-3.5-turbo
                $meeting_agenda = Http::timeout(300)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'Please come up with a short title/agenda of the meeting based on the following texts.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $Meetingresponse->body(),
                        ],
                    ],
                ]);

                $agenda = $meeting_agenda['choices'][0]['message']['content'];

                // Get the meetings conclusion points from gpt-3.5-turbo
                $meeting_conclusion = Http::timeout(300)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withToken(config('openai.token'))->post(config('openai.base_uri').'chat/completions', [
                    'model'    => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'Please come up with the meeting conclusion based on the following texts.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $Meetingresponse->body(),
                        ],
                    ],
                ]);

                $conclusion = $meeting_conclusion['choices'][0]['message']['content'];

                // Create a new PhpWord instance
                $phpWord = new PhpWord();

                // Define formatting styles
                $boldFontStyle = ['bold' => true];
                $agendaStyle = ['bold' => true, 'size' => 14, 'allCaps' => true];

                // Add content to the document (agenda, summary, key points, sentiments)
                $section = $phpWord->addSection();

                // Agenda (bold, centered, uppercase, font size 14)
                $section->addText($agenda, $agendaStyle, ['alignment' => 'center']);

                // Summary, Key Points, Sentiments (bold, font size 12)
                $section->addText('Summary:', $boldFontStyle);
                $section->addText($summary);

                $section->addText('Key Points:', $boldFontStyle);
                $section->addText($keypoints);

                $section->addText('Actions:', $boldFontStyle);
                $section->addText($actions);

                $section->addText('Sentiments:', $boldFontStyle);
                $section->addText($sentiments);

                $section->addText('Conclusion:', $boldFontStyle);
                $section->addText($conclusion);

                // Save the document as a Word file
                $docx = Str::random(40).'.docx';
                $docx_path = public_path('DOCS/DOCX/'.$docx);
                $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($docx_path);

                // Send Email with Word and PDF Attachments
                Mail::send('meeting_transcription.index', [], function ($message) use ($docx_path) {
                    $message->to($this->email)
                        ->subject('Your Meeting Transcription Results')
                        ->attach($docx_path);
                });

                // Send SMS Notification
                $this->send_notification_sms();

                return redirect(request()->header('Referer'));
            } else {
                $this->response = $Meetingresponse;
                $this->transcription_status = 3;
            }
        } catch (\Exception $e) {
            $this->exception = $e->getMessage();
        }
    }

    private function send_notification_sms()
    {
        $jsonDataSMS = [
            'sender_id' => 'MACROIT',
            'numbers'   => $this->phone,
            'message'   => 'We wanted to let you know that your file has been transcribed and has been shared to your email address!',

        ];

        // Convert the data to JSON format
        $jsonData = json_encode($jsonDataSMS);

        Http::withHeaders([
            'Authorization' => 'Bearer '.env('BULK_SMS_TOKEN'),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])
            ->timeout(300)
            ->withBody($jsonData, 'application/json')
            ->get(env('BULK_SMS_BASE_URI'));
    }
}
