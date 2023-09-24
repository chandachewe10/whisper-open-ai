<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Upload Your Audio To Transcribe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Transcribe your Audio at only k10/minute. The amount to pay will be shown to you after you upload your audio so that the system can analyse the number of minutes your audio contains.") }}
        </p>
    </header>

    <form id="send-audio" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="audio" :value="__('Upload Audio')" />
            <x-text-input id="audio" name="audio" type="file" class="mt-1 block w-full" :value="old('audio')" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Transcribe') }}</x-primary-button>
          
        </div>  
    </form>
</section>
