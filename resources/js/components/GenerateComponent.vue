<script setup>
import { ref, onMounted, watch, watchEffect } from "vue";
import { FwbButton, FwbModal } from 'flowbite-vue'
import { usePin } from './../composables/usePin'
import { useTemplate } from './../composables/useTemplate'

const activePin = ref(null);

const pinId = ref(null);

const pinTitle = ref('')
const pinDescription = ref('');
const pinUrl = ref('');
const pinBrandName = ref('');
const pinCustomText = ref('');
const pinDate = ref('');
const pinShorturl = ref('');



const {
    pin,
    pins,
    getPin,
    getPins,
    createNewPin,
    updatePin,
    deletePin,
} = usePin()

const {
    templates,
    getTemplates,
} = useTemplate()

onMounted(() => {
    getPins();
    getTemplates()
});






const isShowModal = ref(false)

function closeModal() {
    isShowModal.value = false;
    pinId.value = null;
}
function showModal(id) {
    isShowModal.value = true
    pinId.value = id;


    // getPin(pinId.value);

    const selectedPin = pins.value.find(pin => pin.id === id);

    if (selectedPin) {
        selectedPin.editable_regions.forEach(region => {
            if (region['type'] === 'i-text') {
                switch (region.tag) {
                    case 'title':
                        pinTitle.value = region.text;
                        break;
                    case 'description':
                        pinDescription.value = region.text;
                        break;
                    case 'url':
                        pinUrl.value = region.text;
                        break;
                    case 'brandname':
                        pinBrandName.value = region.text;
                        break;
                    case 'shorturl':
                        pinShorturl.value = region.text;
                        break;
                    case 'date':
                        pinDate.value = region.text;
                        break;
                    case 'custom':
                        pinCustomText.value = region.text;
                        break;
                    default:
                        console.error('Unknown tag:', region.tag);
                }
            }
        });


        pinId.value = selectedPin.id;
    } else {
        console.error('Pin not found with ID:', pinId);
    }

}








function updateSelectedPinDetails(id) {
    activePin.value = pins.value.find(pin => pin.id === id);

    if (activePin.value) {
        activePin.value.editable_regions.forEach(region => {
            if (region['i-type'] === 'i-text') {
                switch (region.tag) {
                    case 'title':
                        pinTitle.value = region.text;
                        break;
                    case 'description':
                        pinDescription.value = region.text;
                        break;
                    case 'url':
                        pinUrl.value = region.text;
                        break;
                    default:
                        console.error('Unknown tag:', region.tag);
                }
            }
        });

        pinId.value = activePin.value.id;
        console.log('id: ' + pinId.value);
    } else {
        console.error('Pin not found with ID:', id);
    }
}

// Watch for changes in pinTitle, pinDescription, and pinUrl and update the selectedPin accordingly
watch(pinTitle, (newValue) => {
    activePin.value = pin.value
    updateRegionText('title', newValue);
});

watch(pinDescription, (newValue) => {
    updateRegionText('description', newValue);
});

watch(pinUrl, (newValue) => {
    updateRegionText('url', newValue);
});

watch(pinBrandName, (newValue) => {
    updateRegionText('brandname', newValue);
});

function updateRegionText(tag, newValue) {

    const selectedPin = pins.value.find(pin => pin.id === pinId.value);


    if (selectedPin) {
        const regionIndex = selectedPin.editable_regions.findIndex(region => region.tag === tag);

        if (regionIndex !== -1) {
            const region = selectedPin.editable_regions[regionIndex];


            selectedPin.editable_regions[regionIndex] = {
                ...region,
                text: newValue,
            };

            // console.log('Updated Region:', selectedPin.editable_regions[regionIndex]);
            
            pins.value = pins.value;
            pin.value = selectedPin;

            
            const data = {
                name: selectedPin.name,
                template_id: selectedPin.template_id,
                editable_regions: selectedPin.editable_regions,
            }


            // updatePin(pinId.value, data);
        } else {
            console.error('No matching region found for tag:', tag);
        }
    } else {
        console.error('Selected pin not found with ID:', pinId);
    }
}






const formattedText = (text) => text.replace(/\n/g, '<br>');

const circleStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: region.width ? `${region.width}px` : '',
    height: region.height ? `${region.height}px` : '',
    backgroundColor: region.fill || '',
    border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none',
    borderRadius: '50%',
    backgroundImage: `url(${region.patternSrc || ''})`,
    backgroundSize: 'cover'
});

const rectStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: `${region.width}px`,
    height: region.height ? `${region.height}px` : '',
    backgroundColor: region.fill || '',
    border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none',
    backgroundImage: `url(${region.patternSrc || ''})`,
    backgroundSize: 'cover'
});

const textStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    fontSize: `${region.fontSize}px`,
    fontFamily: region.fontFamily,
    color: region.fill,
    fontWeight: region.fontWeight || 'normal',
    textStroke: region.stroke && region.strokeWidth ? `${region.strokeWidth}px ${region.stroke}` : '',
    WebkitTextStroke: region.stroke && region.strokeWidth ? `${region.strokeWidth}px ${region.stroke}` : '',
    textDecoration: region.underline ? 'underline' : ''
});

const ellipseStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: `${region.width}px`,
    height: region.height ? `${region.height}px` : '',
    backgroundColor: region.fill || '',
    border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none',
    borderRadius: `50% / ${(region.ry / region.rx) * 100}%`,
    backgroundImage: `url(${region.patternSrc || ''})`,
    backgroundSize: 'cover'
});

const triangleStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: 0,
    height: 0,
    backgroundColor: region.fill || '',
    borderLeft: `${region.width / 2}px solid transparent`,
    borderRight: `${region.width / 2}px solid transparent`,
    borderBottom: `${region.height}px solid ${region.fill}`,
    border: region.stroke && region.strokeWidth ? `${region.strokeWidth}px solid ${region.stroke}` : 'none'
});

const lineStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: `${region.width}px`,
    height: `${region.strokeWidth}px`,
    backgroundColor: region.fill || ''
});

const imageStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: `${region.width}px`,
    height: region.height ? `${region.height}px` : '',
    backgroundImage: `url(${region.src})`,
    backgroundSize: 'cover'
});

const videoStyle = (region) => ({
    position: 'absolute',
    left: `${region.left}px`,
    top: `${region.top}px`,
    width: `${region.width}px`,
    height: region.height ? `${region.height}px` : ''
});

</script>
<template>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full  border-r  sm:translate-x-0 bg-slate-400 border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-slate-400">
            <ul class="space-y-2 font-medium">

                <li>
                    <a href="#" class="flex items-center p-2  rounded-lg text-white hover:bg-gray-700 group">
                        <svg class="w-5 h-5  transition duration-75 text-gray-800 group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

            </ul>

            <!-- <form action="{{ route('pins.generateRandom') }}" method="POST">
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
            </form> -->
        </div>
    </aside>
    <div class="sm:ml-64 mt-10 p-3 md:p-10">

        <div class="flex flex-wrap gap-4 ">
            <template class="" v-for="pin in pins" :key="pin.id">
                <div class="bg-gray-500 px-10  relative overflow-hidden rounded-lg"
                    :style="{ height: `${pin.height}px`, width: `${pin.width}px` }">
                    <template v-for="region in pin.editable_regions" :key="region.id">
                        <!-- Circle -->
                        <div v-if="region.type === 'circle'" :style="circleStyle(region)">
                        </div>

                        <!-- Rectangle -->
                        <div v-else-if="region.type === 'rect'" :style="rectStyle(region)">
                        </div>

                        <!-- Text -->
                        <div v-else-if="region.type === 'i-text'" :style="textStyle(region)">
                            <span v-html="formattedText(region.text)"></span>
                        </div>

                        <!-- Ellipse -->
                        <div v-else-if="region.type === 'ellipse'" :style="ellipseStyle(region)">
                        </div>

                        <!-- Triangle -->
                        <div v-else-if="region.type === 'triangle'" :style="triangleStyle(region)">
                        </div>

                        <!-- Line -->
                        <div v-else-if="region.type === 'line'" :style="lineStyle(region)">
                        </div>

                        <!-- Image -->
                        <div v-else-if="region.type === 'image'" :style="imageStyle(region)">
                        </div>

                        <!-- Video -->
                        <video v-else-if="region.type === 'video'" :style="videoStyle(region)" controls>
                            <source :src="region.src" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </template>
                    <div class="z-10 bg-slate-100 shadow w-full absolute left-0 bottom-5 px-2 py-3 space-x-2">
                        <button @click="deletePin(pin.id)">delete </button>
                        <a :href="pin.id + '/edit'" target="_blank">Edit</a>
                        <fwb-button @click="showModal(pin.id)">
                            edit
                        </fwb-button>
                    </div>


                </div>
            </template>

            <div @click="createNewPin(templates)"
                class="cursor-pointer relative border w-1/4  rounded-lg overflow-hidden shadow-lg bg-white hover:bg-slate-50 h-72 flex justify-center items-center">
                <span>
                    <i class='bx bx-plus-circle text-[6rem] text-slate-400'></i>
                </span>
            </div>


        </div>





        <fwb-modal size="4xl" v-if="isShowModal" @close="closeModal">
            <template #header>
                <div class="flex items-center text-lg">
                    Terms of Service
                </div>
            </template>
            <template #body>
                <div class="grid grid-cols-2 gap-4 overflow-auto">
                    <div class="">
                        <form class="w-full mx-auto bg-white rounded-lg  ">
                            <template v-for="region in pin.editable_regions" :key="region.id">
                                <div class="mb-5" v-if="region.tag === 'title'">
                                    <label for="pinTitle"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Pin Title</label>
                                    <input type="text" v-model="pinTitle" id="pinTitle"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="pin title" required />
                                </div>
                                <div class="mb-5" v-if="region.tag === 'brandname'">
                                    <label for="pinBrandName"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Brand Name</label>
                                    <input type="text" v-model="pinBrandName" id="pinBrandName"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="pin title" required />
                                </div>

                                <div class="mb-5" v-if="region.tag === 'description'">
                                    <label for="pinDescription"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Description</label>
                                    <textarea v-model="pinDescription" id="pinDescription"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                        placeholder="Write your thoughts here..."></textarea>

                                </div>

                                <div class="mb-5" v-if="region.tag === 'url'">
                                    <label for="pinUrl" class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Url</label>
                                    <input type="text" v-model="pinUrl" id="pinUrl"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required />
                                </div>
                                <div class="mb-5" v-if="region.tag === 'shorturl'">
                                    <label for="pinShorturl"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Short Url</label>
                                    <input type="text" v-model="pinShorturl" id="pinShorturl"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required />
                                </div>
                                <div class="mb-5" v-if="region.tag === 'date'">
                                    <label for="pinDate" class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Date</label>
                                    <input type="text" v-model="pinDate" id="pinDate"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required />
                                </div>
                                <div class="mb-5" v-if="region.tag === 'custom'">
                                    <label for="pinCustomText"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Custom</label>
                                    <input type="text" v-model="pinCustomText" id="pinCustomText"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required />
                                </div>
                            </template>

                            <div>
                                <button type="submit"
                                    class="bg-[#25a0db] hover:bg-cyan-700 focus:bg-[#25a0db] text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  sm:w-[80%] px-5 py-2.5 text-center block  mx-auto btn-default">Submit</button>
                            </div>
                        </form>

                    </div>

                    <!--  -->
                    <div class="  flex items-center justify-center relative">
                        <!-- <template class="" v-for="pin in pins" :key="pin.id"> -->
                        <div class="bg-gray-500 px-10  relative overflow-hidden rounded-lg"
                            :style="{ height: `${pin.height}px`, width: `${pin.width}px` }">
                            <template v-for="region in pin.editable_regions" :key="region.id">
                                <!-- Circle -->
                                <div v-if="region.type === 'circle'" :style="circleStyle(region)">
                                </div>

                                <!-- Rectangle -->
                                <div v-else-if="region.type === 'rect'" :style="rectStyle(region)">
                                </div>

                                <!-- Text -->
                                <div v-else-if="region.type === 'i-text'" :style="textStyle(region)">
                                    <span v-html="formattedText(region.text)"></span>
                                </div>

                                <!-- Ellipse -->
                                <div v-else-if="region.type === 'ellipse'" :style="ellipseStyle(region)">
                                </div>

                                <!-- Triangle -->
                                <div v-else-if="region.type === 'triangle'" :style="triangleStyle(region)">
                                </div>

                                <!-- Line -->
                                <div v-else-if="region.type === 'line'" :style="lineStyle(region)">
                                </div>

                                <!-- Image -->
                                <div v-else-if="region.type === 'image'" :style="imageStyle(region)">
                                </div>

                                <!-- Video -->
                                <video v-else-if="region.type === 'video'" :style="videoStyle(region)" controls>
                                    <source :src="region.src" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </template>

                        </div>
                        <!-- </template> -->
                    </div>

                </div>
            </template>
            <template #footer>
                <div class="flex justify-between">
                    <fwb-button @click="closeModal" color="alternative">
                        Decline
                    </fwb-button>
                    <fwb-button @click="closeModal" color="green">
                        I accept
                    </fwb-button>
                </div>
            </template>
        </fwb-modal>


    </div>
</template>






<style scoped></style>