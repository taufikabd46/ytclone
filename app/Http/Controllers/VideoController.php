<?php

// app/Http/Controllers/VideoController.php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//Settings
use App\Models\Setting;

class VideoController extends Controller
{
    // Show all videos
    public function index()
    {
        $settings = Setting::first();
        //if randomize = 1, then randomize
        if ($settings->randomize == 1) {
            $videos = Video::inRandomOrder()->get(); // Randomize the videos
        } else {
            $videos = Video::orderBy('order')->get(); // Order by 'order' column
        }
        return response()->json($videos); // Return videos as a JSON response
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'platform' => 'required|in:Instagram,Facebook,YouTube',
            'username' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure category exists
        ]);

        // Extract YouTube video ID from URL (for YouTube URLs only)
        $videoUrl = $request->url;
        $videoId = null;
        $thumbnailUrl = null;

        if ($request->platform == 'YouTube') {
            // Extract YouTube video ID from URL
            if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
                preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/\S+\/|(?:watch\?v=|shorts\/)))([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                $videoId = $matches[1] ?? null;
                
                if ($videoId) {
                    // Generate thumbnail URL based on the video ID
                    $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
                }
            }
    
            // Check if videoId was extracted for YouTube
            if (!$videoId) {
                return response()->json(['error' => 'Invalid YouTube video URL'], 400);
            }
    
            // If platform is Facebook, save the URL as is
        } elseif ($request->platform == 'Facebook') {
            $videoId = $videoUrl; // Save the full Facebook URL directly
        }

        $lastOrder = Video::max('order');
        $newOrder = $lastOrder + 1;


        // Create the video in the database
        $video = Video::create([
            'title' => $request->title,
            'url' => $videoId, // Store only the video ID
            'platform' => $request->platform,
            'thumbnail_url' => $thumbnailUrl, // Store the thumbnail URL
            'username' => $request->username,
            'category_id' => $request->category_id, // Store the category ID
            'description' => $request->description, // Optional description
            'order' => $newOrder, // Set the order of the video
        ]);

        // Return the created video as a JSON response
        return response()->json($video, 201); // Return the created video with status 201
    }

    // Show a single video by ID
    public function show($id)
    {
        $video = Video::findOrFail($id); // Find video or fail
        return response()->json($video); // Return video as JSON
    }

    // Update an existing video
    // app/Http/Controllers/VideoController.php

public function update(Request $request, $id)
{
    // Validate incoming request data
    $request->validate([
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'platform' => 'required|in:Instagram,Facebook,YouTube',
        'username' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id', // Ensure category exists
        'description' => 'nullable|string|max:500', // Optional description
    ]);

    // Extract YouTube video ID from URL (for YouTube URLs only)
    $videoUrl = $request->url;
    $videoId = null;
    $thumbnailUrl = null;

    if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false) {
        // Extract video ID from the URL (works for both regular and Shorts URLs)
        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/\S+\/|(?:watch\?v=|shorts\/)))([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
        $videoId = $matches[1] ?? null;
        
        if ($videoId) {
            // Generate thumbnail URL based on the video ID
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }
    }

    // Check if videoId was extracted
    if (!$videoId) {
        return response()->json(['error' => 'Invalid YouTube video URL'], 400);
    }

    

    // Find the existing video by ID and update it
    $video = Video::findOrFail($id); // Find video or fail
    $video->update([
        'title' => $request->title,
        'url' => $videoId, // Store only the video ID
        'platform' => $request->platform,
        'thumbnail_url' => $thumbnailUrl, // Store the thumbnail URL
        'username' => $request->username,
        'category_id' => $request->category_id, // Update the category ID
        'description' => $request->description, // Update the description
    ]);

    return response()->json($video); // Return the updated video as JSON
}


    // Delete a video
    public function destroy($id)
    {
        $video = Video::findOrFail($id); // Find video or fail
        $video->delete(); // Delete the video

        return response()->json(null, 204); // Return a success response
    }

    //getVideo
    public function getVideo(Request $request)
    {
        $videos = Video::orderBy('order')->get();

        if ($videos->isEmpty()) {
            return response()->json(['message' => 'No videos found'], 404);
        }
        return response()->json($videos);  
    }

    //getRandomVideo
    public function getRandomVideo(Request $request)
    {
        // there is no relation with settings at all
        $videoRandom = Video::inRandomOrder()->first();
        if ($videoRandom) {
            return response()->json($videoRandom);
        } else {
            return response()->json(['message' => 'No videos found'], 404);
        } 
    }

        public function updateVideoOrder()
    {
        // Ambil semua video yang ada, urutkan berdasarkan ID atau created_at
        $videos = Video::orderBy('created_at')->get(); // Urutkan berdasarkan tanggal dibuat (atau ID)

        $order = 1; // Mulai urutan dari 1
        foreach ($videos as $video) {
            $video->order = $order; // Set order video
            $video->save(); // Simpan perubahan
            $order++; // Tambah urutan
        }

        return response()->json(['message' => 'Video order updated successfully']);
    }

    public function updateOrder(Request $request)
    {
        // Validasi data urutan yang diterima
        $request->validate([
            'videos' => 'required|array', // Pastikan data yang dikirim berupa array
            'videos.*.id' => 'required|exists:videos,id', // ID video harus ada dalam database
            'videos.*.order' => 'required|integer', // Urutan harus integer
        ]);

        // Proses setiap video dan update urutan sesuai dengan data yang diterima
        foreach ($request->videos as $videoData) {
            $video = Video::find($videoData['id']); // Cari video berdasarkan ID
            $video->order = $videoData['order']; // Update urutan video
            $video->save(); // Simpan perubahan
        }

        return response()->json(['message' => 'Video order updated successfully']);
    }

    
}
