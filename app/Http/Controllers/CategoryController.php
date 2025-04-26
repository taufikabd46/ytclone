<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Video; // Import the Video model

class CategoryController extends Controller
{
    // Show all categories
    public function index()
    {
        $categories = Category::all(); // Retrieve all categories
        return response()->json($categories); // Return categories as a JSON response
    }

    // Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($category, 201); // Return the created category as a JSON response
    }

    // Show a single category by ID
    public function show($id)
    {
        $category = Category::findOrFail($id); // Find category or fail
        return response()->json($category); // Return category as JSON
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $category = Category::findOrFail($id); // Find category or fail
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json($category); // Return updated category as JSON
    }

    // Delete a category
    public function destroy($id)
    {
        $category = Category::findOrFail($id); // Find category or fail
        $category->delete(); // Delete the category

        //response success
        return response()->json(['message' => 'Category deleted successfully'], 200); // Return success message
    }

    //videos within a category
    public function videos($id)
    {
        $category = Category::findOrFail($id); // Find category or fail
        $videos = Video::where('category_id', $category->id)->get(); // Get videos for the category

        return response()->json($videos); // Return videos as JSON
    } 
}
