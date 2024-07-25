<x-app-layout>
    <div class="container mx-auto py-8">
        <div id="pdf-viewer" class="border rounded-lg overflow-hidden shadow-lg" style="width: 100%; height: 100%;"></div>
        <div class="flex items-center justify-between my-4">
            <button id="prev-page" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Previous Page</button>
            <span class="dark:text-white">Page: <span id="page-num" class="font-bold dark:text-white"></span> / <span id="page-count" class="font-bold dark:text-white"></span></span>
            <button id="next-page" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Next Page</button>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

        var url = "{{ asset('storage/' . $copy->file) }}";
        var pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            canvas = document.createElement('canvas'),
            ctx = canvas.getContext('2d');

        document.getElementById('pdf-viewer').appendChild(canvas);

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                var viewerWidth = document.getElementById('pdf-viewer').clientWidth;
                var viewport = page.getViewport({ scale: 1 });
                scale = viewerWidth / viewport.width;
                viewport = page.getViewport({ scale: scale });

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('page-num').textContent = num;
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        }

        document.getElementById('prev-page').addEventListener('click', onPrevPage);
        document.getElementById('next-page').addEventListener('click', onNextPage);

        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page-count').textContent = pdfDoc.numPages;
            renderPage(pageNum);
        }).catch(function(reason) {
            console.error('Error loading PDF:', reason);
        });

        window.addEventListener('resize', function() {
            if (pdfDoc) {
                renderPage(pageNum);
            }
        });
    </script>
    @endpush
</x-app-layout>
