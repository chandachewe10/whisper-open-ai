<?php

namespace App\Http\Controllers;

use App\Jobs\TranscribeAudio;
use App\Models\ip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Transcribe extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transcription_status = 0;
        ini_set('max_execution_time', 180); //3 minutes
        /**
         * Check if the user has transcribed more than twice.
         */
        $users_ip = '41.60.184.49'; //request()->ip;
        $check = ip::where('ip', '=', $users_ip)->get();
        if (count($check) >= 3) {
            session()->flash('message', 'You can only transcribe twice as at now, and you have reached the maximum!');
        } else {
            $request->validate([
                'audio' => 'file|max:25600', // 24 MB Max
            ]);

            $file_path = $request->file('audio')->store('TRACKS');
            TranscribeAudio::dispatch($file_path, $transcription_status, $users_ip);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
