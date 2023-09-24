<?php

namespace App\Http\Livewire;

use App\Models\ip;
use Exception;
use App\Services\AudioDuration;
use App\Jobs\TranscribeAudio;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class HomePage extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $audio;

    public $audio_path = 'TRACKS/sample.mp3';
    public string $transcription_status;
    public string $exception = '';
    public string $output = 'TRACKS/sample.vtt';
    public string $ip;
    public float $audioDuration;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.home-page')
            ->layout('welcome');
    }

    public function translate()
    {
        try {
            ini_set('max_execution_time', 300); //5 minutes
            /**
             * Check if the user has transcribed more than twice.
             */
            $users_ip = '41.60.184.49'; //request()->ip;
            $this->ip = $users_ip;
            $check = ip::where('ip', '=', $users_ip)->get();
            if (count($check) >= 3) {
                $this->alert('warning', 'You can only transcribe twice as at now, and you have reached the maximum!');
            } else {
                $this->validate([
                    'audio' => 'file|max:25600', // 24 MB Max
                ]);




                $file_path = $this->audio->store('TRACKS');
                $this->audio_path = $file_path;
                $audiofile = new AudioDuration('AUDIOS/' . $this->audio_path);
                $duration = $audiofile->getDurationEstimate();
                $this->audioDuration = $duration;
                if ($this->audioDuration < 5) {
                    $this->transcription_status = 'INITIATED';
                    TranscribeAudio::dispatch($this->audio_path, $this->transcription_status, $this->ip, $this->audioDuration);
                    $this->emit('refreshComponent'); //updated transcription_status and other variables to be noticed on front side

                } elseif ($this->audioDuration >= 5) {
                    $this->alert('warning', 'Audios Longer than 5 minutes cannot be transcribed for free. To transcribe this audio go on Meetings below.', [
                        'position' => 'center',
                        'timer' => 10000,
                        'toast' => true,
                        'showConfirmButton' => true,
                        'onConfirmed' => '',
                    ]);
                } else {
                    $this->alert('warning', 'We cant get the duration of this audio.', [
                        'position' => 'center',
                        'timer' => 10000,
                        'toast' => true,
                        'showConfirmButton' => true,
                        'onConfirmed' => '',
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->alert('warning', 'Whoops something went wrong: ' . $e->getMessage(), [
                'position' => 'center',
                'timer' => 10000,
                'toast' => true,
                'showConfirmButton' => true,
                'onConfirmed' => '',
            ]);
        }
    }


    public function transcriptionStatus(): void
    {
        $status = ip::where('ip', '=', $this->ip)->latest()->first();
        if ($status && $status->audio_path == $this->audio_path) {
            $this->transcription_status = $status->transacription_status;
        }
    }


    public function refreshPage()
    {
        //Stop Polling
        $this->transcription_status = '';
        return redirect(request()->header('Referer'));
    }

    public function revertTranscriptionStatus()
    {
        //Stop Polling
        $this->transcription_status = '';
    }
}
