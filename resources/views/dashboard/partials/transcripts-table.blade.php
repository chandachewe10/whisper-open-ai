<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Transcripts') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Here is the list of transcripts you have transcribed before. You can download these transcripts to PDF or Word Documents, you can also send them to your email address by clicking on 'mail me'. Please note that once you decide to delete the transcript it can't be recovered. ") }}
        </p>
    </header>

    @php
        
        $transcripts = \App\models\ip::where('user_id', '=', auth()->user()->id)
            ->latest()
            ->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border">
                            <thead>
                                <tr>
                                    <th class="border p-2">No</th>
                                    <th class="border p-2">Status</th>
                                    <th class="border p-2">PDF</th>
                                    <th class="border p-2">Word</th>
                                    <th class="border p-2">Mail Me</th>
                                    <th class="border p-2">Date</th>
                                    <th class="border p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @forelse($transcripts as $transcript)
                                        <td class="border p-2">{{ $transcript->id }}</td>
                                        <td class="border p-2">{{ $transcript->transcription_status }}</td>
                                        <td class="border p-2"></td>
                                        <td class="border p-2"></td>
                                        <td class="border p-2">{{ $transcript->created_at }}</td>
                                        <td class="border p-2">
                                            <button class="btn btn danger"><i class="fas fa-trash"></i> Delete</button>
                                            <button class="btn btn info" wire:click="playAudio"><i class="fas fa-trash"></i> Play</button>

                                        </td>
                                    @empty
                                        <p>No transcripts available</p>
                                        <br>
                                    @endforelse
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
