<div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
      <div>
        <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{ __('Loaned') }}</h2>
      </div>
    </div>
    <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        {{-- content --}}
        @foreach ($this->loans as $loan )
            <div wire:key="{{ $loan->id }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="h-56 w-full">
                    <a href="#">
                    <img class="mx-auto h-full" src="{{ $loan->copy->book->getCoverUrl() }}" alt="" />
                    <img class="mx-auto hidden h-full" src="{{ $loan->copy->book->getCoverUrl() }}" alt="" />
                    </a>
                </div>
                <div class="pt-6">
                    <a wire:navigate href="{{ route('books.show', $loan->copy->book->slug) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $loan->title }}</a>


                    <div class="mt-4 flex items-center justify-between gap-4 text-center">
                        <a wire:navigate href="{{ route('copys.read', $loan->copy->id) }}" class="w-full rounded-lg bg-primary-700 px-5 py-2.5 text-sm text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            {{__('Read')}}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


