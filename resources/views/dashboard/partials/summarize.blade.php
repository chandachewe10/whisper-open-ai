<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Summarize My Audio') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Only Mark this circle if you want to convert your audio into a meeting otherwise leave this circle empty. If you mark this circle it will remove unnecessary information from your audio and shorten it in order to extract the key points, decisions, action items, and important information discussed during a meeting.") }}
        </p>
    </header>

    
            <div>
           <br><br>
        
            <div class="mt-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="loadAudio" name="option" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2"><strong>Summarize</strong> my audio.</span>
                </label>
            </div>
        
            
        
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        


       
   
</section>
