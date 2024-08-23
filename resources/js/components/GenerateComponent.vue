<script setup>
import { ref, onMounted, watch, watchEffect } from "vue";
import { FwbButton, FwbModal } from 'flowbite-vue'
import { usePin } from './../composables/usePin'
import { useTemplate } from './../composables/useTemplate'
import { useStyle } from './../composables/useStyle'

const activePin = ref(null);

const pinId = ref(null);

const pinImage = ref([])
const pinTitle = ref('')
const pinDescription = ref('');
const pinUrl = ref('');
const pinBrandName = ref('');
const pinCustomText = ref('');
const pinDate = ref('');
const pinShorturl = ref('');
const savingData = ref(false);

const NumberOfPinsToBeGenerate = ref(2);
const selectedTemplates = ref([]);



let debounceTimeout = ref(null); // To store the timeout ID
let isUpdating = false;



const {
    pin,
    pins,
    getPin,
    getPins,
    createNewPin,
    generatePins,
    updatePin,
    deletePin,
    deleteAllPinsCreatedToday,
} = usePin()

const {
    templates,
    showAllTemplates,
    getTemplates,
    sortTemplates,
    filterTemplatesByType,
} = useTemplate()

const {
    videoStyle,
    imageStyle,
    lineStyle,
    triangleStyle,
    circleStyle,
    rectStyle,
    ellipseStyle,
    textStyle,
    formattedText,
} = useStyle()



onMounted(() => {
    if (localStorage.getItem('access_token')) {
        getPins();
        getTemplates();
    } else {
        console.error('No access token found.');
    }
});



const isShowModal = ref(false)
const isshowTemplatesModal = ref(false)

function closeTemlateModal() {
    isshowTemplatesModal.value = false;
}
function showTemplateModal() {
    isshowTemplatesModal.value = true
}

function closeModal() {
    isShowModal.value = false;
    pinId.value = null;
}
function showModal(id) {
    isShowModal.value = true
    pinId.value = id;



    const selectedPin = pins.value.find(pin => pin.id === id);

    pinImage.value = [];
    activePin.value = selectedPin;
    // console.log(activePin.value);
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

            if (region['type'] === 'image') {
                pinImage.value.push(region.src);
            }
        });


        pinId.value = selectedPin.id;
    } else {
        console.error('Pin not found with ID:', pinId);
    }

}






const onImageChange = (event, index) => {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = (e) => {
        activePin.value.editable_regions[index].src = e.target.result;
        updateRegionText('image', e.target.result);
    };

    if (file) {
        reader.readAsDataURL(file);
    }
};




const removeImage = (index) => {
    activePin.value.editable_regions[index].src = ''; // Or handle removal as needed
};

// ------------------------------------
// ------------------------------------
// ------------------------------------
// ------------------------------------


const getFullImageUrl = (templatePath) => {
    const homeUrl = window.location.origin;

    const encodedPath = encodeURIComponent(templatePath);

    const fullUrl = `${homeUrl}/storage/${encodedPath}`;

    return fullUrl;
};



const getRandomTemplates = (templates) => {
    const shuffleArray = (array) => {
        let currentIndex = array.length, randomIndex;
        while (currentIndex !== 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;
            [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
        }
        return array;
    };

    const shuffledTemplates = shuffleArray([...templates]);



    const numberOfTemp = NumberOfPinsToBeGenerate.value;
    const validNumberOfTemp = Math.max(3, Math.min(numberOfTemp, shuffledTemplates.length));


    const selectedTemplates = shuffledTemplates
        .slice(0, validNumberOfTemp)
        .map(template => template.id);


    return selectedTemplates;
}



const generatePinsByNumber = () => {


    if ([...selectedTemplates.value].length == 0) {
        const selectedTemp = getRandomTemplates(templates.value);
        console.log(selectedTemp);
        generatePins(selectedTemp, NumberOfPinsToBeGenerate.value);
    } else {
        const selectedTemp = [...selectedTemplates.value]
        console.log(selectedTemp);
        generatePins(selectedTemp, NumberOfPinsToBeGenerate.value);
    }

}



const selectTemplate = (tempId) => {


    if (!selectedTemplates.value.includes(tempId)) {
        selectedTemplates.value.push(tempId);
    } else {
        selectedTemplates.value = selectedTemplates.value.filter((id) => id !== tempId);
    }

    console.log('Updated selectedTemplates:', [...selectedTemplates.value]);
};

const getTemplateClass = (tempId) => {
    return selectedTemplates.value.includes(tempId) ? 'border-2 border-red-500' : '';
};


function debounceUpdatePin(pinId, data, delay) {
    // Clear any existing timeout to reset the debounce timer
    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }

    debounceTimeout = setTimeout(() => {
        if (!isUpdating) { // Check if an update is already in progress
            try {
                isUpdating = true;
                savingData.value = true; // Indicate that saving is in progress
                updatePin(pinId, data); // Perform the update
                console.log('Pin updated successfully');
            } catch (error) {
                console.error('Error updating pin:', error);
            } finally {
                savingData.value = false; // Indicate that saving is complete
                isUpdating = false; // Reset the update flag
            }
        }
    }, delay); // Delay for debouncing (e.g., 6000 milliseconds)
}



// ------------------------------------
// ------------------------------------
// ------------------------------------
// ------------------------------------


// Watch for changes in pinTitle, pinDescription, and pinUrl and update the selectedPin accordingly
watch(pinTitle, (newValue) => {
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
watch(pinShorturl, (newValue) => {
    updateRegionText('shorturl', newValue);
});
watch(pinCustomText, (newValue) => {
    updateRegionText('custom', newValue);
});
watch(pinDate, (newValue) => {
    updateRegionText('date', newValue);
});
// watch(pinImage, (newValue) => {
//     updateRegionText('image', newValue);
// });

function updateRegionText(tag, newValue) {

    const selectedPin = pins.value.find(pin => pin.id === pinId.value);
    console.log('get here');


    if (selectedPin) {
        const regionIndex = selectedPin.editable_regions.findIndex(region => region.tag === tag);

        if (regionIndex !== -1) {
            const region = selectedPin.editable_regions[regionIndex];


            selectedPin.editable_regions[regionIndex] = {
                ...region,
                text: newValue,
            };


            pins.value = pins.value;
            pin.value = selectedPin;


            const data = {
                name: selectedPin.name,
                template_id: selectedPin.template_id,
                editable_regions: selectedPin.editable_regions,
            }

            debounceUpdatePin(pinId.value, data, 2000);


        } else {
            console.error('No matching region found for tag:', tag);
        }
    } else {
        console.error('Selected pin not found with ID:', pinId);
    }
}








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
                        <span class="ms-3 " v-if="savingData">saving</span>
                    </a>
                </li>

            </ul>

            <form action="" class="">
                <p class="whitespace-nowrap text-sm">Number of pins to be generated</p>
                <div>
                    <input type="text" v-model="NumberOfPinsToBeGenerate" id="NumberOfPinsToBeGenerate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                        placeholder="pin title" required />
                    <button class="btn-primary" type="button" @click="generatePinsByNumber">Generate Pin</button>
                </div>

                <button @click="showTemplateModal" type="button" class="">
                    select Temlate
                </button>

            </form>

        </div>
    </aside>
    <div class="sm:ml-64 mt-10 p-3 md:py-10 md:px-3">

        <div>
            <button @click="deleteAllPinsCreatedToday">clear all</button>
        </div>

        <div class="flex flex-wrap gap-4 ">
            <template class="" v-for="pin in pins" :key="pin.id">
                <div class="group bg-gray-100  relative overflow-hidden rounded-lg border-2 border-slate-300"
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
                    <div
                        class="  z-10 w-full absolute left-0 top-0 px-2 py-3 space-x-2 bg-transparent text-transparent group-hover:bg-slate-100 hover:text-red-500 group-hover:shadow delay-150 transition-all -translate-y-full group-hover:translate-y-full duration-700 ease-out">
                        <button @click="deletePin(pin.id)" class="group-hover:text-red-500">delete</button>
                        <button @click="showModal(pin.id)" class="group-hover:text-red-500">
                            edit
                        </button>
                    </div>


                </div>
            </template>

            <div @click="createNewPin(templates)"
                class="  cursor-pointer relative border w-1/4  rounded-lg overflow-hidden shadow-lg bg-white hover:bg-slate-50 h-72 flex justify-center items-center">
                <span>
                    <i class='bx bx-plus-circle text-[6rem] text-slate-400'></i>
                </span>
            </div>


        </div>

        <fwb-modal size="6xl" v-if="isshowTemplatesModal" @close="closeTemlateModal">
            <template #header>
                <div class="flex items-center text-lg">
                    Select Templates
                </div>
            </template>

            <template #body>
                <div>
                    <div class="grid md:grid-cols-6  overflow-auto">
                        <div class="md:col-span-1">
                            <ul
                                class="flex-column space-y space-y-4 text-sm font-medium text-gray-500  md:me-4 mb-4 md:mb-0  w-full overflow-y-auto">

                                <li>
                                    <button
                                        class="inline-flex text-sm whitespace-nowrap items-center px-4 py-3 text-white bg-gray-700 rounded-lg active w-full"
                                        @click="filterTemplatesByType('blocks')">Blocks</button>
                                </li>
                                <li>
                                    <button
                                        class="inline-flex text-sm whitespace-nowrap items-center px-4 py-3 text-white bg-gray-700 rounded-lg active w-full"
                                        @click="filterTemplatesByType('interior')">Interior</button>
                                </li>
                                <li>
                                    <button
                                        class="inline-flex text-sm whitespace-nowrap items-center px-4 py-3 text-white bg-gray-700 rounded-lg active w-full"
                                        @click="filterTemplatesByType('multiimages')">Multiimages</button>
                                </li>
                                <li>
                                    <button
                                        class="inline-flex text-sm whitespace-nowrap items-center px-4 py-3 text-white bg-gray-700 rounded-lg active w-full"
                                        @click="filterTemplatesByType('basics')">Basics</button>
                                </li>
                                <!-- Add more buttons as needed -->
                                <li>
                                    <button
                                        class="inline-flex text-sm whitespace-nowrap items-center px-4 py-3 text-white bg-gray-700 rounded-lg active w-full"
                                        @click="showAllTemplates">Show All Templates</button>
                                </li>
                            </ul>
                        </div>

                        <div
                            class="md:col-span-5 bg-gray-100 grid sm:grid-cols-2  md:grid-cols-4 lg:grid-cols-4 gap-2 space-y-3 p-5 overflow-auto">
                            <div v-for="template in templates" :class="getTemplateClass(template.id)"
                                class="shadow p-2 rounded-lg bg-white cursor-pointer" :key="template.id"
                                @click="selectTemplate(template.id)">
                                <img :src="getFullImageUrl(template.path)" :alt="template.name">
                            </div>

                        </div>

                    </div>
                </div>
            </template>


            <template #footer>
                <div class="flex justify-between">
                    <fwb-button @click="closeTemlateModal" color="alternative">
                        Decline
                    </fwb-button>
                    <fwb-button @click="closeTemlateModal" color="green">
                        I accept
                    </fwb-button>
                </div>
            </template>

        </fwb-modal>




        <!-- ----------------- -->
        <fwb-modal size="4xl" v-if="isShowModal" @close="closeModal">
            <template #header>
                <div class="flex items-center text-lg">
                    Edit Pin
                </div>
            </template>
            <template #body>
                <div class="grid grid-cols-2 gap-4 overflow-auto">
                    <div class="">
                        <form class="w-full mx-auto bg-white rounded-lg  ">
                            <template v-for="region in activePin.editable_regions" :key="region.id">
                                <div class="mb-5" v-if="region.tag === 'title'">
                                    <label for="pinTitle"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Pin Title</label>
                                    <textarea type="text" v-model="pinTitle" id="pinTitle"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="pin title" required></textarea>
                                </div>
                                <div class="mb-5" v-if="region.tag === 'brandname'">
                                    <label for="pinBrandName"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Brand Name</label>
                                    <textarea type="text" v-model="pinBrandName" id="pinBrandName"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="pin title" required></textarea>
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
                                    <textarea type="text" v-model="pinUrl" id="pinUrl"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required></textarea>
                                </div>
                                <div class="mb-5" v-if="region.tag === 'shorturl'">
                                    <label for="pinShorturl"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Short Url</label>
                                    <textarea type="text" v-model="pinShorturl" id="pinShorturl"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required></textarea>
                                </div>
                                <div class="mb-5" v-if="region.tag === 'date'">
                                    <label for="pinDate" class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Date</label>
                                    <textarea type="text" v-model="pinDate" id="pinDate"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required></textarea>
                                </div>
                                <div class="mb-5" v-if="region.tag === 'custom'">
                                    <label for="pinCustomText"
                                        class="block mb-2 text-sm font-medium text-gray-900 uppercase">
                                        Custom</label>
                                    <textarea type="text" v-model="pinCustomText" id="pinCustomText"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 resize-none  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  "
                                        placeholder="Enter your name" required></textarea>
                                </div>

                            </template>
                            <div v-for="(region, index) in activePin.editable_regions" :key="region.id"
                                class="mb-5 flex">
                                <div v-if="region.type === 'image'" class="w-full flex items-center  p-2 rounded-lg">
                                    <img :src="region.src" alt="Pin Image" class="w-20 rounded-lg mr-4" />
                                    <div>
                                        <input type="file" @change="onImageChange($event, index)"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                            accept="image/*" />
                                        <button @click="removeImage(index)" type="button"
                                            class="mt-2 text-white bg-red-600 hover:bg-red-700 rounded-lg px-3 py-1">
                                            Remove Image
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="submit" @click="updatePin(activePin.id)"
                                    class="bg-[#25a0db] hover:bg-cyan-700 focus:bg-[#25a0db] text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  sm:w-[80%] px-5 py-2.5 text-center block  mx-auto btn-default">Submit</button>
                            </div>
                        </form>

                    </div>

                    <!--  -->
                    <div class="  flex items-center justify-center relative">
                        <!-- {{ activePin }} -->
                        <!-- <template class="" v-for="pin in pins" :key="pin.id"> -->
                        <div class="bg-gray-500 px-10  relative overflow-hidden rounded-lg"
                            :style="{ height: `${activePin.height}px`, width: `${activePin.width}px` }">
                            <template v-for="region in activePin.editable_regions" :key="region.id">
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