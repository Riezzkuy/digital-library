<div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
    <div class="items-end justify-between mb-4 space-y-4 sm:flex sm:space-y-0 md:mb-8">
      <div>
        <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">{{ __('Loaned') }}</h2>
        <span class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Total loaned books this week') }} : {{ $loanedBooksCount }} / 2
        </span>
      </div>
    </div>
    <div class="grid gap-4 mb-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        {{-- content --}}
        @foreach ($this->loans as $loan )
            <div wire:key="{{ $loan->id }}" class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="w-full h-56">
                    <a href="#">
                    <img class="h-full mx-auto" src="{{ $loan->copy->book->getCoverUrl() }}" alt="" />
                    <img class="hidden h-full mx-auto" src="{{ $loan->copy->book->getCoverUrl() }}" alt="" />
                    </a>
                </div>
                <div class="pt-6">
                    <a wire:navigate href="{{ route('books.show', $loan->copy->book->slug) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $loan->title }}</a>


                    <div class="flex items-center justify-between gap-4 mt-4 text-center">
                        <a wire:navigate href="{{ route('copys.read', $loan->copy->id) }}" class="w-full rounded-lg bg-primary-700 px-5 py-2.5 text-sm text-center font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            {{__('Read')}}
                        </a>
                        <button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-return-book')"
                            class="w-full rounded-lg bg-red-700 px-5 py-2.5 text-sm text-center font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                            role="button">
                            {{__('Return')}}
                        </button>
                        <x-modal name="confirm-return-book" :show="$errors->isNotEmpty()" focusable>
                            <form wire:submit.prevent="returnBook({{ $loan->id }})" class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Return this book?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Once you return it, it will be available for other users.') }}
                                </p>

                                <div class="flex justify-end mt-6">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancel') }}
                                    </x-secondary-button>

                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-md ms-3 bg-primary-600 hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        {{ __('Return') }}
                                    </button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


