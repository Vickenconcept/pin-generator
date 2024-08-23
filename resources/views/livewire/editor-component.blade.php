<div>
    <x-sidebar-editor />

    <div class=" sm:ml-64">
        <div class="mt-16">

            <div class="bg-slate-400 flex flex-wrap  w-full px-4 py-2  space-x-2 ">



                <section id="hiddenTextControlDiv" style="display:none;" class="space-x-2 flex items-center">
                    <div class="space-x-2 flex items-center">

                        <select id="tagSelector"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm font-semibold rounded-lg focus:ring-slate-500 focus:border-slate-500 block  p-2.5 w-40">
                            <option selected>Use detected text</option>
                            <option value="title">Title</option>
                            <option value="description">Description</option>
                            <option value="url">URL</option>
                            <option value="shorturl">Short URL</option>
                            <option value="date">Date</option>
                            <option value="brandname">Brand Name</option>
                            <option value="custom">Custom</option>
                        </select>

                        <div class="max-w-sm mx-auto">
                            <select id="fontFamily"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm font-semibold rounded-lg focus:ring-slate-500 focus:border-slate-500 block w-full p-2.5 ">
                                <option selected>Choose a Font</option>
                                <option value="Arial">Arial</option>
                                <option value="Helvetica">Helvetica</option>
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Courier New">Courier New</option>
                                <option value="Verdana">Verdana</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Palatino">Palatino</option>
                                <option value="Garamond">Garamond</option>
                                <option value="Bookman">Bookman</option>
                                <option value="Comic Sans MS">Comic Sans MS</option>
                                <option value="Trebuchet MS">Trebuchet MS</option>
                                <option value="Arial Black">Arial Black</option>
                                <option value="Impact">Impact</option>
                                <option value="Lucida Sans Unicode">Lucida Sans Unicode</option>
                                <option value="Tahoma">Tahoma</option>
                                <option value="Geneva">Geneva</option>
                                <option value="MS Serif">MS Serif</option>
                                <option value="Lucida Console">Lucida Console</option>
                                <option value="Monaco">Monaco</option>
                            </select>
                        </div>

                        <div class="max-w-xs mx-auto">
                            <div class="relative flex items-center max-w-[8rem]">
                                <button type="button" id="decreaseTextSize"
                                    data-input-counter-decrement="quantity-input"
                                    class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none">
                                    <svg class="w-3 h-3 text-gray-900 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 1h16" />
                                    </svg>
                                </button>
                                <input type="text" id="textSizeInput" step="1" max="60"
                                    data-input-counter data-input-counter-min="1" data-input-counter-max="60"
                                    aria-describedby="helper-text-explanation"
                                    class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-slate-500 focus:border-slate-500 block w-full py-2.5   "
                                    placeholder="999" value="5" required />
                                <button type="button" id="increaseTextSize"
                                    data-input-counter-increment="quantity-input"
                                    class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none">
                                    <svg class="w-3 h-3 text-gray-900 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 1v16M1 9h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <input type="color"
                                class="p-1 h-10 w-10 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none "
                                id="textColor" value="#2563eb" title="Choose your color">
                        </div>

                        <div class="flex space-x-2 text-gray-700">
                            <button type="button"
                                class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg px-4 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none font-bold"
                                id="boldText">
                                B
                            </button>
                            <button type="button"
                                class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg px-4 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none italic font-semibold"
                                id="italicText">
                                I
                            </button>
                            <button type="button"
                                class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg px-4 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none font-semibold underline"
                                id="underlineText">
                                U
                            </button>
                        </div>
                        <div class="flex space-x-2 text-gray-700">
                            <button type="button"
                                class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg px-4 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none font-bold "
                                id="textCase">
                                Tt
                            </button>
                            <button type="button"
                                class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg px-4 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none font-semibold underline"
                                id="textAlign">
                                =
                            </button>
                        </div>
                    </div>
                </section>

                <section id="HiddenDrawingDiv" style="display:none;">
                    <div class="space-x-2 flex items-center">
                        <div>
                            <input type="color"
                                class="p-1 h-10 w-10 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none "
                                id="pencilColorPicker" value="#2563eb" title="Choose your color">
                        </div>
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  text-xs h-11"
                            id="disableDrawing">Disable
                            Drawing</button>

                    </div>
                </section>

                <div id="hiddenShapeControlDiv" style="display:none;">
                    <div>
                        <input type="color"
                            class="p-1 h-10 w-10 block bg-white border border-gray-200 cursor-pointer rounded-lg disabled:opacity-50 disabled:pointer-events-none "
                            id="shapeColor" value="#2563eb" title="Choose your color">
                    </div>
                </div>
                {{-- image starts here --}}
                <div id="hiddenImageControlDiv" style="display:none;">
                    <div id="opacityControl" class="text-center">
                        <label class="block text-xs font-bold" for="opacityRange">Opacity: </label>
                        <input type="range" id="opacityRange" min="0" max="1" step="0.01">
                        <span class="block text-sm font-bold" id="opacityValue">100%</span>
                    </div>

                </div>
                {{-- image control ends here --}}



                <section id="hiddenMultipleSelect" style="display:none;">
                    <div class="space-x-2 flex items-center">
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                            id="toggleGroupObjects">Group</button>

                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                            id="deleteObject">Delete
                            Selected
                            Object</button>
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                            id="clearCanvas">Clear
                            Canvas</button>
                    </div>
                </section>

                <section id="uniformDisplayMenu" style="display:none;">
                    <div class="space-x-2 flex items-center">
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none "
                            id="bringToFront"><i class='bx bx-minus-front text-md'></i></button>
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none "
                            id="sendToBack"><i class='bx bx-minus-back text-md'></i></button>

                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none "
                            id="duplicateObject"><i class='bx bxs-duplicate text-md'></i></button>
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none "
                            id="undo"><i class='bx bx-undo text-md'></i></button>
                        <button
                            class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none "
                            id="redo"><i class='bx bx-redo text-md'></i></button>

                    </div>
                </section>

                {{-- general setting --}}
                <div>
                    <label for="cornerRadiusRange">Corner Radius:</label>
                    <input type="range" id="cornerRadiusRange" min="0" max="100" value="0">

                    <label for="border-width">Border Width:</label>
                    <input type="range" id="border-width" min="1" max="20" value="1"
                        class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none">

                    <label for="border-color">Border Color:</label>
                    <input type="color" id="border-color" value="#000000">




                    <button onclick="addBorderToSelectedObject(canvas, 'red', 3);"
                        class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                        type="button"><i class='bx bx-menu text-xl'></i>
                    </button>



                    <button
                        class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                        id="saveTemplate">Save
                        Template</button>
                    <button
                        class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                        id="saveAsJSON">saveAsJSON</button>
                    <button
                        class="bg-gray-100  hover:bg-gray-200 border border-gray-300 rounded-lg p-3 h-11 focus:ring-gray-100  focus:ring-2 focus:outline-none  "
                        id="saveAsImage">saveAsImage</button>
                </div>

            </div>



            <main class=" border  border-gray-700 ">
                <div class="flex items-center justify-center h-screen bg-gray-200" id="parent_div">
                    <div class="p-4 bg-white border border-gray-300 rounded shadow-lg">
                        <canvas id="canvas" Pin Generator width="210" height="350"
                            style="border:1px solid #000;"></canvas>
                    </div>
                </div>


                <form id="templateForm" action="{{ route('templates.store') }}" method="POST"
                    enctype="multipart/form-data" style="display: none;">
                    @csrf
                    <input type="hidden" name="name" id="templateName">
                    <input type="hidden" name="image" id="templateImage">
                    <input type="hidden" name="height" id="canvas_height">
                    <input type="hidden" name="width" id="canvas_width">
                    <input type="hidden" name="editable_regions" id="templateRegions">
                </form>

                <input type="file" id="replaceImageInput" style="display: none;" />

                <input type="file" id="videoUpload" accept="video/*" style="display: none;">
                <input type="file" id="imageUploadToShape" style="display:none" />

                <input type="file" id="bgImageInput" accept="image/*">


            </main>
        </div>
    </div>



</div>
