<x-app-layout>
    {{-- <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="name">Template Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <label for="editable_regions">Editable Regions (JSON format):</label>
        <textarea name="editable_regions" id="editable_regions" required></textarea>

        <button type="submit">Create Template</button>
    </form> --}}





    <h1>Create Your Template</h1>

    <canvas id="canvas" width="600" height="400" style="border:1px solid #000;"></canvas>

    <button id="addText">Add Text</button>
    <button id="addImage">Add Image</button>
    <input type="file" id="uploadImage" style="display:none;">
    <button id="saveTemplate">Save Template</button>

    <script>
        var canvas = new fabric.Canvas('canvas');

        document.getElementById('addText').addEventListener('click', function() {
            var text = new fabric.Text('Your Text Here', {
                left: 100,
                top: 100,
                fill: 'black',
            });
            canvas.add(text);
        });

        document.getElementById('addImage').addEventListener('click', function() {
            document.getElementById('uploadImage').click();
        });

        document.getElementById('uploadImage').addEventListener('change', function(e) {
            var reader = new FileReader();
            reader.onload = function(event) {
                var imgObj = new Image();
                imgObj.src = event.target.result;
                imgObj.onload = function() {
                    var image = new fabric.Image(imgObj);
                    image.set({
                        left: 100,
                        top: 100,
                    });
                    canvas.add(image);
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        });

        document.getElementById('saveTemplate').addEventListener('click', function() {
            var json = JSON.stringify(canvas.toJSON());
            // Send this JSON to your server to save the template
            console.log(json);
        });
    </script>



</x-app-layout>

