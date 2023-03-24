<?php

namespace App\Http\Livewire;

use App\Models\ip;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomePage extends Component
{
    use WithFileUploads;
 
    public $audio, $audio_path = 'TRACKS/sample.mp3';
    public int $transcription_status = 0;
    public string $exception = '';
    public string $output = 'TRACKS/sample.vtt';
    public string $ip;
    
    public function render()
    {
        
        return view('livewire.home-page')
        ->layout('welcome');
    }


    public function translate(){
        ini_set('max_execution_time', 180); //3 minutes
    /**
      * Check if the user has transcribed more than twice.
      */
     
    $users_ip = '41.60.184.49';//request()->ip;
    $this->ip = $users_ip;
    $check = ip::where('ip', "=", $users_ip)->get();
    if (count($check) >= 1112) {
        session()->flash('message', 'You can only transcribe twice as at now, and you have reached the maximum!');
    } else {
        $this->validate([
            'audio' => 'file|max:25600', // 24 MB Max
        ]);

        $file_path = $this->audio->store('TRACKS');
        $this->audio_path = $file_path;


        try {
            $response = Http::timeout(300)->attach(
                'file',
                fopen(public_path('AUDIOS/'.$file_path), 'r'),
            )->withToken(config('openai.token'))->post(config('openai.base_uri').'audio/transcriptions', [

                'model' => 'whisper-1',
                'response_format' => 'vtt',
                'temperature' => 0.2
            ]);
            $vtt_path = Str::random(40). '.vtt';
            Storage::disk('webvtt')->put('VTTFILES/'.$vtt_path,$response);
            if ($response->status() == 200) {
                $this->output = $response;
                $this->transcription_status = 2;


                /**
                  * Store Users IP Address with File Path and Vtt Path for each successfull transcription.
                  */
                  ip::create([
                    'ip' => $users_ip,
                    'audio_path' => $file_path,
                    'vtt_path' => $vtt_path
                ]);
                return redirect(request()->header('Referer'));
              
             
     

                
              
            } else {
                $this->transcription_status = 3;
            }
        } catch(Exception $e) {
            $this->exception = $e;
        }
    }
}
}
