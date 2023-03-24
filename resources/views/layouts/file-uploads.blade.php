<!-- ////////////////////////////////////////////////////////////////////////////////////////////////
                               START SECTION 5 - Upload File  
/////////////////////////////////////////////////////////////////////////////////////////////////////-->
<section id="testimonials" class="testimonials">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#fff" fill-opacity="1" d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>
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
          <label for="formFileLg" class="form-label" style="color: white;">Supported formats: mp3, mp4, mpeg, mpga, m4a, wav and webm.
            Max 25 MB.</label>
         
          <input class="form-control form-control-lg  @error('audio') is-invalid @enderror" type="file" wire:model.delay="audio" accept="audio/mp3,audio/mp4,audio/mpeg,audio/mpga,audio/ma4,audio/wav,audio/webm;capture=microphone" required>
         <!-- Loading Indicators If File Upload has started--> 
<center>
 <div wire:loading wire:target="audio">
 <h3 style="color:white"> uploading your audio...</h3>
 <p style="color:white">This might take a while</p>
    </div>
</center>
 <!-- Loading Indicators If Fileupload has ended -->
          @error('audio') <span class="error">{{ $message }}</span> @enderror
        </div>
           
          </div>     
        </div>
        <div class="text-center">
         <br><br>
         <button type="button" wire:click="translate" class="btn btn-success btn-lg btn-block">Translate</button>
         <br><br>
        </div>
        
        
@if (!empty($exception))
 <!-- While sending Exception--> 
 
 <div class="alert alert-warning" role="alert">
  <h5 class="alert-heading"> Hello there you've got some negative Feedbacks</h5>
  <hr>
  <p class="mb-0">The system can't transcribe your audio</p>
</div>
 <!-- End while sending Exception-->
@endif


        
 <!-- Loading Indicators If Transcription has started--> 
<center>
 <div wire:loading wire:target="translate">
 <h3 style="color:white"> Transcription in progress...</h3>
<p style="color:white">This might take a while depending on the length of your audio</p>
    </div>
</center>
 <!-- Loading Indicators If Transcription has ended -->



 <!-- Loading Indicators If User has transcribed more than twice--> 
 <center>
 <div>
        @if (session()->has('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif
    </div>
    </center>
 <!-- Loading Indicators If User has transcribed more than twice has ended -->
 




 <!--Transcript-->
 <br>
 <p id="latest-transcriptions" class="lead pt-1" style="color:white; font-weight:bold">Your Latest Transcriptions will appear here... </p>
<hr>
 @php   
$web_vtt = \App\Models\ip::where('ip',"=",'41.60.184.49')->latest()->first();
$audio_path = \App\Models\ip::where('ip',"=",'41.60.184.49')->latest()->first();

$vtt = $web_vtt->vtt_path ?? '';
$audio = $audio_path->audio_path ?? '';
@endphp


 <div id="webvtt" wire:ignore   
                 data-audio="{{asset('AUDIOS/'.$audio)}}"
                 data-transcript="{{asset('WEBVTT/VTTFILES/'.$vtt)}}"
                 >
       
    
    <script src="{{asset('assets/js/output2.js')}}"></script>

    </div>




 <!--end showing transcript-->




<!--If Errors are present -->
@if ($transcription_status == 3)
 
 <div class="form-group">
    <label for="results">Hello you've got some negative feedbacks!</label>
    <textarea class="form-control" rows="8" readonly>We were unable to transcribe your audio. The system is busy, please try again later.</textarea>
  </div>
 
  
  @endif
  <!--End showing errors here -->   
      </div>
    </div>
 
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#fff" fill-opacity="1" d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
</section>
