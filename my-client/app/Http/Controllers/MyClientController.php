<?php

namespace App\Http\Controllers;

use App\Models\MyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class MyClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MyClient::all();
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
            'name' => 'required|max:250',
            'slug' => 'required|max:100|unique:my_clients',
            'is_project' => 'required|in:0,1',
            'self_capture' => 'sometimes|max:1',
            'client_prefix' => 'required|max:4',
            'client_logo' => 'sometimes|string',
            'address' => 'nullable',
            'phone_number' => 'nullable|max:50',
            'city' => 'nullable|max:50',
        ]);

        $client = MyClient::create($validated);
        
        Redis::set("client:{$client->slug}", json_encode($client));
        
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id_slug)
    {
        // Check Redis first
        if ($cached = Redis::get("client:{$id_slug}")) {
            return response()->json(json_decode($cached));
        }

        $client = MyClient::where('slug', $id_slug)->firstOrFail();
        return response()->json(null, 201);
    }

    public function uploadLogo(Request $request, $id)
    {
        $request->validate(['logo' => 'required|image|max:2048']);
        
        $client = MyClient::findOrFail($id);
        $path = $request->file('logo')->store('client-logos', 's3');
        
        if ($client->client_logo !== 'no-image.jpg') {
            Storage::disk('s3')->delete($client->client_logo);
        }
        
        $client->update(['client_logo' => Storage::disk('s3')->url($path)]);
        
        return response()->json(['url' => $client->client_logo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MyClient $myClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = MyClient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|max:250',
            'slug' => 'sometimes|max:100|unique:my_clients,slug,'.$client->id,
            'is_project' => 'sometimes|in:0,1',
            'self_capture' => 'sometimes|max:1',
            'client_prefix' => 'sometimes|max:4',
            'client_logo' => 'sometimes|string',
            'address' => 'nullable',
            'phone_number' => 'nullable|max:50',
            'city' => 'nullable|max:50',
        ]);

        if ($request->has('slug') && $request->slug !== $client->slug) {
            Redis::del("client:{$client->slug}");
        }

        $client->update($validated);
        
        Redis::set("client:{$client->slug}", json_encode($client));
        
        return response()->json(null, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = MyClient::findOrFail($id);
        Redis::del("client:{$client->slug}");
        $client->delete();
        
        return response()->json(null, 204);
    }
}
