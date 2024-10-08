<x-app-layout>
    <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
          <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
            <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
              <img class="w-full dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" alt="" />
              <img class="w-full hidden dark:block" src="{{ $book->getCoverUrl() }}" alt="" />
            </div>

            <div class="mt-6 sm:mt-8 lg:mt-0">
              <h1
                class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
              >
                {{ $book->title }}
              </h1>

              <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

              <p class="mb-6 text-gray-500 dark:text-gray-400">
                {{ $book->description }}
              </p>


            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 text-xl font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Book Details
                        </th>
                    </tr>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Authors
                        </th>
                        <td class="px-6 py-4">
                            @foreach ($book->authors as $author)
                                <li>{{ $author->name }}</li>
                            @endforeach
                        </td>
                    </tr>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Categories
                        </th>
                        <td class="px-6 py-4">
                            @foreach ($book->categories as $category)
                                <li>{{ $category->name }}</li>
                            @endforeach
                        </td>
                    </tr>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Publisher
                        </th>
                        <td class="px-6 py-4">
                            {{ $book->publisher->name }}
                        </td>
                    </tr>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Published At
                        </th>
                        <td class="px-6 py-4">
                            {{ $book->published_at }}
                        </td>
                    </tr>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Pages
                        </th>
                        <td class="px-6 py-4">
                            {{ $book->pages }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- button --}}
            <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
                <livewire:book-loan :bookId="$book->id" />
            </div>

            </div>
          </div>
        </div>
      </section>
</x-app-layout>
