<div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
      <div>
        <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{ __('Queued') }}</h2>
      </div>
    </div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        {{-- content --}}
        @foreach ($this->queues as $queue )
            <div wire:key="{{ $queue->id }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="h-56 w-full">
                    <a href="#">
                    <img class="mx-auto h-full" src="{{ $queue->copy->book->getCoverUrl() }}" alt="" />
                    <img class="mx-auto hidden h-full" src="{{ $queue->copy->book->getCoverUrl() }}" alt="" />
                    </a>
                </div>
                <div class="pt-6">
                    <a wire:navigate href="{{ route('books.show', $queue->copy->book->slug) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $queue->title }}</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

