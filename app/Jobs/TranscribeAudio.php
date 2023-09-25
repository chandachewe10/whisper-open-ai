<?php

namespace App\Jobs;

use App\Models\ip;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    /**
     * Create a new job instance.
     */
    public function __construct($audio_path, $transcription_status, $ip, $audio_duration)
    {
        $this->audio_path = $audio_path;
        $this->transcription_status = $transcription_status;
        $this->ip = $ip;
        $this->audio_duration = $audio_duration;
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
                $this->transcription_status = 2;

                /**
                 * Store Users IP Address with File Path and Vtt Path for each successfull transcription.
                 */
                $processing->update([
                    'ip'                     => $this->ip,
                    'audio_path'             => $this->audio_path,
                    'vtt_path'               => $vtt_path,
                    'transcription_status'   => 'TRANSCRIBED',
                ]);
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
