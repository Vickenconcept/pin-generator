<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Template;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $templates = Template::get();
        $pins = Pin::get();

        foreach ($templates as $template) {
            $template->editable_regions = json_decode($template->editable_regions);
        }


        return $request->wantsJson()
            ? response()->json($pins->toArray())
            : view('pins.index', compact('pins','templates'));
    }

    
    public function generate()
    {
        $templates = Template::all();
        foreach ($templates as $template) {
            $template->editable_regions = json_decode($template->editable_regions);
        }
        $pins = Pin::all();
        return view('pins.generate', compact('templates', 'pins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'template_id' => 'required|integer|exists:templates,id',
            'editable_regions' => 'required|json'
        ]);

        $pin = Pin::create([
            'name' => $request->input('name'),
            'template_id' => $request->input('template_id'),
            'editable_regions' => $request->input('editable_regions'),
        ]);

        return response()->json(['success' => true, 'pin' => $pin]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Pin $pin)
    {
        return $request->wantsJson()
            ? response()->json($pin->toArray())
            : view('pins.show', compact('pins'));
        // return view('pins.show' , compact('pin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pin $pin)
    {
        return view('pins.edit', compact('pin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pin $pin)
    {
   
        $data =  $request->validate([
            'name' => 'sometimes|required|string',
            'template_id' => 'sometimes|required|exists:templates,id',
            'editable_regions' => 'sometimes|required',
        ]);


        if ($request->has('path')) {
            // Check if the pin already has an image
            // if ($pin->path) {
            //     // Delete the old image from storage
            //     Storage::disk('public')->delete($pin->path);
            // }
    
            // Save the new image
            $imageData = $request->input('path');
            $imageName = 'templates/' . uniqid() . '.png';
            Storage::disk('public')->put($imageName, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)));
    
            // Update the pin's image path
            $data['path'] = $imageName;
        }

        $pin->update([
            'name' => $request->input('name', $pin->name),
            'template_id' => $request->input('template_id', $pin->template_id), 
            'editable_regions' => $request->input('editable_regions', $pin->editable_regions), 
            'path' => $data['path'] ?? $pin->path,
        ]);

        // Return a JSON response indicating success
        return response()->json(['success' => true, 'pin' => $pin]);
    }


    // public function showGenerateForm()
    // {

    // }



    public function generateRandomPins(Request $request)
    {
        $request->validate([
            'template_ids' => 'required|array|min:1',
            'template_ids.*' => 'exists:templates,id',
            'pin_count' => 'required|integer|min:1',
        ]);
        // dd($request->all());

        $templateIds = $request->input('template_ids');
        $pinCount = $request->input('pin_count');

        $faker = Faker::create();
        $pins = [];

        for ($i = 0; $i < $pinCount; $i++) {
            $templateId = $faker->randomElement($templateIds);
            $template = Template::find($templateId);

            $template->editable_regions = json_decode($template->editable_regions);

            $pin = new Pin();
            $pin->user_id = auth()->id();
            $pin->template_id = $templateId;
            $pin->name = $faker->sentence;
            $pin->editable_regions = $template->editable_regions;
            $pin->path =  $template->path;
            $pin->width =  $template->width;
            $pin->height =  $template->height;

            $pin->save();
            $pins[] = $pin;
        }

        return response()->json($pins);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pin $pin)
    {
        $pin->delete();

        return response()->json([
            'message' => 'deleted successfully'
        ]);
    }
}
