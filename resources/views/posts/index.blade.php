<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-800">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Validation Errors -->
                    <x-auth-validations-errors class="mb-4" :errors="$errors" />

                    <!-- Formulario para crear nuevo post -->
                    <form action="{{ route('posts.store') }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-900">Título</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required>
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-900">Contenido</label>
                            <textarea name="content" id="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-gray-900 font-bold py-2 px-4 rounded shadow-sm">
                            Crear Post
                        </button>
                    </form>

                    <!-- Lista de posts -->
                    <div class="space-y-4">
                        @foreach($posts as $post)
                        <div class="border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $post->title }}</h3>
                            <p class="text-gray-700 mt-2">{{ $post->body }}</p>
                            <div class="mt-3 flex space-x-2">
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm shadow-sm">
                                        Eliminar
                                    </button>
                                </form>
                                <button onclick="editPost('{{ $post->id }}')" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-1 px-2 rounded text-sm shadow-sm">
                                    Editar
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar post -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-xl rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Editar Post</h3>
                <form id="editForm" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="edit_title" class="block text-sm font-medium text-gray-900">Título</label>
                        <input type="text" name="title" id="edit_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_content" class="block text-sm font-medium text-gray-900">Contenido</label>
                        <textarea name="content" id="edit_content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-600 focus:ring-indigo-600" required></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-sm">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-sm">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editPost(id) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const post = @json($posts);
            const currentPost = post.find(p => p.id === id);

            document.getElementById('edit_title').value = currentPost.title;
            document.getElementById('edit_content').value = currentPost.body;
            form.action = `/posts/${id}`;

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>