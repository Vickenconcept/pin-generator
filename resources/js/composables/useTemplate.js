import { computed, onUnmounted, onMounted, ref, watchEffect } from "vue";
import { http } from "../utils/request";

export function useTemplate() {
    const templates = ref([]);


    // const getPins = async () => pins.value = (await http.get('pins')).data;

    const getTemplates = async () => {
        try {
            const response = await http.get('/templates');
            templates.value = Object.values(response.data); 
        } catch (error) {
            console.error('Failed to fetch pins:', error);
        }
    }


    return {
        templates,
        getTemplates,
    }
}