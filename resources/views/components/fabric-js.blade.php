<script>
    function showUniformDisplayMenu() {
        document.getElementById('uniformDisplayMenu').style.display = 'block';
    }

    function hideUniformDisplayMenu() {
        document.getElementById('uniformDisplayMenu').style.display = 'none';
    }


    function setCanvasSize() {
        var width = parseInt(document.getElementById('canvasWidth').value, 10);
        var height = parseInt(document.getElementById('canvasHeight').value, 10);

        if (!isNaN(width) && !isNaN(height) && width > 0 && height > 0) {
            canvas.setWidth(width);
            canvas.setHeight(height);
            canvas.renderAll();
        }
    }


    // Attach event listeners to input fields
    var canvasWidthInput = document.getElementById('canvasWidth');
    var canvasHeightInput = document.getElementById('canvasHeight');

    if (canvasWidthInput) {
        canvasWidthInput.addEventListener('input', setCanvasSize);
    }

    if (canvasHeightInput) {
        canvasHeightInput.addEventListener('input', setCanvasSize);
    }

    // ---------------------------------
    // ---------------------------------

    var canvas = new fabric.Canvas('canvas');
    canvas.setBackgroundColor('white', canvas.renderAll.bind(canvas));
    canvas.isDrawingMode = false;
    var activeTextObject = null;

    const bgImageInput = document.getElementById('bgImageInput');
    if (bgImageInput) {
        bgImageInput.addEventListener('change', handleBgImageInput);
    }

    function handleBgImageInput(e) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var imgObj = new Image();
            imgObj.src = event.target.result;
            imgObj.onload = function() {
                var img = new fabric.Image(imgObj);
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: canvas.width / img.width,
                    scaleY: canvas.height / img.height
                });
            }
        }
        reader.readAsDataURL(e.target.files[0]);
    };


    // Function to deselect all objects on canvas
    function deselectAllObjects() {
        canvas.discardActiveObject();
        canvas.renderAll();;
    }

    // Function to handle document click events
    function handleDocumentClick(event) {
        var parentDiv = document.getElementById('parent_div');
        var canvasElement = canvas.getElement();
        var parentRect = parentDiv.getBoundingClientRect();
        var canvasRect = canvasElement.getBoundingClientRect();

        // Check if the click occurred inside the parent_div but outside the canvas
        if (event.clientX >= parentRect.left && event.clientX <= parentRect.right &&
            event.clientY >= parentRect.top && event.clientY <= parentRect.bottom) {
            // Check if the click occurred inside the canvas
            if (!(event.clientX >= canvasRect.left && event.clientX <= canvasRect.right &&
                    event.clientY >= canvasRect.top && event.clientY <= canvasRect.bottom)) {
                deselectAllObjects();
            }
        }
    }

    // Attach event listener to document for click events
    document.addEventListener('mousedown', handleDocumentClick);




    document.addEventListener('keydown', function(event) {
        const activeObject = canvas.getActiveObject();
        if (activeObject) {
            switch (event.key) {
                case 'ArrowUp':
                    activeObject.top -= 5; // Move up by 5 pixels
                    break;
                case 'ArrowDown':
                    activeObject.top += 5; // Move down by 5 pixels
                    break;
                case 'ArrowLeft':
                    activeObject.left -= 5; // Move left by 5 pixels
                    break;
                case 'ArrowRight':
                    activeObject.left += 5; // Move right by 5 pixels
                    break;
                default:
                    return; // Quit when this doesn't handle the key event.
            }
            activeObject.setCoords(); // Update the object's coordinates
            canvas.renderAll(); // Re-render the canvas
        }
    });




    // Add Text
    function addText() {

        var text = new fabric.IText('Your Text Here', {
            left: 50,
            top: 50,
            fill: 'black',
            tag: 'title',
            padding: 10
        });
        canvas.add(text);
        canvas.setActiveObject(text);
        // activeTextObject = text;
        text.enterEditing();
        text.hiddenTextarea.focus();
    }

    var addTextButton = document.getElementById('addText');
    if (addTextButton) {
        addTextButton.addEventListener('click', addText);
    }

    canvas.on('mouse:dblclick', function(options) {
        if (options.target && options.target.type === 'i-text') {
            options.target.enterEditing();
            options.target.hiddenTextarea.focus();
        }
    });

    // Update active text object
    // canvas.on('mouse:down', function(options) {
    //     if (options.target && options.target.type === 'i-text') {
    //         activeTextObject = options.target;
    //     } else {
    //         activeTextObject = null;
    //     }
    // });


    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    canvas.on('mouse:down', debounce(function(options) {
        if (options.target && options.target.type === 'i-text') {
            activeTextObject = options.target;
        } else {
            activeTextObject = null;
        }
    }, 300));


    function tagSelectorAction() {
        var activeObject = canvas.getActiveObject();
        var selectedTag = this.value;

        if (activeObject && activeObject.type === 'i-text' && selectedTag) {
            activeObject.set('tag', selectedTag); // Update tag property
            canvas.renderAll();
        }
    };

    var tagSelector = document.getElementById('tagSelector');
    if (tagSelector) {
        tagSelector.addEventListener('change', tagSelectorAction);
    }


    // Function to make selected text bold
    function makeTextBold() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            activeObject.set({
                fontWeight: activeObject.fontWeight === 'bold' ? 'normal' : 'bold'
            });
            canvas.renderAll();
        }
    }

    // Function to make selected text italic
    function makeTextItalic() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            activeObject.set({
                fontStyle: activeObject.fontStyle === 'italic' ? 'normal' : 'italic'
            });
            canvas.renderAll();
        }
    }

    // Function to underline selected text
    function underlineText() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            // Fabric.js does not support underline directly, so use a custom solution
            if (!activeObject.underline) {
                activeObject.underline = true;
                var textDecoration = activeObject.textDecoration || '';
                activeObject.set({
                    textDecoration: textDecoration + ' underline'
                });
            } else {
                activeObject.underline = false;
                var textDecoration = activeObject.textDecoration || '';
                activeObject.set({
                    textDecoration: textDecoration.replace(/ underline/g, '')
                });
            }
            canvas.renderAll();
        }
    }

    // Attach event listeners to buttons
    var boldButton = document.getElementById('boldText');
    var italicButton = document.getElementById('italicText');
    var underlineButton = document.getElementById('underlineText');

    if (boldButton) {
        boldButton.addEventListener('click', makeTextBold);
    }

    if (italicButton) {
        italicButton.addEventListener('click', makeTextItalic);
    }

    if (underlineButton) {
        underlineButton.addEventListener('click', underlineText);
    }
    // document.getElementById('boldText').addEventListener('click', makeTextBold);
    // document.getElementById('italicText').addEventListener('click', makeTextItalic);
    // document.getElementById('underlineText').addEventListener('click', underlineText);




    var alignmentState = 'left'; // Initial alignment state
    var caseState = 'upper'; // Initial case state

    // Function to set text alignment
    function setTextAlignment() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            switch (alignmentState) {
                case 'left':
                    activeObject.set({
                        textAlign: 'center'
                    });
                    alignmentState = 'center';
                    break;
                case 'center':
                    activeObject.set({
                        textAlign: 'right'
                    });
                    alignmentState = 'right';
                    break;
                case 'right':
                    activeObject.set({
                        textAlign: 'left'
                    });
                    alignmentState = 'left';
                    break;
            }
            canvas.renderAll();
        }
    }

    // Function to toggle text case
    function toggleTextCase() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            switch (caseState) {
                case 'upper':
                    activeObject.set({
                        text: activeObject.text.toLowerCase()
                    });
                    caseState = 'lower';
                    break;
                case 'lower':
                    activeObject.set({
                        text: toCamelCase(activeObject.text)
                    });
                    caseState = 'camel';
                    break;
                case 'camel':
                    activeObject.set({
                        text: toPascalCase(activeObject.text)
                    });
                    caseState = 'pascal';
                    break;
                case 'pascal':
                    activeObject.set({
                        text: activeObject.text.toUpperCase()
                    });
                    caseState = 'upper';
                    break;
            }
            canvas.renderAll();
        }
    }

    // Helper function to convert text to camel case
    function toCamelCase(text) {
        return text
            .toLowerCase()
            .replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
                return index === 0 ? match.toLowerCase() : match.toUpperCase();
            });
    }

    function toPascalCase(text) {
        return text
            .toLowerCase()
            .replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match) {
                return match.toUpperCase();
            });
    }

    // Attach event listeners to buttons
    var textAlignButton = document.getElementById('textAlign');
    var textCaseButton = document.getElementById('textCase');

    if (textAlignButton) {
        textAlignButton.addEventListener('click', setTextAlignment);
    }

    if (textCaseButton) {
        textCaseButton.addEventListener('click', toggleTextCase);
    }
    // document.getElementById('textAlign').addEventListener('click', setTextAlignment);
    // document.getElementById('textCase').addEventListener('click', toggleTextCase);


    // Function to update the text size input field
    function updateTextSizeInput() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            document.getElementById('textSizeInput').value = activeObject.fontSize;
        }
    }

    // Function to increase the text size
    function increaseTextSize() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            var newSize = activeObject.fontSize + 2; // Increase by 2 units
            activeObject.set({
                fontSize: newSize
            });
            updateTextSizeInput(); // Update input field
            canvas.renderAll();
        }
    }

    // Function to decrease the text size
    function decreaseTextSize() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            var newSize = activeObject.fontSize - 2; // Decrease by 2 units
            if (newSize > 0) {
                activeObject.set({
                    fontSize: newSize
                });
                updateTextSizeInput(); // Update input field
                canvas.renderAll();
            }
        }
    }

    // Function to set text size from input
    function setTextSizeFromInput() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            var newSize = parseFloat(document.getElementById('textSizeInput').value);
            if (!isNaN(newSize) && newSize > 0) {
                activeObject.set({
                    fontSize: newSize
                });
                canvas.renderAll();
            }
        }
    }

    // Attach event listeners to buttons and input field

    var increaseTextSizeButton = document.getElementById('increaseTextSize');
    var decreaseTextSizeButton = document.getElementById('decreaseTextSize');
    var textSizeInput = document.getElementById('textSizeInput');

    if (increaseTextSizeButton) {
        increaseTextSizeButton.addEventListener('click', increaseTextSize);
    }

    if (decreaseTextSizeButton) {
        decreaseTextSizeButton.addEventListener('click', decreaseTextSize);
    }

    if (textSizeInput) {
        textSizeInput.addEventListener('input', setTextSizeFromInput);
    }
    // document.getElementById('increaseTextSize').addEventListener('click', increaseTextSize);
    // document.getElementById('decreaseTextSize').addEventListener('click', decreaseTextSize);
    // document.getElementById('textSizeInput').addEventListener('input', setTextSizeFromInput);

    // Initialize input field with current text size
    canvas.on('object:modified', updateTextSizeInput);
    canvas.on('object:added', updateTextSizeInput);





    // 

    // Add Image

    var addImageButton = document.getElementById('addImage');
    var uploadImageInput = document.getElementById('uploadImage');

    if (addImageButton && uploadImageInput) {
        addImageButton.addEventListener('click', function() {
            uploadImageInput.click();
        });

        uploadImageInput.addEventListener('change', handleImageUpload);
    }



    function handleImageUpload(e) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var imgObj = new Image();
            imgObj.src = event.target.result;
            imgObj.onload = function() {
                var image = new fabric.Image(imgObj);
                let scale = 300 / image.width;
                image.set({
                    scaleX: scale,
                    scaleY: scale,
                    padding: 10
                });
                canvas.add(image);
            }
        }
        reader.readAsDataURL(e.target.files[0]);
    };




    // Add Video;

    var video1El = document.getElementById('video1');

    // Add video button click event
    var addVideoButton = document.getElementById('addVideo');

    const videoUpload = document.getElementById('videoUpload');

    if (addVideoButton && videoUpload) {
        addVideoButton.addEventListener('click', function() {
            videoUpload.click(); // Trigger the file input click
        });

        videoUpload.addEventListener('change', handleVideoUpload);
    }



    // Get the video upload input element
    // pins/upload-video
    // Handle the video upload event




    function handleVideoUpload(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('video/')) {
            const reader = new FileReader();

            // When the file is read successfully
            reader.onload = function(e) {
                const videoElement = document.createElement('video');
                videoElement.src = e.target.result;
                videoElement.autoplay = true;
                videoElement.loop = true;
                videoElement.muted = false; // Optional: mute the video if necessary
                videoElement.controls = true;
                videoElement.crossOrigin = 'anonymous'; // Add crossOrigin attribute

                // When the video metadata is loaded
                videoElement.onloadedmetadata = function() {
                    // Set video dimensions to match the actual video size
                    videoElement.width = videoElement.videoWidth;
                    videoElement.height = videoElement.videoHeight;

                    const fabricVideo = new fabric.Image(videoElement, {
                        left: 100,
                        top: 100,
                        width: videoElement.videoWidth,
                        height: videoElement.videoHeight,
                        scaleX: 1, // Ensure scale is set to 1 initially
                        scaleY: 1, // Ensure scale is set to 1 initially
                        selectable: true
                    });

                    // Add the video to the canvas
                    canvas.add(fabricVideo);

                    // Start rendering the video on the canvas
                    function renderVideo() {
                        if (fabricVideo && fabricVideo.canvas) {
                            fabricVideo.set({
                                width: videoElement.videoWidth,
                                height: videoElement.videoHeight
                            });
                            fabricVideo.setElement(videoElement);
                            canvas.renderAll();
                        }
                        requestAnimationFrame(renderVideo);
                    }

                    renderVideo();
                };

                videoElement.play();
            };

            // Read the video file
            reader.readAsDataURL(file);
        }
    };



    // ---------------- shapes -------------------
    // ---------------- shapes -------------------
    // Add Rectangle
    var addRectButton = document.getElementById('addRect');

    if (addRectButton) {
        addRectButton.addEventListener('click', addRectangle);
    }

    function addRectangle() {
        var rect = new fabric.Rect({
            left: 100,
            top: 100,
            fill: 'red',
            width: 50,
            height: 50,
            padding: 10
        });
        canvas.add(rect);
        canvas.setActiveObject(rect);
        canvas.renderAll();
    };

    // Add Circle
    document.getElementById('addCircle').addEventListener('click', function() {
        var circle = new fabric.Circle({
            left: 100,
            top: 100,
            fill: 'green',
            radius: 30,
            padding: 10
        });
        canvas.add(circle);
        canvas.setActiveObject(circle);
        canvas.renderAll();
    });

    // Add Triangle
    document.getElementById('addTriangle').addEventListener('click', function() {
        var triangle = new fabric.Triangle({
            left: 100,
            top: 100,
            fill: 'red',
            width: 60,
            height: 60,
            padding: 10
        });
        canvas.add(triangle);
        canvas.setActiveObject(triangle);
        canvas.renderAll();
    });

    // Add Ellipse
    document.getElementById('addEllipse').addEventListener('click', function() {
        var ellipse = new fabric.Ellipse({
            left: 100,
            top: 100,
            fill: 'purple',
            rx: 40,
            ry: 30,
            padding: 10
        });
        canvas.add(ellipse);
        canvas.setActiveObject(ellipse);
        canvas.renderAll();
    });

    // Function to add a line to the canvas
    document.getElementById('addLine').addEventListener('click', function() {
        var line = new fabric.Line([50, 100, 200, 200], {
            left: 100,
            top: 100,
            stroke: 'blue',
            strokeWidth: 5,
            padding: 10
        });
        canvas.add(line);
        canvas.setActiveObject(line);
        canvas.renderAll();
    });

    // Function to add an arrow to the canvas
    document.getElementById('addArrow').addEventListener('click', function() {
        // Define the line part of the arrow
        var line = new fabric.Line([50, 50, 200, 50], {
            stroke: 'black',
            strokeWidth: 5,
            selectable: false

        });

        // Define the triangle part of the arrow (the arrowhead)
        var arrowHead = new fabric.Triangle({
            left: 200,
            top: 52,
            originX: 'center',
            originY: 'center',
            angle: 90,
            width: 20,
            height: 20,
            fill: 'black',
            selectable: false
        });

        // Create a group containing the line and the arrowhead
        var arrow = new fabric.Group([line, arrowHead], {
            selectable: true,
            left: 100,
            top: 100,
            customType: 'arrow'
        });

        // Add the arrow group to the canvas
        canvas.add(arrow);
        canvas.setActiveObject(arrow);
        canvas.renderAll();
    });


    // Add Star
    document.getElementById('addStar').addEventListener('click', function() {
        // Define the vertices of the star
        var points = [{
                x: 0,
                y: -50
            }, // Top point
            {
                x: 14,
                y: -20
            }, // Right top point
            {
                x: 47,
                y: -20
            }, // Right bottom point
            {
                x: 23,
                y: 7
            }, // Bottom right point
            {
                x: 29,
                y: 40
            }, // Bottom point
            {
                x: 0,
                y: 25
            }, // Left bottom point
            {
                x: -29,
                y: 40
            }, // Left point
            {
                x: -23,
                y: 7
            }, // Bottom left point
            {
                x: -47,
                y: -20
            }, // Left top point
            {
                x: -14,
                y: -20
            } // Top left point
        ].map(function(point) {
            return new fabric.Point(point.x + 100, point.y + 100); // Offset by left and top
        });

        var star = new fabric.Polygon(points, {
            left: 100,
            top: 100,
            fill: 'yellow',
            originX: 'center',
            originY: 'center'
        });

        canvas.add(star);
        canvas.setActiveObject(star);
        canvas.renderAll();
    });


    // for shape color

    document.getElementById('shapeColor').addEventListener('input', function() {
        var activeObject = canvas.getActiveObject();
        if (activeObject) {
            if (activeObject.customType === 'arrow') {

                activeObject.forEachObject(function(obj) {
                    if (obj.type === 'line') {
                        obj.set({
                            stroke: this.value
                        });
                    } else if (obj.type === 'triangle') {
                        obj.set({
                            fill: this.value
                        });
                    }
                }.bind(this));
            } else if (activeObject.type !== 'i-text') {
                activeObject.set({
                    fill: this.value
                });
            }
            canvas.renderAll();
        }
    });

    // Function to apply image as pattern fill to the shape
    function fillShapeWithImage(shape, imageFile) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var imgElement = new Image();
            imgElement.src = event.target.result;

            imgElement.onload = function() {
                var img = new fabric.Image(imgElement);

                var scaleX = shape.width / img.width;
                var scaleY = shape.height / img.height;
                var scale = Math.max(scaleX, scaleY);

                var pattern = new fabric.Pattern({
                    source: img.getElement(),
                    repeat: 'no-repeat',
                    patternTransform: [scale, 0, 0, scale, 0, 0] // Scale the pattern
                });

                if (shape.setPatternFill) {
                    shape.setPatternFill(pattern);
                } else {
                    shape.set({
                        fill: pattern
                    });
                }

                canvas.renderAll();
            };
        };
        reader.readAsDataURL(imageFile); // Convert the file to a data URL
    }

    // Handle file input change
    document.getElementById('imageUploadToShape').addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var activeObject = canvas.getActiveObject();
            if (activeObject && (activeObject.type === 'rect' || activeObject.type === 'circle' || activeObject
                    .type === 'ellipse' || activeObject.type === 'triangle')) {
                fillShapeWithImage(activeObject, file);
            } else {
                console.log('No suitable object selected.');
            }
        }
    });

    // Double-click to trigger file input
    canvas.on('mouse:dblclick', function(e) {
        var target = e.target;
        if (target && (target.type === 'rect' || target.type === 'circle' || target.type === 'ellipse' || target
                .type === 'triangle')) {
            document.getElementById('imageUploadToShape').click(); // Trigger file input
        }
    });


    // --------------- Custom Functions




    function toggleGroup() {
        var activeObjects = canvas.getActiveObjects();
        if (activeObjects.length > 1) {
            // Group the objects
            var group = new fabric.Group(activeObjects);
            canvas.discardActiveObject();
            canvas.add(group);
            canvas.renderAll();
        } else if (activeObjects.length === 1 && activeObjects[0].type === 'group') {
            // Ungroup the objects
            var group = activeObjects[0];
            var items = group._objects;
            group._restoreObjectsState();
            canvas.remove(group);
            for (var i = 0; i < items.length; i++) {
                canvas.add(items[i]);
            }
            canvas.renderAll();
            canvas.setActiveObject(new fabric.ActiveSelection(items, {
                canvas: canvas
            }));
        }
    }

    // Attach event listener to button for click events
    document.getElementById('toggleGroupObjects').addEventListener('click', toggleGroup);

    // Attach event listener to document for keyboard events
    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.key === 'g') {
            event.preventDefault();
            toggleGroup();
        }
    });

    function selectAllObjects(canvas) {
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'a') {
                event.preventDefault(); // Prevent the default "Select All" action in the browser

                const objects = canvas.getObjects();
                if (objects.length > 0) {
                    canvas.discardActiveObject(); // Clear any previous selection
                    const activeSelection = new fabric.ActiveSelection(objects, {
                        canvas: canvas,
                    });
                    canvas.setActiveObject(activeSelection);
                    canvas.requestRenderAll();
                }
            }
        });
    }

    // Example usage
    selectAllObjects(canvas);



    // Color Picker for drawing
    document.getElementById('pencilColorPicker').addEventListener('input', function() {
        var color = this.value;
        canvas.freeDrawingBrush.color = color;
    });



    function toggleDrawingDiv() {
        var drawingDiv = document.getElementById('HiddenDrawingDiv');
        if (canvas.isDrawingMode) {
            drawingDiv.style.display = 'block';
            document.getElementById('hiddenTextControlDiv').style.display = 'none';
            document.getElementById('hiddenShapeControlDiv').style.display = 'none';
            document.getElementById('hiddenImageControlDiv').style.display = 'none';
        } else {
            drawingDiv.style.display = 'none';

        }
    }

    // Attach event listener to toggle drawing mode
    document.getElementById('enableDrawing').addEventListener('click', function() {
        canvas.isDrawingMode = !canvas.isDrawingMode;
        toggleDrawingDiv();
    });

    // Initial call to set the correct visibility state
    toggleDrawingDiv();


    document.getElementById('disableDrawing').addEventListener('click', function() {
        canvas.isDrawingMode = false;
    });


    // Edit Text Color and Font immediately
    document.getElementById('textColor').addEventListener('input', function() {
        if (activeTextObject && activeTextObject.type === 'i-text') {
            activeTextObject.set({
                fill: this.value
            });
            canvas.renderAll();
        }
    });

    document.getElementById('fontFamily').addEventListener('change', function() {
        if (activeTextObject && activeTextObject.type === 'i-text') {
            activeTextObject.set({
                fontFamily: this.value
            });
            canvas.renderAll();
        }
    });






    document.getElementById('deleteObject').addEventListener('click', function() {
        var activeObjects = canvas.getActiveObjects(); // Get all selected objects
        if (activeObjects.length > 0) {
            activeObjects.forEach(function(obj) {
                canvas.remove(obj); // Remove each selected object
            });
            canvas.discardActiveObject(); // Deselect objects
        }
    });

    // Delete objects with Delete key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Delete') {
            var activeObjects = canvas.getActiveObjects(); // Get all selected objects
            if (activeObjects.length > 0) {
                activeObjects.forEach(function(obj) {
                    canvas.remove(obj); // Remove each selected object
                });
                canvas.discardActiveObject(); // Deselect objects
            }
        }
    });

    // Clear Canvas
    document.getElementById('clearCanvas').addEventListener('click', function() {
        canvas.clear();
    });

    document.getElementById('bringToFront').addEventListener('click', function() {
        var activeObject = canvas.getActiveObject();
        if (activeObject) {
            canvas.bringToFront(activeObject);
        }
    });

    // Send selected object to back
    document.getElementById('sendToBack').addEventListener('click', function() {
        var activeObject = canvas.getActiveObject();
        if (activeObject) {
            canvas.sendToBack(activeObject);
        }
    });
    // Duplicate Selected Object
    function duplicateSelectedObject() {
        var activeObject = canvas.getActiveObject();
        if (activeObject) {
            // Clone the selected object
            activeObject.clone(function(cloned) {
                cloned.set({
                    left: activeObject.left + 10, // Offset the duplicate
                    top: activeObject.top + 10
                });
                canvas.add(cloned);
                canvas.renderAll();
            });
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.key === 'd') {
            event.preventDefault(); // Prevent the default browser action for Ctrl + G
            duplicateSelectedObject();
        }
    });

    // Add event listener for duplicate button
    document.getElementById('duplicateObject').addEventListener('click', function() {
        duplicateSelectedObject();
    });


    // 
    // var undoStack = [];
    // var redoStack = [];

    // Save the current state of the canvas
    // function saveState() {
    //     undoStack.push(JSON.stringify(canvas));
    //     redoStack.length = 0; // Clear the redo stack
    // }

    // Undo last action
    // function undo() {
    //     if (undoStack.length > 0) {
    //         redoStack.push(JSON.stringify(canvas));
    //         var lastState = undoStack.pop();
    //         canvas.clear(); // Clear current canvas
    //         canvas.loadFromJSON(lastState, canvas.renderAll.bind(canvas));
    //     }
    // }

    // Redo last undone action
    // function redo() {
    //     if (redoStack.length > 0) {
    //         saveState();
    //         var lastUndo = redoStack.pop();
    //         canvas.clear(); // Clear current canvas
    //         canvas.loadFromJSON(lastUndo, canvas.renderAll.bind(canvas));
    //     }
    // }

    // Add event listeners for undo and redo buttons
    // document.getElementById('undo').addEventListener('click', function() {
    //     undo();
    // });

    // document.getElementById('redo').addEventListener('click', function() {
    //     redo();
    // });

    // // Save the initial state
    // saveState();

    let canvasHistory = [];
    let historyIndex = -1;

    // Function to save the current state of the canvas
    function saveCanvasState() {
        historyIndex++;
        canvasHistory = canvasHistory.slice(0, historyIndex); // Remove any redo history
        canvasHistory.push(JSON.stringify(canvas)); // Save the current state as JSON
    }

    // Function to restore the canvas to a given state
    function restoreCanvasState(state) {
        canvas.loadFromJSON(state, function() {
            canvas.renderAll();
        });
    }

    // Undo button functionality
    document.getElementById('undo').addEventListener('click', function() {
        if (historyIndex > 0) {
            historyIndex--;
            restoreCanvasState(canvasHistory[historyIndex]);
        }
    });

    // Redo button functionality
    document.getElementById('redo').addEventListener('click', function() {
        // Ensure that redo can only occur if there's a future state in history
        if (historyIndex < canvasHistory.length - 1) {
            historyIndex++;
            restoreCanvasState(canvasHistory[historyIndex]);
        }
    });

    // Track canvas changes
    canvas.on('object:added', saveCanvasState);
    canvas.on('object:modified', saveCanvasState);
    canvas.on('object:removed', saveCanvasState);
</script>

<script>
    // Function to check if an object is of a specific type
    function isCustomArrow(object) {
        return object && object.type === 'group' && object.customType === 'arrow';
    }

    function showDiveOnTextSelect() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'i-text') {
            document.getElementById('hiddenTextControlDiv').style.display = 'block';
            document.getElementById('HiddenDrawingDiv').style.display = 'none';
            document.getElementById('hiddenShapeControlDiv').style.display = 'none';
            document.getElementById('hiddenImageControlDiv').style.display = 'none';
            canvas.isDrawingMode = false;
            document.getElementById('uniformDisplayMenu').style.display = 'block';

        } else {
            document.getElementById('hiddenTextControlDiv').style.display = 'none';

        }
    }


    // Function to show the button when a shape is selected
    function showDivOnShapeSelect() {
        var activeObject = canvas.getActiveObject();

        if (activeObject && (activeObject.type === 'rect' || activeObject.type === 'circle' || activeObject.type ===
                'triangle' || activeObject.type === 'line' || activeObject.type === 'polygon' || activeObject.type ===
                'ellipse' || isCustomArrow(activeObject))) {
            document.getElementById('hiddenShapeControlDiv').style.display = 'block';
            document.getElementById('HiddenDrawingDiv').style.display = 'none';
            document.getElementById('hiddenTextControlDiv').style.display = 'none';
            document.getElementById('hiddenImageControlDiv').style.display = 'none';
            canvas.isDrawingMode = false;
            document.getElementById('uniformDisplayMenu').style.display = 'block';
        } else {
            document.getElementById('hiddenShapeControlDiv').style.display = 'none';

        }
    }

    // Function to show the button when an image is selected
    function showDivOnImageSelect() {
        var activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type === 'image') {
            document.getElementById('hiddenImageControlDiv').style.display = 'block';
            document.getElementById('HiddenDrawingDiv').style.display = 'none';
            document.getElementById('hiddenTextControlDiv').style.display = 'none';
            document.getElementById('hiddenShapeControlDiv').style.display = 'none';
            canvas.isDrawingMode = false;
            showUniformDisplayMenu();
        } else {
            document.getElementById('hiddenImageControlDiv').style.display = 'none';

        }
    }

    // Function to hide all buttons
    function hideAllDivs() {
        document.getElementById('hiddenShapeControlDiv').style.display = 'none';
        document.getElementById('hiddenImageControlDiv').style.display = 'none';
        document.getElementById('hiddenTextControlDiv').style.display = 'none';
        hideUniformDisplayMenu()
    }

    function checkMultipleSelection() {
        var hiddenDiv = document.getElementById('hiddenMultipleSelect');
        if (canvas.getActiveObjects().length > 1) {
            hiddenDiv.style.display = 'block';
        } else {
            hiddenDiv.style.display = 'none';
        }
    }

    // Attach event listeners to canvas for selection events
    canvas.on('selection:created', function() {
        showDivOnShapeSelect();
        showDivOnImageSelect();
        showDiveOnTextSelect();
        checkMultipleSelection();
    });
    canvas.on('selection:updated', function() {
        showDivOnShapeSelect();
        showDivOnImageSelect();
        showDiveOnTextSelect();
        checkMultipleSelection();
    });


    canvas.on('selection:cleared', function() {
        hideAllDivs();
        checkMultipleSelection();
    });
</script>

<script>
    const text = new fabric.IText('Edit me', {
        left: 40,
        top: 50,
        width: 500,
        height: 100,
        tag: 'title',
        padding: 10
    });
    canvas.add(text);


    document.getElementById('saveTemplate').addEventListener('click', function() {
        const MAX_SIZE = 2048;
        let width = canvas.width;
        let height = canvas.height;
        let scaleFactor = 1;

        if (width > MAX_SIZE || height > MAX_SIZE) {
            scaleFactor = Math.min(MAX_SIZE / width, MAX_SIZE / height);
            width = width * scaleFactor;
            height = height * scaleFactor;

            // Create a new scaled canvas
            const scaledCanvas = new fabric.StaticCanvas();
            scaledCanvas.setDimensions({
                width,
                height
            });
            scaledCanvas.loadFromJSON(canvas.toJSON(), () => {
                scaledCanvas.forEachObject((obj) => {
                    obj.scaleX *= scaleFactor;
                    obj.scaleY *= scaleFactor;
                    obj.left *= scaleFactor;
                    obj.top *= scaleFactor;
                    obj.setCoords();
                });
                scaledCanvas.renderAll();

                submitTemplate(scaledCanvas);
            });
        } else {
            submitTemplate(canvas);
        }
    });


    // Function to submit the template data
    function submitTemplate(canvas) {
        const templateName = prompt('Enter template name:');
        if (templateName === null) return;

        const canvasWidth = canvas.width;
        const canvasHeight = canvas.height;


        const editableRegions = canvas.getObjects().map(obj => {
            if (obj.type === 'i-text') {
                return {
                    type: 'i-text',
                    left: obj.left,
                    top: obj.top,
                    width: obj.width * obj.scaleX,
                    height: obj.height * obj.scaleY,
                    text: obj.text,
                    tag: obj.tag || 'description',
                    fill: obj.fill,
                    fontSize: obj.fontSize,
                    fontFamily: obj.fontFamily,
                    fontWeight: obj.fontWeight || 'normal',
                    stroke: obj.stroke || 'transparent', // Border color
                    strokeWidth: obj.strokeWidth || 0, // Border width
                    underline: obj.underline || false, // Underline
                    textAlign: obj.textAlign || 'left' // Text alignment
                };
            } else if (obj.type === 'image') {
                return {
                    type: 'image',
                    left: obj.left,
                    top: obj.top,
                    width: obj.width * obj.scaleX,
                    height: obj.height * obj.scaleY,
                    src: obj.src || obj._element.src,
                    tag: obj.tag || 'image',
                    opacity: obj.opacity || 1,
                };
            } else if (['rect', 'circle', 'triangle', 'line', 'ellipse', 'star'].includes(obj.type ||
                    isCustomArrow(
                        obj))) {
                const fill = obj.fill;
                let patternSrc = null;

                if (fill && fill.source) {
                    if (fill.source.src) {
                        patternSrc = fill.source.src;
                    } else if (fill.source.toDataURL) {
                        patternSrc = fill.source.toDataURL();
                    }
                }

                return {
                    type: obj.type,
                    left: obj.left,
                    top: obj.top,
                    width: obj.width * obj.scaleX,
                    height: obj.height * obj.scaleY,
                    fill: patternSrc ? null : obj.fill,
                    patternSrc: patternSrc || null,
                    stroke: obj.stroke || null,
                    strokeWidth: obj.strokeWidth || 0,
                    strokeDashArray: obj.strokeDashArray || null,
                    tag: obj.tag || 'shape'
                };
            } else if (obj.type === 'video') {
                return {
                    type: 'video',
                    left: obj.left,
                    top: obj.top,
                    width: obj.width * obj.scaleX,
                    height: obj.height * obj.scaleY,
                    src: obj.src || obj._element.src, // Save the source of the video
                    tag: obj.tag || 'video'
                };
            }
            return null;
        }).filter(obj => obj !== null); // Filter out any null values


        // Convert canvas to image data URL
        const dataURL = canvas.toDataURL({
            format: 'png',
            quality: 1
        });

        // Ensure the data URL image is within size limits
        const img = new Image();
        img.src = dataURL;
        img.onload = function() {
            const maxSize = 2048;
            if (img.width > maxSize || img.height > maxSize) {
                // Scale down the image if necessary
                const canvasImage = document.createElement('canvas');
                const context = canvasImage.getContext('2d');
                const scaleFactor = Math.min(maxSize / img.width, maxSize / img.height);
                canvasImage.width = img.width * scaleFactor;
                canvasImage.height = img.height * scaleFactor;
                context.drawImage(img, 0, 0, canvasImage.width, canvasImage.height);
                const scaledDataURL = canvasImage.toDataURL('image/png');

                document.getElementById('templateName').value = templateName;
                document.getElementById('templateImage').value = scaledDataURL;
                document.getElementById('templateRegions').value = JSON.stringify(editableRegions);

                document.getElementById('templateForm').submit();
            } else {
                document.getElementById('templateName').value = templateName;
                document.getElementById('templateImage').value = dataURL;
                document.getElementById('canvas_width').value = canvasWidth;
                document.getElementById('canvas_height').value = canvasHeight;
                document.getElementById('templateRegions').value = JSON.stringify(editableRegions);

                document.getElementById('templateForm').submit();
            }
        };
    }




    // save as image
    function saveAsImage() {
        var dataURL = canvas.toDataURL({
            format: 'png',
            quality: 1
        });
        var link = document.createElement('a');
        link.href = dataURL;
        link.download = 'canvas.png';
        link.click();
    }

    // Attach event listener to button
    var saveAsImageButton = document.getElementById('saveAsImage');

    if (saveAsImageButton) {
        saveAsImageButton.addEventListener('click', saveAsImage);
    }


    function saveAsJSON() {
        var json = JSON.stringify(canvas.toJSON());
        var blob = new Blob([json], {
            type: "application/json"
        });
        var url = URL.createObjectURL(blob);
        var link = document.createElement('a');
        link.href = url;
        link.download = 'canvas.json';
        link.click();
        URL.revokeObjectURL(url);
    }

    // Attach event listener to button
    // document.getElementById('saveAsJSON').addEventListener('click', saveAsJSON);

    var saveAsJsonButton = document.getElementById('saveAsJSON');

    if (saveAsJsonButton) {
        saveAsJsonButton.addEventListener('click', saveAsJSON);
    }
</script>

<script>
    const opacityControl = document.getElementById('opacityControl');
    const opacityRange = document.getElementById('opacityRange');
    const opacityValue = document.getElementById('opacityValue');
    let activeImage = null;


    canvas.on('mouse:dblclick', function(e) {
        const selectedObject = canvas.findTarget(e.e); // Get the object under the double-click
        if (selectedObject && selectedObject.type === 'image') {
            activeImage = selectedObject;
            replaceImageInput.click(); // Trigger file input when an image is double-clicked
        }
    });



    // Handle image replacement
    replaceImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file && activeImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fabric.Image.fromURL(e.target.result, function(img) {
                    // Calculate scale to maintain size of the original image
                    img.scaleToWidth(activeImage.width * activeImage.scaleX);
                    img.scaleToHeight(activeImage.height * activeImage.scaleY);

                    // Set the position and angle to match the original image
                    img.set({
                        left: activeImage.left,
                        top: activeImage.top,
                        angle: activeImage.angle
                    });

                    // Remove the old image and add the new one in its place
                    canvas.remove(activeImage);
                    canvas.add(img);
                    activeImage = img;
                    canvas.renderAll();
                });
            };
            reader.readAsDataURL(file);
        }
    });


    // Handle image selection
    canvas.on('selection:updated', handleSelection);
    canvas.on('selection:created', handleSelection);

    function handleSelection(e) {
        const selectedObject = e.target;

        if (selectedObject && selectedObject.type === 'image') {
            activeImage = selectedObject;

            // Set the range input value to the current opacity of the image
            opacityRange.value = activeImage.opacity;
            opacityValue.innerText = `${Math.round(activeImage.opacity * 100)}%`;

            // Position the opacity control near the selected image
            const canvasRect = canvas.getElement().getBoundingClientRect();
            const objRect = activeImage.getBoundingRect();

            opacityControl.style.left = `${canvasRect.left + objRect.left}px`;
            opacityControl.style.top = `${canvasRect.top + objRect.top + objRect.height + 10}px`;
            opacityControl.style.display = 'block'; // Show the control
        } else {
            opacityControl.style.display = 'none'; // Hide the control if no image is selected
            activeImage = null;
        }
    }

    // Handle object deselection
    canvas.on('selection:cleared', function() {
        activeImage = null;
        opacityControl.style.display = 'none'; // Hide the control
    });

    // Update image opacity based on range input value
    opacityRange.addEventListener('input', function() {
        if (activeImage) {
            const opacity = parseFloat(opacityRange.value);
            activeImage.set('opacity', opacity);
            opacityValue.innerText = `${Math.round(opacity * 100)}%`; // Update the opacity percentage
            canvas.renderAll(); // Re-render the canvas
        }
    });
</script>



<script>
    // fabric.Image.fromURL('https://via.placeholder.com/150', function(img) {
    //     img.set({
    //         left: 100,
    //         top: 100
    //     });
    //     canvas.add(img);
    // });


    function addBorderToSelectedObject(canvas, borderColor = 'black', borderWidth = 2) {
        // Get the active object (the selected object) on the canvas
        // var activeObject = canvas.getActiveObject();

        // if (activeObject) {
        //     // Set the stroke (border) properties
        //     activeObject.set({
        //         stroke: borderColor,
        //         strokeWidth: borderWidth
        //     });

        //     // Render the canvas to apply changes
        //     canvas.renderAll();
        // } else {
        //     console.log('No object selected');
        // }


        var activeObject = canvas.getActiveObject();

        if (activeObject) {
            // Check if the object already has a border
            if (activeObject.stroke && activeObject.strokeWidth) {
                // If it has a border, remove it
                activeObject.set({
                    stroke: null,
                    strokeWidth: 0
                });
            } else {
                // If it doesn't have a border, add it
                activeObject.set({
                    stroke: borderColor,
                    strokeWidth: borderWidth
                });
            }

            // Render the canvas to apply changes
            canvas.renderAll();
        } else {
            console.log('No object selected');
        }

    }



    // --------
    function updateBorderWidthRealTime(canvas, rangeInput) {
        rangeInput.addEventListener('input', function() {
            const activeObject = canvas.getActiveObject();
            if (activeObject) {

                activeObject.set('strokeWidth', parseInt(rangeInput.value, 10));
                canvas.renderAll();
            }
        });
    }

    function updateBorderColorRealTime(canvas, colorInput) {
        colorInput.addEventListener('input', function() {
            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                activeObject.set('stroke', colorInput.value);
                canvas.renderAll();
            }
        });
    }

    canvas.on('selection:created', function(e) {
        const activeObject = e.target;

        if (activeObject) {
            rangeInput.value = activeObject.strokeWidth || 1;
            colorInput.value = activeObject.stroke || '#000000';
        }
    });

    canvas.on('selection:updated', function(e) {
        const activeObject = e.target;

        if (activeObject) {
            rangeInput.value = activeObject.strokeWidth || 1;
            colorInput.value = activeObject.stroke || '#000000';
        }
    });

    const rangeInput = document.getElementById('border-width');
    const colorInput = document.getElementById('border-color');

    updateBorderWidthRealTime(canvas, rangeInput);
    updateBorderColorRealTime(canvas, colorInput);

    // ---------

    function createRoundedClipPath(radius, width, height) {
        const path = new fabric.Path(
            `M ${radius} 0 H ${width - radius} A ${radius} ${radius} 0 0 1 ${width} ${radius} V ${height - radius} A ${radius} ${radius} 0 0 1 ${width - radius} ${height} H ${radius} A ${radius} ${radius} 0 0 1 0 ${height - radius} V ${radius} A ${radius} ${radius} 0 0 1 ${radius} 0 Z`, {
                originX: 'left',
                originY: 'top',
                left: 0,
                top: 0,
                width: width,
                height: height
            });
        return path;
    }

    function handleCornerRadiusChange(value) {
        const activeObject = canvas.getActiveObject();

        if (activeObject) {
            const maxRadius = Math.min(activeObject.width, activeObject.height) / 2;
            const cornerRadius = (value / 100) * maxRadius;

            if (activeObject.type === 'rect' || activeObject.type === 'circle') {
                activeObject.set({
                    rx: cornerRadius,
                    ry: cornerRadius
                });
            } else if (activeObject.type === 'image') {
                const clipPath = createRoundedClipPath(cornerRadius, activeObject.width, activeObject.height);
                activeObject.set({
                    clipPath: clipPath
                });
            } else {
                console.log('Selected object type does not support corner radius.');
            }

            canvas.renderAll();
        } else {
            console.log('No object selected.');
        }
    }

    // Example usage with a range input
    document.getElementById('cornerRadiusRange').addEventListener('input', function() {
        handleCornerRadiusChange(this.value);
    });


    // --------------
</script>
