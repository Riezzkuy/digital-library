<div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
    <div class="items-end justify-between mb-4 space-y-4 sm:flex sm:space-y-0 md:mb-8">
      <div>
        <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{ __('Queued') }}</h2>
      </div>
    </div>
    <div class="grid gap-4 mb-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        {{-- content --}}
        @foreach ($this->queues as $queue )
            <div wire:key="{{ $queue->id }}" class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="w-full h-56">
                    <a href="#">
                        <img class="h-full mx-auto" src="{{ $queue->copy->book->getCoverUrl() }}" alt="" />
                        <img class="hidden h-full mx-auto" src="{{ $queue->copy->book->getCoverUrl() }}" alt="" />
                    </a>
                </div>
                <div class="pt-6">
                    <a wire:navigate href="{{ route('books.show', $queue->copy->book) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $queue->copy->book->title }}</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

