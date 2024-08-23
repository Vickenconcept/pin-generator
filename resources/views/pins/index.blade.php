<x-app-layout>


    <div>
        <div class=" sm:ml-64">
            <div class="mt-16">

                {{-- <main class=" border  border-gray-700 ">
                    <div class="flex items-center justify-center h-screen bg-gray-200" id="parent_div">
                        <div class="p-4 bg-white border border-gray-300 rounded shadow-lg">
                            <canvas id="canvas" Pin Generator width="210" height="350" style="border:1px solid #000;"></canvas>
                        </div>
                    </div>


                    <form id="templateForm" action="{{ route('templates.store') }}" method="POST"
                        enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <input type="hidden" name="name" id="templateName">
                        <input type="hidden" name="image" id="templateImage">
                        <input type="hidden" name="editable_regions" id="templateRegions">
                    </form>

                    <input type="file" id="replaceImageInput" style="display: none;" />
                    <input type="file" id="imageUploadToShape" style="display:none" />

                </main> --}}

            </div>
            <section class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 p-10 ">
                @foreach ($templates as $template)
                    <div  data-id="{{ $template->id }}" onclick="loadTemplate({{ $template->id }})"
                        class="cursor-pointer rounded-lg shadow hover:shadow-md  overflow-hidden ">
                        <img src="{{ asset('storage/' . $template->path) }}" alt="{{ $template->name }}" class="w-full object-cover"/>
                        <!-- Add more details if needed -->
                        {{-- <button onclick="loadTemplate({{ $template->id }})">select me</button> --}}
                    </div>
                @endforeach
            </section>


            <div class="flex flex-wrap gap-3 px-10 py-10">
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
                                    @if (!empty($region['underline'])) text-decoration: {{ $region['underline'] ?'underline':''}}; @endif
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
            </div>


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

    </div>




    {{-- <x-fabric-js /> --}}

    {{-- <script>
        function loadTemplate(templateId) {
            fetch(`/templates/${templateId}`)
                .then(response => response.json())
                .then(template => {
                    // Clear the canvas before adding the new template
                    canvas.clear();
                    // Load the image onto the canvas
                    fabric.Image.fromURL(template.path, function(img) {
                        // canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                        canvas.renderAll();

                        // Load editable regions
                        const editableRegions = JSON.parse(template.editable_regions);
                        editableRegions.forEach(region => {
                            let fabricObject;

                            if (region.type === 'i-text') {
                                const text = new fabric.IText(region.text, {
                                    left: region.left,
                                    top: region.top,
                                    width: region.width,
                                    height: region.height,
                                    scaleX: region.scaleX || 1,
                                    scaleY: region.scaleY || 1,
                                    fontFamily: region.fontFamily || 'Arial',
                                    fontSize: region.fontSize,
                                    fontStyle: region.fontStyle || '',
                                    fontWeight: region.fontWeight || 'normal',
                                    fill: region.fill || '#000000',
                                    textAlign: region.textAlign || 'left',
                                    stroke: region.stroke || 'transparent',
                                    strokeWidth: region.strokeWidth,
                                    underline: region.underline,
                                    textAlign: region.textAlign || 'left',
                                });
                                canvas.add(text);
                                text.setCoords();
                                // console.log('Loaded font size:', region.fontSize);

                            } else if (region.type === 'image') {
                                fabric.Image.fromURL(region.src, function(img) {
                                    img.set({
                                        left: region.left,
                                        top: region.top,
                                        scaleX: region.width / img.width,
                                        scaleY: region.height / img.height,
                                        opacity: region.opacity || 1
                                    });
                                    canvas.add(img);
                                });
                            } else if (['rect', 'circle', 'triangle', 'line', 'ellipse', 'star',
                                    'arrow'
                                ]
                                .includes(region.type)) {
                                let shape;
                                let commonOptions = {
                                    left: region.left,
                                    top: region.top,
                                    width: region.width,
                                    height: region.height,
                                    fill: region.fill,
                                    stroke: region.stroke || null, // Load the stroke color
                                    strokeWidth: region.strokeWidth || 1, // Load the stroke width
                                    strokeDashArray: region.strokeDashArray ||
                                        null // Load the dash array (if any)
                                };

                                if (region.type === 'rect') {
                                    shape = new fabric.Rect(commonOptions);
                                } else if (region.type === 'circle') {
                                    commonOptions.radius = region.width / 2;
                                    shape = new fabric.Circle(commonOptions);
                                } else if (region.type === 'triangle') {
                                    shape = new fabric.Triangle(commonOptions);
                                } else if (region.type === 'ellipse') {
                                    commonOptions.rx = region.width / 2;
                                    commonOptions.ry = region.height / 2;
                                    shape = new fabric.Ellipse(commonOptions);
                                } else if (region.type === 'line') {
                                    shape = new fabric.Line([region.left, region.top, region.left +
                                        region.width, region.top + region.height
                                    ], {
                                        stroke: region.fill,
                                        strokeWidth: region.strokeWidth || 1,
                                        strokeDashArray: region.strokeDashArray || null
                                    });
                                }

                                if (region.patternSrc) {
                                    // Load the pattern image
                                    fabric.util.loadImage(region.patternSrc, function(img) {
                                        shape.set('fill', new fabric.Pattern({
                                            source: img,
                                            repeat: 'no-repeat',
                                            patternTransform: [region.width / img
                                                .width, 0, 0, region.height /
                                                img.height, 0, 0
                                            ]
                                        }));
                                        canvas.renderAll();
                                    });
                                }

                                canvas.add(shape);
                            } else if (region.type === 'video') {
                                fabric.Video.fromURL(region.src, function(video) {
                                    video.set({
                                        left: region.left,
                                        top: region.top,
                                        scaleX: region.width / video.width,
                                        scaleY: region.height / video.height
                                    });
                                    canvas.add(video);
                                });
                            }


                            // Add more cases for other types if needed

                            if (fabricObject) {
                                canvas.add(fabricObject);
                            }
                        });

                        canvas.renderAll();
                    });
                })
                .catch(error => console.error('Error loading template:', error));
        }
    </script> --}}

</x-app-layout>
