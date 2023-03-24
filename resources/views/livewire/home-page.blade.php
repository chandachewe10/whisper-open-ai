<div>
    

<x-guest-layout>

  <!-- ////////////////////////////////////////////////////////////////////////////////////////
                               START SECTION 1 - THE NAVBAR SECTION  
/////////////////////////////////////////////////////////////////////////////////////////////-->
<nav class="navbar navbar-expand-lg navbar-dark menu shadow fixed-top">
    <div class="container">
      <a class="navbar-brand" href="">
       <h3 style="font-weight: bold;">SPEECH-TEXT</h3>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#services">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#testimonials">Get Started</a></li>
          <li class="nav-item"><a class="nav-link" href="#faq">faq</a></li>
         
          </li>
        </ul>
        <button type="button" class="rounded-pill btn-rounded">
         <a href="https://github.com/chandachewe10/whisper-open-ai.git"> Star on GitHub</a>
          <span>
            <i class="fab fa-github"></i>
          </span>
        </button>
      </div>
    </div>
  </nav>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////
                            START SECTION 2 - THE INTRO SECTION  
/////////////////////////////////////////////////////////////////////////////////////////////////////-->

<section id="home" class="intro-section">
  <div class="container">
    <div class="row align-items-center text-white">
      <!-- START THE CONTENT FOR THE INTRO  -->
      <div class="col-md-6 intros text-start">
        <h1 class="display-2">
          <span class="display-2--intro">Speech to text</span>
          <span class="display-2--description lh-base">
            Translate and transcribe the audio into Text.
          </span>
        </h1>
        <button type="button" class="rounded-pill btn-rounded"><a href="#testimonials">Get Started</a>
          <span><i class="fas fa-arrow-right"></i></span>
        </button>
      </div>
      <!-- START THE CONTENT FOR THE VIDEO -->
      <div class="col-md-6 intros text-end">
        <div class="video-box">
          <img src="{{asset('assets/images/arts/intro-section-illustration.png')}}" alt="video illutration" class="img-fluid">
          <a href="#" class="glightbox position-absolute top-50 start-50 translate-middle">
            <span>
              <i class="fas fa-play-circle"></i>
            </span>
            <span class="border-animation border-animation--border-1"></span>
            <span class="border-animation border-animation--border-2"></span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,160L48,176C96,192,192,224,288,208C384,192,480,128,576,133.3C672,139,768,213,864,202.7C960,192,1056,96,1152,74.7C1248,53,1344,107,1392,133.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
</section>

<!-- //////////////////////////////////////////////////////////////////////////////////////////////
                             START SECTION 3 - THE CAMPANIES SECTION  
////////////////////////////////////////////////////////////////////////////////////////////////////-->

 

<!-- //////////////////////////////////////////////////////////////////////////////////////////////
                         START SECTION 4 - THE SERVICES  
///////////////////////////////////////////////////////////////////////////////////////////////////-->
<br><br>
<section id="services" class="services">
  <div class="container">
    <div class="row text-center">
      <h1 class="display-3 fw-bold">Audio to Text - How it's Done</h1>
      <div class="heading-line mb-1"></div>
    </div>
    
    <div wire:ignore> 
  
   <div id="webvtt-player"   
                 data-audio="{{asset('AUDIOS/TRACKS/sample.mp3')}}"
                 data-transcript="{{asset('AUDIOS/TRACKS/sample.vtt')}}"
                 >
       
    
    <script src="{{asset('assets/js/output.js')}}"></script>

    </div>
   
    </div>
</section>

<br><br>

  <!-- START THE DESCRIPTION CONTENT -->
    <div class="row pt-2 pb-2 mt-0 mb-3">
      <div class="col-md-6 border-right">
        <div class="bg-white p-3">
          <h2 class="fw-bold text-capitalize text-center">
            Learn how to turn audio into text. Translate and transcribe the audio into Text.
          </h2>
        </div>
      </div>
      <div class="col-md-6">
        <div class="bg-white p-4 text-start">
          <p class="fw-light">
            Only files that are less than 25 MB are supported. If you have an audio file that is longer than that, you will need to break it up into chunks of 25 MB's or less or use a compressed audio format. To get the best performance, it is good that you avoid breaking the audio up mid-sentence as this may cause some context to be lost.
          </p>
        </div>
      </div>
    </div>
   

  

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
 <p class="lead pt-1" style="color:white; font-weight:bold">Your Latest Transcriptions will appear here... </p>
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

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////
                       START SECTION 6 - THE FAQ 
//////////////////////////////////////////////////////////////////////////////////////////////////////-->
<section id="faq" class="faq">
  <div class="container">
    <div class="row text-center">
      <h1 class="display-3 fw-bold text-uppercase">faq</h1>
      <div class="heading-line"></div>
      <p class="lead">frequently asked questions, get knowledge befere hand</p>
    </div>
    <!-- ACCORDION CONTENT  -->
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="accordion" id="accordionExample">
          <!-- ACCORDION ITEM 1 -->
          <div class="accordion-item shadow mb-3">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                What are the main features?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <strong>Translate and transcribe the audio into english.</strong> Transcribe audio into whatever language the audio is in. The system will return results for languages not other than english as well, but the quality will be low.
              </div>
            </div>
          </div>
             <!-- ACCORDION ITEM 2 -->
          <div class="accordion-item shadow mb-3">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                What Files can I upload?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                File uploads are currently limited to 25 MB and the following input file types are supported: mp3, mp4, mpeg, mpga, m4a, wav, and webm.
              </div>
            </div>
          </div>
             <!-- ACCORDION ITEM 3 -->
          <div class="accordion-item shadow mb-3">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            What Audio Languages does the system support and translate to?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                The following languages through both transcriptions and translations are supported:

                Afrikaans, Arabic, Armenian, Azerbaijani, Belarusian, Bosnian, Bulgarian, Catalan, Chinese, Croatian, Czech, Danish, Dutch, English, Estonian, Finnish, French, Galician, German, Greek, Hebrew, Hindi, Hungarian, Icelandic, Indonesian, Italian, Japanese, Kannada, Kazakh, Korean, Latvian, Lithuanian, Macedonian, Malay, Marathi, Maori, Nepali, Norwegian, Persian, Polish, Portuguese, Romanian, Russian, Serbian, Slovak, Slovenian, Spanish, Swahili, Swedish, Tagalog, Tamil, Thai, Turkish, Ukrainian, Urdu, Vietnamese, and Welsh.
              </div>
            </div>
          </div>
             <!-- ACCORDION ITEM 4 -->
          <div class="accordion-item shadow mb-3">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Do I need to Pay?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
              <div class="accordion-body">
               <p>The first 5 Speech to Text translation will be free </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


  <x-footer/>

</x-guest-layout>


</div>
