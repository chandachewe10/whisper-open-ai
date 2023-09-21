<?php

namespace App\Http\Livewire;

use App\Models\ip;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\TranscribeAudio;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomePage extends Component
{
    use WithFileUploads;

    public $audio;

    public $audio_path = 'TRACKS/sample.mp3';
    public int $transcription_status = 0;
    public string $exception = '';
    public string $output = 'TRACKS/sample.vtt';
    public string $ip;

    public function render()
    {
        return view('livewire.home-page')
        ->layout('welcome');
    }

    public function translate()
    {
        ini_set('max_execution_time', 180); //3 minutes
        /**
         * Check if the user has transcribed more than twice.
         */
        $users_ip = '41.60.184.49'; //request()->ip;
        $this->ip = $users_ip;
        $check = ip::where('ip', '=', $users_ip)->get();
        if (count($check) >= 3) {
            session()->flash('message', 'You can only transcribe twice as at now, and you have reached the maximum!');
        } else {
            $this->validate([
                'audio' => 'file|max:25600', // 24 MB Max
            ]);

            $file_path = $this->audio->store('TRACKS');
            $this->audio_path = $file_path;

            TranscribeAudio::dispatch($this->audio_path,$this->transcription_status,$this->ip,$this->exception);

           
        }
    }
}
