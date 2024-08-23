import { computed, onUnmounted, onMounted, ref, watchEffect } from "vue";
import { http } from "../utils/request";

export function useTemplate() {
    const templates = ref([]);
    const allTemplates = ref([]);


    // const getPins = async () => pins.value = (await http.get('pins')).data;

    // const getTemplates = async () => {
    //     try {
    //         const response = await http.get('/templates');
    //         templates.value = Object.values(response.data); 
    //     } catch (error) {
    //         console.error('Failed to fetch pins:', error);
    //     }
    // }

    const getTemplates = async () => {
        try {
            const response = await http.get('/templates');
            allTemplates.value = Object.values(response.data); // Store all templates
            templates.value = allTemplates.value; // Initialize templates
        } catch (error) {
            console.error('Failed to fetch templates:', error);
        }
    };

    const sortTemplates = (type) => {
        if (!templates.value.length) return;

        templates.value.sort((a, b) => {
            // Compare types and sort
            return (a.type === type ? -1 : (b.type === type ? 1 : 0));
        });
    };

    const filterTemplatesByType = (type) => {
        if (!allTemplates.value.length) return;

        const filteredTemplates = allTemplates.value.filter(template => template.type === type);

        templates.value = filteredTemplates;
    };

    const showAllTemplates = () => {
        templates.value = [...allTemplates.value];
    };


    return {
        templates,
        showAllTemplates,
        getTemplates,
        sortTemplates,
        filterTemplatesByType,
    }
}