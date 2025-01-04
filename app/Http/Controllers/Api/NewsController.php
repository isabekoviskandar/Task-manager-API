<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->get()->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'text' => $post->text,
                'file' => $post->file,
                'category_name' => $post->category->name ?? null,
            ];
        });

        $categories = Category::whereNotIn('id', [1, 2, 3])
            ->with('posts:id,title,category_id')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'posts' => $category->posts->map(function ($post) {
                        return [
                            'id' => $post->id,
                            'title' => $post->title,
                        ];
                    }),
                ];
            });

        return response()->json([
            'posts' => $posts,
            'categories' => $categories,
            'message' => 'List of news with categories',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'text' => 'required|max:300',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $category = Category::where('id', $request->category_id);

        $filePath = $request->file('file')->store('uploads', 'public');

        $post = Post::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'text' => $request->text,
            'file' => $filePath,
        ]);

        return response()->json([
            'post' => $post,
            'category' => $category,
            'message' => 'News created successfully',
        ]);
    }

    public function show(Post $post)
    {
        $data = [
            'post' => $post,
            'message' => 'show new'
        ];
        return response()->json($data);
    }

    public function update(Post $post, Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'text' => 'required|max:300',
            'file' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $filePath = $post->file;

        if ($request->hasFile('file')) {
            if ($post->file && Storage::disk('public')->exists($post->file)) {
                Storage::disk('public')->delete($post->file);
            }
            $filePath = $request->file('file')->store('uploads', 'public');
        }

        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'text' => $request->text,
            'file' => $filePath,
        ]);

        $category = Category::where('id', $request->category_id);

        return response()->json([
            'post' => $post,
            'category' => $category,
            'message' => 'News updated successfully',
        ]);
    }


    public function delete(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
