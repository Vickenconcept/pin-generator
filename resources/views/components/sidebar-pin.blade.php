<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full  border-r  sm:translate-x-0 bg-slate-400 border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-slate-400">
        <ul class="space-y-2 font-medium">
            
            <li>
                <a href="#" class="flex items-center p-2  rounded-lg text-white hover:bg-gray-700 group">
                    <svg class="w-5 h-5  transition duration-75 text-gray-800 group-hover:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

        </ul>

        <div class="space-y-4">
            <div>
                <label for="canvasWidth" class="text-gray-900 text-xs font-bold">Canvas Width:</label>
                <input type="number" id="canvasWidth" min="1" placeholder="Width" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-slate-500 focus:border-slate-500 block w-full py-2.5  rounded-md">
                <label for="canvasHeight" class="text-gray-900 text-xs font-bold">Canvas Height:</label>
                <input type="number" id="canvasHeight" min="1" placeholder="Height" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-slate-500 focus:border-slate-500 block w-full py-2.5  rounded-md">
            </div>



            



            <button class="px-4 py-1.5 rounded-md border-2 border-gray-900 shadow-inner shadow-gray-700 hover:shadow-xl text-gray-100 bg-gray-900  hover:bg-gray-200 hover:text-gray-900 delay-75 transition duration-700 ease-in-out  " id="addText">Add
                Text</button>

            <button class="px-4 py-1.5 rounded-md border-2 border-gray-900 shadow-inner shadow-gray-700 hover:shadow-xl text-gray-100 bg-gray-900  hover:bg-gray-200 hover:text-gray-900 delay-75 transition duration-700 ease-in-out  " id="addImage">Add
                Image</button>
            <input type="file" id="uploadImage" style="display:none;">

            <button class="px-4 py-1.5 rounded-md border-2 border-gray-900 shadow-inner shadow-gray-700 hover:shadow-xl text-gray-100 bg-gray-900  hover:bg-gray-200 hover:text-gray-900 delay-75 transition duration-700 ease-in-out  " id="addVideo">Add
                Video</button>
            {{-- <canvas id="c"></canvas> --}}
            <video id="video1" style="display:none;" controls></video>

            <button class="px-4 py-1.5 rounded-md border-2 border-gray-900 shadow-inner shadow-gray-700 hover:shadow-xl text-gray-100 bg-gray-900  hover:bg-gray-200 hover:text-gray-900 delay-75 transition duration-700 ease-in-out  " id="enableDrawing">
                Drawing
                <i class='bx bxs-pen'></i>
            </button>



            <button id="dropdownShapes" data-dropdown-toggle="dropdown-shapes"
                class="px-4 py-1.5 rounded-md border-2 border-gray-900 shadow-inner shadow-gray-700 hover:shadow-xl text-gray-100 bg-gray-900  hover:bg-gray-200 hover:text-gray-900 delay-75 transition duration-700 ease-in-out whitespace-nowrap"  type="button">Dropdown-shapes button <i class='bx bxs-chevron-down'></i>
            </button>

            <!-- Dropdown menu -->
            <div id="dropdown-shapes" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 ">
                <ul class="py-2 text-sm text-gray-700 " aria-labelledby="dropdownShapes">
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addRect">
                            <i class='bx bxs-square text-2xl'></i> Rectangle
                        </button>
                    </li>
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addCircle">
                            <i class='bx bxs-circle text-2xl'></i> Circle
                        </button>
                    </li>
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addEllipse">
                            <i class='bx bxs-circle text-2xl'></i> Ellipse
                        </button>
                    </li>
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addTriangle">
                            <i class='bx bx-left-arrow text-2xl'></i> Triangle
                        </button>
                    </li>
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addStar">
                            <i class='bx bxs-star text-2xl'></i> Star
                        </button>
                    </li>
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addLine">
                            <span class="text-2xl font-semibold mr-1">/</span> Line
                        </button>
                    </li>
                    <li>
                        <button class=" w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center " id="addArrow">
                            <i class='bx bx-up-arrow-alt text-2xl'></i> Arrow
                        </button>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</aside>
