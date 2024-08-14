import { computed, onUnmounted, onMounted, ref, watchEffect } from "vue";
import { http } from "../utils/request";

export function usePin() {
    const pin = ref([]);
    const pins = ref([]);

    // const getPins = async () => pins.value = (await http.get('pins')).data;

    const getPins = async () => {
        try {
            const response = await http.get('/pins');
            pins.value = Object.values(response.data);
        } catch (error) {
            console.error('Failed to fetch pins:', error);
        }
    }

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

    const createNewPin = async (templates) => {
        const templateIds = extractTemplateIds(templates);

        const selectedTemplateId = getRandomTemplateId(templateIds);

        const data = {
            template_ids: [selectedTemplateId],
            pin_count: 1
        };

        try {
            const response = await http.post('/pins/generate-random', data);
            let newPin;

            if (Array.isArray(response.data)) {
                if (response.data.length > 0) {
                    newPin = response.data[0];
                }
            } else if (typeof response.data === 'object') {
                newPin = response.data;
            }

            if (newPin && newPin.id) {
                pins.value.push(newPin);
            }
        } catch (error) {
            console.error('Failed to create new pin:', error);
        }
    }
    const getPin = async (pinId) => {
        try {

            pin.value = (await http.get(`/pins/${pinId}`)).data;

        } catch (error) {
            console.error('Failed get pin:', error);
        }
    }

    const updatePin = async (pinId, data) => {

        console.log('the id ', pinId );
        const response = await http.put(`/pins/${pinId}`, data);

    }
    const deletePin = async (pinId) => {
        try {
            // Send DELETE request to the backend
            await http.delete(`/pins/${pinId}`);

            // Update the local pins array to remove the deleted pin
            pins.value = pins.value.filter(pin => pin.id !== pinId);

            console.log('Pin deleted successfully');
        } catch (error) {
            console.error('Failed to delete pin:', error);
        }
    }




    return {
        pin,
        pins,
        getPin,
        getPins,
        createNewPin,
        updatePin,
        deletePin,
    }
}


