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

    /**
     * Create a new job instance.
     */
    public function __construct($audio_path, $transcription_status, $ip)
    {
        $this->audio_path = $audio_path;
        $this->transcription_status = $transcription_status;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $processing = ip::create([
            'ip'                      => $this->ip,
            'transacription_status'   => 'TRANSCRIBING',
        ]);

        try {
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
                    'ip'                      => $this->ip,
                    'audio_path'              => $this->audio_path,
                    'vtt_path'                => $vtt_path,
                    'transacription_status'   => 'TRANSCRIBED',
                ]);
            } else {
                $processing->update([
                    'transacription_status'   => 'TRANSCRIBED',
                ]);
            }
        } catch (Exception $e) {
            $processing->update([
                'transacription_status'   => 'TRANSCRIBED',
            ]);
            log('An error occurred for this IP Address : '.$this->ip.' ERROR: '.$e->getMessage());
        }
    }
}
