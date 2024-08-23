import { computed, onUnmounted, onMounted, ref, watchEffect } from "vue";
import { http } from "../utils/request";
import * as fabric from 'fabric';

// export * from './fabric';

// Assume you have initialized a Fabric.js canvas somewhere in your code

export function usePin() {
    const pin = ref([]);
    const pins = ref([]);
    console.log(fabric); 

    const canvas = new fabric.Canvas('myCanvas');

    // const getPins = async () => {
    //     try {
    //         const response = await http.get('/pins');
    //         pins.value = Object.values(response.data);
    //     } catch (error) {
    //         console.error('Failed to fetch pins:', error);
    //     }
    // }


    const getPins = async () => {
        try {
            // Fetch all pins from the API
            const response = await http.get('/pins');
            const allPins = Object.values(response.data);

            // Get today's date
            const today = new Date().toISOString().split('T')[0];

            // Filter pins created today
            pins.value = allPins.filter(pin => {
                const pinDate = new Date(pin.created_at).toISOString().split('T')[0];
                return pinDate === today;
            });


        } catch (error) {
            console.error('Failed to fetch pins:', error);
        }
    };


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


    const generatePins = async (templates, numberOfPins) => {
        // const templateIds = extractTemplateIds(templates);
        console.log();

        const data = {
            template_ids: templates,
            pin_count: numberOfPins || 1
        };

        try {
            const response = await http.post('/pins/generate-random', data);

            if (Array.isArray(response.data) && response.data.length > 0) {
                response.data.forEach(newPin => {
                    if (newPin && newPin.id) {
                        pins.value.push(newPin);
                    }
                });
            } else if (typeof response.data === 'object' && response.data.id) {
                pins.value.push(response.data);
            }

        } catch (error) {
            console.error('Failed to create new pins:', error);
        }
    };



    const getPin = async (pinId) => {
        try {

            pin.value = (await http.get(`/pins/${pinId}`)).data;


        } catch (error) {
            console.error('Failed get pin:', error);
        }
    }

    const updatePin = async (pinId, data) => {


        loadEditableRegionsToCanvas(data.editable_regions);

        // Wait for all images to load and render on the canvas
        const canvasImage = canvas.toDataURL('image/png');

        // Add the image path to the data object
        data.path = canvasImage;

        const response = await http.put(`/pins/${pinId}`, data);

    }
    const deletePin = async (pinId) => {
        try {
            await http.delete(`/pins/${pinId}`);

            pins.value = pins.value.filter(pin => pin.id !== pinId);

            console.log('Pin deleted successfully');
        } catch (error) {
            console.error('Failed to delete pin:', error);
        }
    }



    const deleteAllPinsCreatedToday = async () => {
        try {
            const response = await http.get('/pins');
            const allPins = Object.values(response.data);

            const today = new Date().toISOString().split('T')[0];

            const pinsToDelete = allPins.filter(pin => {
                const pinDate = new Date(pin.created_at).toISOString().split('T')[0];
                return pinDate === today;
            });

            for (const pin of pinsToDelete) {
                await http.delete(`/pins/${pin.id}`);
            }

            pins.value = pins.value.filter(pin => !pinsToDelete.some(p => p.id === pin.id));

            console.log('All pins created today have been deleted successfully');

        } catch (error) {
            console.error('Failed to delete pins:', error);
        }
    };

    const loadEditableRegionsToCanvas = (editableRegions) => {
        canvas.clear();

        editableRegions.forEach(region => {
            if (region.type === 'image') {
                const testUrl = "https://via.placeholder.com/150";
                console.log("Loading test image with URL:", testUrl);
                
                fabric.Image.fromURL(testUrl, (img) => {
                    console.log("Test image load callback triggered.");
                    // Continue with setting the properties and adding to canvas...
                }, { crossOrigin: 'Anonymous' });

            } else if (region.type === 'i-text') {
                console.log("Adding text:", region.text);
                const text = new fabric.Text(region.text, {
                    left: region.left,
                    top: region.top,
                    fontSize: region.fontSize,
                    fontFamily: region.fontFamily,
                    fontWeight: region.fontWeight,
                    fill: region.fill,
                    opacity: region.opacity,
                    textAlign: region.textAlign,
                    underline: region.underline,
                    stroke: region.stroke,
                    strokeWidth: region.strokeWidth,
                });
                canvas.add(text);
            } else if (['rect', 'circle', 'triangle', 'line', 'ellipse', 'star'].includes(region.type)) {
                console.log("Adding shape:", region.type);
                const shapeOptions = {
                    left: region.left,
                    top: region.top,
                    width: region.width,
                    height: region.height,
                    fill: region.fill,
                    stroke: region.stroke,
                    strokeWidth: region.strokeWidth,
                    rx: region.rx || 0,
                    ry: region.ry || 0
                };

                let shape;
                if (region.type === 'rect') {
                    shape = new fabric.Rect(shapeOptions);
                } else if (region.type === 'circle') {
                    shape = new fabric.Circle({ ...shapeOptions, radius: region.width / 2 });
                } else if (region.type === 'triangle') {
                    shape = new fabric.Triangle(shapeOptions);
                } else if (region.type === 'line') {
                    shape = new fabric.Line([region.left, region.top, region.left + region.width, region.top + region.height], shapeOptions);
                } else if (region.type === 'ellipse') {
                    shape = new fabric.Ellipse(shapeOptions);
                }
                canvas.add(shape);
            }
        });

        canvas.renderAll();
    };





    return {
        pin,
        pins,
        getPin,
        getPins,
        createNewPin,
        generatePins,
        updatePin,
        deletePin,
        deleteAllPinsCreatedToday,
    }
}


