<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

         $templates = Template::get()->shuffle();

         foreach ($templates as $template) {
             $template->editable_regions = json_decode($template->editable_regions);
         }
 
         return $request->wantsJson()
         ? response()->json( $templates->toArray())
         : view('templates.index');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'image' => 'required|image|max:2048', // Ensure the image is valid and not too large
            'editable_regions' => 'required|json',
            'width' => 'required',
            'height' => 'required',
        ]);
 
        // dd($request->all());

        // Decode the base64 image and save it
        $imageData = $request->input('image');
        $imageName = 'templates/' . uniqid() . '.png';
        Storage::disk('public')->put($imageName, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)));

        // Create the template
        $template = Template::create([
            'name' => $request->input('name'),
            'path' => $imageName,
            'editable_regions' => $request->input('editable_regions'),
            'width' => (int)  $validated['width'] ,
            'height' => (int)  $validated['height'] ,
        ]);

        return back()->with('success','Template created successfully');
        // return response()->json(['message' => 'Template created successfully!', 'template' => $template], 201);
    }



    public function uploadVideo(Request $request)
    {
        // Log request data
        \Log::info('Request Files:', ['files' => $request->all()]);
        \Log::info('Has File:', ['has_file' => $request->hasFile('video')]);

        // Check if video file is uploaded
        if ($request->hasFile('video')) {
            try {
                $file = $request->file('video');
                $path = $file->store('videos', 'public');

                return response()->json([
                    'success' => true,
                    'videoUrl' => Storage::url($path)
                ]);
            } catch (\Exception $e) {
                \Log::error('Upload Error:', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'error' => 'No file uploaded'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        // $template = Template::find($id);

        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        // Prepare the response data
        $response = [
            'id' => $template->id,
            'name' => $template->name,
            'path' => asset('storage/' . $template->path), // Ensure the URL is accessible
            'editable_regions' => $template->editable_regions,
        ];

        return response()->json($response);
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
