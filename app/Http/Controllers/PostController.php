<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Post creado exitosamente');
    }

    public function destroy(Post $post)
    {
        // Verificar que el usuario sea el dueño del post
        if ($post->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para eliminar este post');
        }

        $post->delete();
        return redirect()->route('dashboard')->with('success', 'Post eliminado exitosamente');
    }

    public function update(Request $request, Post $post)
    {
        // Verificar que el usuario sea el dueño del post
        if ($post->user_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para actualizar este post');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update([
            'title' => $request->title,
            'body' => $request->content,
        ]);

        return redirect()->route('dashboard')->with('success', 'Post actualizado exitosamente');
    }
}
