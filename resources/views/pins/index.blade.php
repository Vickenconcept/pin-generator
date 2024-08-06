<!-- resources/views/pins/generate.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Random Pins</title>
</head>
<body>
    <form action="{{ route('pins.generateRandom') }}" method="POST">
        @csrf
        <label for="template_ids">Choose templates:</label>
        <select name="template_ids[]" id="template_ids" multiple>
            @foreach($templates as $template)
                <option value="{{ $template->id }}">{{ $template->name }}</option>
            @endforeach
        </select>

        <label for="pin_count">Number of pins to generate:</label>
        <input type="number" name="pin_count" id="pin_count" min="1">

        <button type="submit">Generate Pins</button>
    </form>
</body>
</html>
