<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Template;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class PinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Template::all();
        return view('pins.index', compact('templates'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pin $pin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pin $pin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pin $pin)
    {
        //
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

        $templateIds = $request->input('template_ids');
        $pinCount = $request->input('pin_count');

        $faker = Faker::create();
        $pins = [];

        for ($i = 0; $i < $pinCount; $i++) {
            $templateId = $faker->randomElement($templateIds);
            $template = Template::find($templateId);

            $pin = new Pin();
            $pin->user_id = auth()->id();
            $pin->template_id = $templateId;
            $pin->title = $faker->sentence;
            $pin->description = $faker->paragraph;

            // Simulate an uploaded image by using a placeholder image
            // $pin->image_path = 'pins/placeholder.png';

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('pins', 'public');
                $pin->image_path = $imagePath;
            }

            $pin->save();
            $pins[] = $pin;
        }

        return response()->json(['message' => 'Pins generated successfully!', 'pins' => $pins], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pin $pin)
    {
        //
    }
}
