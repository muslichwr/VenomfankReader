<div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
    @if(count($images) > 0)
        <div class="flex flex-col items-center space-y-4">
            @foreach($images as $image)
                <div class="w-full flex justify-center">
                    <img src="{{ asset('storage/' . $image) }}" alt="Chapter page {{ $loop->iteration }}" class="max-w-full h-auto rounded-lg shadow-md">
                </div>
            @endforeach
        </div>
    @else
        <div class="p-8 text-center">
            <p class="text-gray-500 dark:text-gray-400">No images available for this chapter.</p>
        </div>
    @endif
</div> 