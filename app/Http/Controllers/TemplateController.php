<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('templates.index');
    }
    public function template_build()
    {
        return view('templates.build');
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
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048', // Ensure the image is valid and not too large
            'editable_regions' => 'required|array',
        ]);

        // Handle the image upload
        $imagePath = $request->file('image')->store('templates', 'public');

        // Create the template
        $template = Template::create([
            'name' => $request->input('name'),
            'path' => $imagePath,
            'editable_regions' => json_encode($request->input('editable_regions')),
        ]);

        return response()->json(['message' => 'Template created successfully!', 'template' => $template], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        //
    }
}
