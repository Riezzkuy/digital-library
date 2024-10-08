<section class="spaec-y-6">
    @if ($isLoaned)
        <a wire:navigate href="{{ route('copys.read', $activeLoan->copy->id) }}" class="flex justify-center items-center px-5 py-2.5 mt-4 text-sm font-medium text-white rounded-lg sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
            {{__('Read')}}
        </a>
    @elseif ($stock)
        @auth
            <button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-loan-book')"
                class="flex justify-center items-center px-5 py-2.5 mt-4 text-sm font-medium text-white rounded-lg sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                role="button">
                {{__('Loan')}}
            </button>
        @else
            <a href="{{ route('login') }}" class="flex justify-center items-center px-5 py-2.5 mt-4 text-sm font-medium text-white rounded-lg sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                {{__('Login to Loan')}}
            </a>
        @endauth


        <x-modal name="confirm-loan-book" :show="$errors->isNotEmpty()" focusable>
            <form wire:submit.prevent="loanBook({{ $bookId }})" class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to loan this book?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once you loaned it will be returned automatically in 7 days.') }}
                </p>

                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-md ms-3 bg-primary-600 hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Loan Book') }}
                    </button>
                </div>
            </form>
        </x-modal>
        @elseif ($isQueued)
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-cancel-queue')"
            class="flex justify-center items-center px-5 py-2.5 mt-4 text-sm font-medium text-white rounded-lg sm:mt-0 bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
            role="button">
            {{__('Cancel Queue')}}
        </button>

        <x-modal name="confirm-cancel-queue" :show="$errors->isNotEmpty()" focusable>
            <form wire:submit.prevent="cancelQueue({{ $bookId }})" class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to cancel the queue for this book?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once you cancel, you will be removed from the waiting list for this book.') }}
                </p>

                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('No') }}
                    </x-secondary-button>

                    <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-md ms-3 bg-red-600 hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Yes, Cancel Queue') }}
                    </button>
                </div>
            </form>
        </x-modal>
    @else
        @auth
            <button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-queue-book')"
                class="flex justify-center items-center px-5 py-2.5 mt-4 text-sm font-medium text-white rounded-lg sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                role="button">
                {{ __('Queue') }}
            </button>
        @else
            <a href="{{ route('login') }}" class="flex justify-center items-center px-5 py-2.5 mt-4 text-sm font-medium text-white rounded-lg sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                {{__('Login to Queue')}}
            </a>
        @endauth
        <x-modal name="confirm-queue-book" :show="$errors->isNotEmpty()" focusable>
            <form wire:submit.prevent="queueBook({{ $bookId }})" class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to wait for this book?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once you are in the queue, you will be on the waiting list to loan this book automatically when it becomes available.') }}
                </p>

                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out border border-transparent rounded-md ms-3 bg-primary-600 hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Queue Book') }}
                    </button>
                </div>
            </form>
        </x-modal>
    @endif
</section>
