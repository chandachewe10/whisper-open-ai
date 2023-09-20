<!-- //////////////////////////////////////////////////////////////////////////////////////////////
                         START SECTION 4 - SAMPLE FORMAT
///////////////////////////////////////////////////////////////////////////////////////////////////-->
<br><br>
<section id="services" class="services">
    <div class="container">
        <div class="row text-center">
            <h1 class="display-3 fw-bold">Audio to Text - How it's Done</h1>
            <div class="heading-line mb-1">Your Latest Transcription(s) will replace this Demo</div>
        </div>


        <!--Transcript-->
        <br>

        <hr>
        @php
            $web_vtt = \App\Models\ip::where('ip', '=', '41.60.184.49')
                ->latest()
                ->first();
            $audio_path = \App\Models\ip::where('ip', '=', '41.60.184.49')
                ->latest()
                ->first();
            
            $audio = $audio_path->audio_path ?? 'TRACKS/sample.mp3';
            $vtt = $web_vtt->vtt_path ?? 'VTTFILES/sample.vtt';
        @endphp


        <div id="webvtt" wire:ignore data-audio="{{ asset('AUDIOS/' . $audio) }}"
            data-transcript="{{ asset('WEBVTT/' . $vtt) }}">


            <script src="{{ asset('assets/js/output2.js') }}"></script>

        </div>




        <!--end showing transcript-->


</section>

<br><br>
