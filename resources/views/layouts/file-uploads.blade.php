<!-- ////////////////////////////////////////////////////////////////////////////////////////////////
                               START SECTION 5 - Upload File
/////////////////////////////////////////////////////////////////////////////////////////////////////-->
<section id="testimonials" class="testimonials">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#fff" fill-opacity="1"
            d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
        </path>
    </svg>
    <div class="container">
        <div class="row text-center text-white">
            <h1 class="display-3 fw-bold">Upload a File</h1>
            <hr style="width: 100px; height: 3px; " class="mx-auto">
            <p class="lead pt-1">Upload Your Audio File</p>
        </div>

        <!-- START THE CAROUSEL CONTENT  -->
        <div class="row align-items-center">
            <div class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">

                    <!--Input File-->

                    <div>
                        <label for="formFileLg" class="form-label" style="color: white;">Supported formats: mp3, mp4,
                            mpeg, mpga, m4a, wav and webm.
                            Max 25 MB.</label>

                        <input class="form-control form-control-lg  @error('audio') is-invalid @enderror" type="file"
                            wire:model.delay="audio"
                            accept="audio/mp3,audio/mp4,audio/mpeg,audio/mpga,audio/ma4,audio/wav,audio/webm;capture=microphone"
                            required>
                        <!-- Loading Indicators If File Upload has started-->
                        <center>
                            <div wire:loading wire:target="audio">
                                <h3 style="color:white"> uploading your audio...</h3>
                                <p style="color:white">This might take a while. </p>
                            </div>
                        </center>
                        <!-- Loading Indicators If Fileupload has ended -->
                        @error('audio')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="text-center">
                <br><br>
                <button type="button" wire:click="translate"
                    class="btn btn-success btn-lg btn-block">Translate</button>
                <br><br>
            </div>


            @if ($transcription_status == 'INITIATED' || $transcription_status == 'TRANSCRIBING')
                @include('loaders.spinner')
            @endif
            @if ($transcription_status == 'TRANSCRIBED')
                <script>
                    Livewire.emit('refreshPage');
                </script>
            @endif
            @if ($transcription_status == 'FAILED')
                <div class="alert alert-warning" role="alert">
                    <script>
                        Livewire.emit('revertTranscriptionStatus');
                    </script>
                    <p class="mb-0">Sorry, We could not process your request currently, please try again later</p>
                </div>
                
            @endif


        </div>
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#fff" fill-opacity="1"
            d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
        </path>
    </svg>
</section>
