<x-customize-layout>

    {{-- <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full  border-r  sm:translate-x-0 bg-slate-400 border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-slate-400">
            <ul class="space-y-2 font-medium">

                <li>
                    <a href="#" class="flex items-center p-2  rounded-lg text-white hover:bg-gray-700 group">
                        <svg class="w-5 h-5  transition duration-75 text-gray-800 group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

            </ul>

            <form action="{{ route('pins.generateRandom') }}" method="POST">
                @csrf
                <label for="template_ids">Choose templates:</label>
                <select name="template_ids[]" id="template_ids" multiple>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>

                <label for="pin_count">Number of pins to generate:</label>
                <input type="number" name="pin_count" id="pin_count" min="1">

                <button type="submit">Generate Pins</button>
            </form>
        </div>
    </aside> --}}

    {{-- sidebar ends here --}}

    <generate-component></generate-component>
    {{-- <div>
        <div class=" sm:ml-64">
            <div class="flex flex-wrap gap-3 px-10 py-10 mt-10">
                @foreach ($pins as $pin)
                    <div class="relative w-full  border rounded-lg overflow-hidden shadow-lg bg-white"
                        style=" height: {{ $pin->height }}px; width: {{ $pin->width }}px">
                        @foreach ($pin->editable_regions as $region)
                            @if ($region['type'] === 'circle')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    @if ($region['width']) width: {{ $region['width'] }}px; @endif
                                    @if (!empty($region['height'])) height: {{ $region['height'] }}px; @endif
                                    @if (!empty($region['fill'])) background-color: {{ $region['fill'] }}; @endif
                                    @if (!empty($region['stroke']) && !empty($region['strokeWidth'])) border: {{ $region['strokeWidth'] }}px solid {{ $region['stroke'] }};
                        @else
                        border: none; @endif
                                    border-radius: 50%;
                                    background-image: url({{ $region['patternSrc'] ?? '' }}); /* If pattern is used */
                                    background-size: cover;
                                ">
                                </div>
                            @elseif ($region['type'] === 'rect')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    width: {{ $region['width'] }}px;
                                    @if (!empty($region['height'])) height: {{ $region['height'] }}px; @endif
                                    @if (!empty($region['fill'])) background-color: {{ $region['fill'] }}; @endif
                                    @if (!empty($region['stroke']) && !empty($region['strokeWidth'])) border: {{ $region['strokeWidth'] }}px solid {{ $region['stroke'] }};
                    @else
                        border: none; @endif
                                    background-image: url({{ $region['patternSrc'] ?? '' }}); /* If pattern is used */
                                    background-size: cover;
                                ">
                                </div>
                            @elseif ($region['type'] === 'i-text')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    font-size: {{ $region['fontSize'] }}px;
                                    font-family: {{ $region['fontFamily'] }};
                                    color: {{ $region['fill'] }};
                                    @if (!empty($region['fontWeight'])) font-weight: {{ $region['fontWeight'] }}; @endif
                                    @if (!empty($region['stroke']) && !empty($region['strokeWidth'])) text-stroke: {{ $region['strokeWidth'] }}px {{ $region['stroke'] }};
                                    -webkit-text-stroke: {{ $region['strokeWidth'] }}px {{ $region['stroke'] }}; @endif
                                    @if (!empty($region['underline'])) text-decoration: {{ $region['underline'] ? 'underline' : '' }}; @endif
                                ">
                                    {!! nl2br(e($region['text'])) !!}

                                </div>
                            @elseif ($region['type'] === 'ellipse')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    width: {{ $region['width'] }}px;
                                    @if (!empty($region['height'])) height: {{ $region['height'] }}px; @endif
                                    @if (!empty($region['fill'])) background-color: {{ $region['fill'] }}; @endif
                                    @if (!empty($region['stroke']) && !empty($region['strokeWidth'])) border: {{ $region['strokeWidth'] }}px solid {{ $region['stroke'] }};
                    @else
                        border: none; @endif
                                    border-radius: 50% / {{ ($region['ry'] / $region['rx']) * 100 }}%;
                                    background-image: url({{ $region['patternSrc'] ?? '' }}); /* If pattern is used */
                                    background-size: cover;
                                ">
                                </div>
                            @elseif ($region['type'] === 'triangle')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    width: 0;
                                    height: 0;
                                    @if (!empty($region['fill'])) background-color: {{ $region['fill'] }}; @endif
                                    @if (!empty($region['stroke']) && !empty($region['strokeWidth'])) border: {{ $region['strokeWidth'] }}px solid {{ $region['stroke'] }};
                    @else
                        border: none; @endif
                                    border-left: {{ $region['width'] / 2 }}px solid transparent;
                                    border-right: {{ $region['width'] / 2 }}px solid transparent;
                                    border-bottom: {{ $region['height'] }}px solid {{ $region['fill'] }};
                                ">
                                </div>
                            @elseif ($region['type'] === 'line')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    width: {{ $region['width'] }}px;
                                    height: {{ $region['strokeWidth'] }}px;
                                    @if (!empty($region['fill'])) background-color: {{ $region['fill'] }}; @endif
                                ">
                                </div>
                            @elseif ($region['type'] === 'image')
                                <div
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    width: {{ $region['width'] }}px;
                                    @if (!empty($region['height'])) height: {{ $region['height'] }}px; @endif
                                    background-image: url({{ $region['src'] }});
                                    background-size: cover;
                                ">
                                </div>
                            @elseif ($region['type'] === 'video')
                                <video
                                    style="
                                    position: absolute;
                                    left: {{ $region['left'] }}px;
                                    top: {{ $region['top'] }}px;
                                    width: {{ $region['width'] }}px;
                                    @if (!empty($region['height'])) height: {{ $region['height'] }}px; @endif
                                "
                                    controls>
                                    <source src="{{ $region['src'] }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        @endforeach
                    </div>
                @endforeach

                <div class="relative w-1/4  border rounded-lg overflow-hidden shadow-lg bg-white h-72">
                    this isit
                    <button onclick="sendTemplateData({{ $templates }})">click me</button>
                </div>
            </div>

        </div>

    </div> --}}

    {{-- <script>
        function extractTemplateIds(templates) {
            let templateIds = [];
            templates.forEach(template => {
                templateIds.push(template.id);
            });
            return templateIds;
        }


        function getRandomTemplateId(templateIds) {
            const randomIndex = Math.floor((Math.random() * Date.now()) % templateIds.length);
            return templateIds[randomIndex];
        }

        function sendTemplateData(templates) {

            const templateIds = extractTemplateIds(templates);

            console.log('Template IDs:', templateIds);

            const selectedTemplateId = getRandomTemplateId(templateIds);

            console.log('Selected Template ID:', selectedTemplateId);

            // Create the data object to send
            const data = {
                template_ids: [selectedTemplateId],
                pin_count: 1 // Replace with the actual pin_count value
            };

            // Send the POST request
            axios.post('/pins/generate-random', data)
                .then(response => {
                    console.log('Success:', response.data);
                })
                .catch(error => {
                    console.error('Error:', error.response);
                });
        }
    </script> --}}
</x-customize-layout>
