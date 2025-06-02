<?php

// Importamos las clases necesarias para crear la migración
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Creamos una nueva clase anónima que extiende de Migration
return new class extends Migration
{
    /**
     * Este método se ejecuta cuando ejecutamos la migración
     * Aquí definimos la estructura de la tabla posts
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            // Creamos un ID autoincremental como clave primaria
            $table->id();

            // Agregamos una columna para la relación con la tabla users
            // unsignedBigInteger es el tipo de dato recomendado para IDs en Laravel
            $table->unsignedBigInteger('user_id');

            // Definimos la clave foránea que relaciona posts con users
            // onDelete('cascade') eliminará todos los posts cuando se elimine el usuario
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Campo para almacenar el contenido del post
            $table->text('body');

            // Agrega automáticamente created_at y updated_at
            $table->timestamps();
        });
    }

    /**
     * Este método se ejecuta cuando revertimos la migración
     * Elimina la tabla posts de la base de datos
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
