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

    /**
     * Almacena un nuevo post en la base de datos
     *
     * Este método maneja la creación de nuevos posts en el sistema. Sigue estos pasos:
     * 1. Verifica que el usuario esté autenticado
     * 2. Valida los datos del formulario
     * 3. Crea el post asociado al usuario actual
     * 4. Redirecciona con mensaje de éxito
     *
     * @param Request $request Contiene los datos del formulario (title y content)
     * @return \Illuminate\Http\RedirectResponse Redirecciona al dashboard con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Verificar autenticación del usuario
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Validar los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',    // Título requerido, máximo 255 caracteres
            'content' => 'required|string',          // Contenido requerido
        ]);

        // Crear el post asociado al usuario actual
        $request->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->content,
        ]);

        // Redireccionar con mensaje de éxito
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
