
            <style>
                .todo-card {
                    transition: all 0.3s ease;
                }

                .todo-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
                }

                .comment-section {
                    max-height: 300px;
                    overflow-y: auto;
                    scrollbar-width: thin;
                }

                .comment-section::-webkit-scrollbar {
                    width: 6px;
                }

                .comment-section::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }

                .comment-section::-webkit-scrollbar-thumb {
                    background: #888;
                    border-radius: 3px;
                }

                .modal-overlay {
                    transition: opacity 0.3s ease-in-out;
                    opacity: 0;
                }

                .modal-overlay.show {
                    opacity: 1;
                }

                .modal-content {
                    transition: all 0.3s ease-in-out;
                    transform: scale(0.9);
                    opacity: 0;
                }

                .modal-overlay.show .modal-content {
                    transform: scale(1);
                    opacity: 1;
                }

                .todo-card {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    animation: slideIn 0.5s ease-out;
                }

                .todo-card.deleting {
                    transform: translateX(100%);
                    opacity: 0;
                }

                @keyframes slideIn {
                    from {
                        transform: translateY(20px);
                        opacity: 0;
                    }

                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .approve-button {
                    transition: all 0.2s ease;
                }

                .approve-button:hover {
                    transform: scale(1.05);
                }

                .modal-enter {
                    animation: modalEnter 0.3s ease-out;
                }

                .modal-leave {
                    animation: modalLeave 0.3s ease-in;
                }

                @keyframes modalEnter {
                    from {
                        transform: scale(0.95);
                        opacity: 0;
                    }

                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }

                @keyframes modalLeave {
                    from {
                        transform: scale(1);
                        opacity: 1;
                    }

                    to {
                        transform: scale(0.95);
                        opacity: 0;
                    }
                }

                .sidebar {
                    height: calc(100vh - 65px);
                    transition: all 0.3s ease;
                }

                .empty-state {
                    animation: fadeIn 0.5s ease-out;
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .filter-pill {
                    transition: all 0.2s ease;
                }

                .filter-pill.active {
                    background-color: #4F46E5;
                    color: white;
                }

                .filter-pill:hover {
                    transform: translateY(-1px);
                }
            </style>

            <x-app-layout>
                <div class="flex h-full bg-gray-50">
                    <!-- Sidebar -->
                    <div class="sidebar w-80 bg-white shadow-sm border-r border-gray-200 overflow-y-auto">
                        <div class="p-6">
                            <div class="text-center">
                                <div class="relative inline-block">
                                    <img src="{{ asset('img/boy.png') }}"
                                        class="h-24 w-24 rounded-full border-4 border-white shadow-lg">
                                    <div
                                        class="absolute bottom-0 right-0 h-4 w-4 rounded-full border-2 border-white bg-green-400">
                                    </div>
                                </div>
                                <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="mt-8">
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Statistics</h3>
                                <div class="mt-4 grid grid-cols-2 gap-4">
                                    <div class="bg-indigo-50 rounded-lg p-4">
                                        <div class="text-2xl font-bold text-indigo-600" id="totalTodosCount">
                                            {{ Auth::user()->todos()->count() }}
                                        </div>
                                        <div class="text-sm text-gray-600">Total Todos</div>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <div class="text-2xl font-bold text-green-600" id="completedTodosCount">
                                            {{ Auth::user()->todos()->where('completed', true)->count() }}
                                        </div>
                                        <div class="text-sm text-gray-600">Completed</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8">
                                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Quick Filters
                                </h3>
                                <div class="mt-4 space-y-2">
                                    <button onclick="filterTodos('all')"
                                        class="filter-pill w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50 active">
                                        All Todos
                                    </button>
                                    <button onclick="filterTodos('pending')"
                                        class="filter-pill w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50">
                                        Pending
                                    </button>
                                    <button onclick="filterTodos('completed')"
                                        class="filter-pill w-full text-left px-4 py-2 rounded-lg hover:bg-gray-50">
                                        Completed
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="py-8 px-8">
                            <!-- Header -->
                            <div class="flex justify-between items-center mb-8">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">My Todo List</h1>
                                    <p class="text-gray-500">Manage and track your tasks</p>
                                </div>
                                <button onclick="openModal()"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Todo
                                </button>
                            </div>

                            <!-- Search and Filters -->
                            <div class="mb-8">
                                <div class="relative">
                                    <input type="text" id="search"
                                        class="w-full rounded-lg border-gray-300 pl-10 pr-4 focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Search todos...">
                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Todo List -->
                            <div id="todoList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @forelse ($todos as $todo)
                                    <div class="todo-card bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1"
                                        data-id="{{ $todo->id }}"
                                        data-status="{{ $todo->completed ? 'completed' : 'pending' }}">
                                        <div class="p-6">
                                            @if ($todo->image_path)
                                                <div class="mb-4">
                                                    <img src="{{ Storage::url($todo->image_path) }}" alt="Todo image"
                                                        class="w-full h-48 object-cover rounded-lg">
                                                </div>
                                            @endif
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $todo->title }}</h3>
                                                <button onclick="toggleComplete({{ $todo->id }})"
                                                    class="status-badge px-3 py-1 rounded-full text-sm {{ $todo->completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $todo->completed ? 'Completed' : 'Pending' }}
                                                </button>
                                            </div>
                                            <p class="text-gray-600 mb-4">{{ $todo->description }}</p>

                                            <!-- Comments Section -->
                                            @if ($todo->comments->count() > 0)
                                                <div class="mb-4">
                                                    <h4
                                                        class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                        </svg>
                                                        Comments ({{ $todo->comments->count() }})
                                                    </h4>
                                                    <div class="space-y-3 comment-section">
                                                        @foreach ($todo->comments as $comment)
                                                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200
                                                                {{ $comment->approved ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}"
                                                                data-comment-id="{{ $comment->id }}">
                                                                <div class="flex items-start space-x-3">
                                                                    @if ($comment->image_path)
                                                                        <img src="{{ Storage::url($comment->image_path) }}"
                                                                            class="w-12 h-12 rounded-lg object-cover shadow-sm">
                                                                    @endif
                                                                    <div class="flex-1">
                                                                        <div
                                                                            class="flex justify-between items-start mb-1">
                                                                            <div>
                                                                                <p class="text-sm text-gray-800">
                                                                                    {{ $comment->content }}</p>
                                                                                <div class="flex items-center mt-1">
                                                                                    <span class="text-xs text-gray-500">
                                                                                        {{ $comment->user->name }} •
                                                                                        {{ $comment->created_at->diffForHumans() }}
                                                                                    </span>
                                                                                    <span
                                                                                        class="ml-2 flex items-center">
                                                                                        @if ($comment->approved)
                                                                                            <svg class="h-4 w-4 text-green-500"
                                                                                                fill="none"
                                                                                                stroke="currentColor"
                                                                                                viewBox="0 0 24 24">
                                                                                                <path
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round"
                                                                                                    stroke-width="2"
                                                                                                    d="M5 13l4 4L19 7" />
                                                                                            </svg>
                                                                                        @else
                                                                                            <svg class="h-4 w-4 text-red-500"
                                                                                                fill="none"
                                                                                                stroke="currentColor"
                                                                                                viewBox="0 0 24 24">
                                                                                                <path
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round"
                                                                                                    stroke-width="2"
                                                                                                    d="M6 18L18 6M6 6l12 12" />
                                                                                            </svg>
                                                                                        @endif
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            @if ($todo->user_id === auth()->id() && !$comment->approved && !$todo->comments->contains('approved', true))
                                                                                <button
                                                                                    onclick="approveComment({{ $comment->id }}, {{ $todo->id }})"
                                                                                    class="approve-button ml-2 inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-full
                                                                                    bg-green-100 text-green-800 hover:bg-green-200 focus:outline-none focus:ring-2
                                                                                    focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                                                    <svg class="h-3.5 w-3.5 mr-1"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M5 13l4 4L19 7" />
                                                                                    </svg>
                                                                                    Approve
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500">
                                                    By {{ $todo->user->name }} •
                                                    {{ $todo->created_at->diffForHumans() }}
                                                </span>
                                                @if ($todo->user_id === auth()->id())
                                                    <div class="flex space-x-2">
                                                        <button onclick="editTodo({{ $todo->id }})"
                                                            class="text-blue-600 hover:text-blue-800 transition-colors">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                        <button onclick="deleteTodo({{ $todo->id }})"
                                                            class="text-red-600 hover:text-red-800 transition-colors">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @else
                                                    <button onclick="openCommentModal({{ $todo->id }})"
                                                        class="text-indigo-600 hover:text-indigo-800 transition-colors">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-3 empty-state">
                                        <div class="text-center py-12">
                                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <h3 class="mt-4 text-lg font-medium text-gray-900">No todos found</h3>
                                            <p class="mt-2 text-gray-500">Get started by creating your first todo item
                                            </p>
                                            <button onclick="openModal()"
                                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Create Todo
                                            </button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create/Edit Todo Modal -->
                <div id="todoModal"
                    class="modal-overlay fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                    <div class="modal-content relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="absolute top-3 right-3">
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-3">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Create New
                                Todo</h3>
                            <form id="todoForm" onsubmit="handleTodoSubmit(event)" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="todoId" name="todoId">
                                <div class="mb-4">
                                    <label for="title"
                                        class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" name="title" id="title" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="4" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700">Image
                                        (optional)</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="closeModal()"
                                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" id="submitButton"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                        Create Todo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Comment Modal -->
                <div id="commentModal"
                    class="modal-overlay fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                    <div class="modal-content relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="absolute top-3 right-3">
                            <button onclick="closeCommentModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-3">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Add Comment</h3>
                            <form id="commentForm" onsubmit="handleCommentSubmit(event)" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="commentTodoId" name="todo_id">
                                <div class="mb-4">
                                    <label for="content"
                                        class="block text-sm font-medium text-gray-700">Comment</label>
                                    <textarea name="content" id="content" rows="4" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="comment_image" class="block text-sm font-medium text-gray-700">Image
                                        (optional)</label>
                                    <input type="file" name="image" id="comment_image" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="closeCommentModal()"
                                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                        Add Comment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    let currentTodoId = null;

                    function openModal(todo = null) {
                        const modal = document.getElementById('todoModal');
                        const modalContent = modal.querySelector('.modal-content');
                        const form = document.getElementById('todoForm');
                        const titleInput = document.getElementById('title');
                        const descriptionInput = document.getElementById('description');
                        const todoIdInput = document.getElementById('todoId');
                        const modalTitle = document.getElementById('modalTitle');
                        const submitButton = document.getElementById('submitButton');

                        // Reset form
                        form.reset();

                        if (todo) {
                            modalTitle.textContent = 'Edit Todo';
                            submitButton.textContent = 'Update Todo';
                            titleInput.value = todo.title;
                            descriptionInput.value = todo.description;
                            todoIdInput.value = todo.id;
                            form.setAttribute('method', 'PUT');
                        } else {
                            modalTitle.textContent = 'Create New Todo';
                            submitButton.textContent = 'Create Todo';
                            todoIdInput.value = '';
                            form.setAttribute('method', 'POST');
                        }

                        modal.classList.remove('hidden');
                        setTimeout(() => {
                            modal.classList.add('show');
                            modalContent.classList.add('modal-enter');
                        }, 10);
                    }

                    function closeModal() {
                        const modal = document.getElementById('todoModal');
                        const modalContent = modal.querySelector('.modal-content');

                        modalContent.classList.remove('modal-enter');
                        modalContent.classList.add('modal-leave');
                        modal.classList.remove('show');

                        setTimeout(() => {
                            modal.classList.add('hidden');
                            modalContent.classList.remove('modal-leave');
                            document.getElementById('todoForm').reset();
                        }, 300);
                    }

                    async function handleTodoSubmit(event) {
                        event.preventDefault();
                        const form = event.target;
                        const formData = new FormData(form);
                        console.log(formData)
                        const todoId = document.getElementById('todoId').value;
                        const method = 'POST';
                        const url = todoId ? `/todos/${todoId}` : '/todos';

                        try {
                            const response = await axios({
                                method: method,
                                url: url,
                                data: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            if (response.data.todo) {
                                if (todoId) {
                                    updateTodoInDOM(response.data.todo);
                                } else {
                                    addTodoToDOM(response.data.todo);
                                }
                            }

                            closeModal();
                        } catch (error) {
                            console.error('Error:', error);
                            alert(error.response?.data?.message || 'An error occurred');
                        }
                    }

                    function updateTodoInDOM(todo) {
                        const todoElement = document.querySelector(`.todo-card[data-id="${todo.id}"]`);
                        if (todoElement) {
                            todoElement.querySelector('h3').textContent = todo.title;
                            todoElement.querySelector('p').textContent = todo.description;
                            const statusBadge = todoElement.querySelector('.status-badge');
                            statusBadge.className =
                                `status-badge px-3 py-1 rounded-full text-sm ${todo.completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`;
                            statusBadge.textContent = todo.completed ? 'Completed' : 'Pending';
                        }
                    }

                    function addTodoToDOM(todo) {
                        const todoList = document.getElementById('todoList');
                        const todoCard = document.createElement('div');
                        todoCard.className =
                            'todo-card bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1';
                        todoCard.setAttribute('data-id', todo.id);
                        todoCard.setAttribute('data-status', 'pending');

                        todoCard.innerHTML = `
                <div class="p-6">
                    ${todo.image_path ? `
                                                        <div class="mb-4">
                                                            <img src="/storage/${todo.image_path}" alt="Todo image" class="w-full h-48 object-cover rounded-lg">
                                                        </div>
                                                    ` : ''}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">${todo.title}</h3>
                        <button onclick="toggleComplete(${todo.id})"
                            class="status-badge px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                            Pending
                        </button>
                    </div>
                    <p class="text-gray-600 mb-4">${todo.description}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            By ${todo.user.name} • Just now
                        </span>
                        <div class="flex space-x-2">
                            <button onclick="editTodo(${todo.id})" class="text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="deleteTodo(${todo.id})" class="text-red-600 hover:text-red-800 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

                        // Check if there's an empty state message and remove it
                        const emptyState = document.querySelector('.empty-state');
                        if (emptyState) {
                            emptyState.remove();
                        }

                        todoList.insertBefore(todoCard, todoList.firstChild);
                        updateSidebarStats('add');
                    }

                    async function toggleComplete(todoId) {
                        try {
                            const response = await axios.post(`/todos/${todoId}/toggle-complete`, {}, {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            if (response.data.todo) {
                                updateTodoInDOM(response.data.todo);
                                updateSidebarStats('toggleComplete', response.data.todo.completed);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Failed to update todo status');
                        }
                    }

                    async function approveComment(commentId, todoId) {
                        try {
                            const response = await axios.post(`/comments/${commentId}/approve`, {}, {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            if (response.data.comment) {
                                // Update all comments in this todo
                                const comments = document.querySelectorAll(`[data-comment-id]`);
                                comments.forEach(commentElement => {
                                    const isApprovedComment = commentElement.getAttribute('data-comment-id') == commentId;

                                    // Remove existing status classes
                                    commentElement.classList.remove('border-yellow-500', 'border-green-500',
                                        'border-red-500');

                                    // Add appropriate status class
                                    if (isApprovedComment) {
                                        commentElement.classList.add('border-green-500');
                                    } else {
                                        commentElement.classList.add('border-red-500');
                                    }

                                    // Update status icon
                                    const statusIcon = commentElement.querySelector('.flex.items-center svg');
                                    if (statusIcon) {
                                        if (isApprovedComment) {
                                            statusIcon.outerHTML = `
                                    <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                `;
                                        } else {
                                            statusIcon.outerHTML = `
                                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                `;
                                        }
                                    }

                                    // Remove all approve buttons after approval
                                    const approveButton = commentElement.querySelector('.approve-button');
                                    if (approveButton) {
                                        approveButton.remove();
                                    }
                                });

                                // Update todo status
                                if (response.data.todo) {
                                    const todoElement = document.querySelector(`.todo-card[data-id="${todoId}"]`);
                                    if (todoElement) {
                                        const statusBadge = todoElement.querySelector('.status-badge');
                                        if (statusBadge) {
                                            statusBadge.className =
                                                'status-badge px-3 py-1 rounded-full text-sm bg-green-100 text-green-800';
                                            statusBadge.textContent = 'Completed';
                                        }
                                    }
                                }
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Failed to approve comment');
                        }
                    }

                    async function deleteTodo(id) {
                        if (!confirm('Are you sure you want to delete this todo?')) return;

                        const todoCard = document.querySelector(`.todo-card[data-id="${id}"]`);
                        const isCompleted = todoCard.dataset.status === 'completed';
                        todoCard.classList.add('deleting');

                        try {
                            await axios.delete(`/todos/${id}`, {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            setTimeout(() => {
                                todoCard.remove();
                                updateSidebarStats('delete', isCompleted);
                            }, 300);
                        } catch (error) {
                            console.error('Error:', error);
                            todoCard.classList.remove('deleting');
                            alert('Failed to delete todo');
                        }
                    }

                    async function editTodo(id) {
                        try {
                            const response = await axios.get(`/todos/${id}`);
                            if (response.data) {
                                openModal(response.data);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Failed to load todo');
                        }
                    }

                    // Search functionality
                    const searchInput = document.getElementById('search');
                    searchInput.addEventListener('input', (e) => {
                        const searchTerm = e.target.value.toLowerCase();
                        const todos = document.querySelectorAll('.todo-card');

                        todos.forEach(todo => {
                            const title = todo.querySelector('h3').textContent.toLowerCase();
                            const description = todo.querySelector('p').textContent.toLowerCase();
                            const isVisible = title.includes(searchTerm) || description.includes(searchTerm);
                            todo.classList.toggle('hidden', !isVisible);
                        });
                    });

                    function openCommentModal(todoId) {
                        const modal = document.getElementById('commentModal');
                        const modalContent = modal.querySelector('.modal-content');
                        document.getElementById('commentTodoId').value = todoId;
                        document.getElementById('commentForm').reset();

                        modal.classList.remove('hidden');
                        setTimeout(() => {
                            modal.classList.add('show');
                            modalContent.classList.add('modal-enter');
                        }, 10);
                    }

                    function closeCommentModal() {
                        const modal = document.getElementById('commentModal');
                        const modalContent = modal.querySelector('.modal-content');

                        modalContent.classList.remove('modal-enter');
                        modalContent.classList.add('modal-leave');
                        modal.classList.remove('show');

                        setTimeout(() => {
                            modal.classList.add('hidden');
                            modalContent.classList.remove('modal-leave');
                            document.getElementById('commentForm').reset();
                        }, 300);
                    }

                    async function handleCommentSubmit(event) {
                        event.preventDefault();
                        const form = event.target;
                        const formData = new FormData(form);
                        const todoId = document.getElementById('commentTodoId').value;

                        try {
                            const response = await axios.post(`/todos/${todoId}/comments`, formData, {
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'multipart/form-data'
                                }
                            });

                            if (response.data.comment) {
                                window.location.reload(); // We'll reload for now to show the new comment
                            }
                            closeCommentModal();
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Failed to add comment');
                        }
                    }

                    function filterTodos(filter) {
                        const todos = document.querySelectorAll('.todo-card');
                        const filterPills = document.querySelectorAll('.filter-pill');

                        // Update active filter pill
                        filterPills.forEach(pill => {
                            pill.classList.remove('active');
                            if (pill.textContent.toLowerCase().includes(filter)) {
                                pill.classList.add('active');
                            }
                        });

                        // Filter todos
                        todos.forEach(todo => {
                            const status = todo.dataset.status;
                            if (filter === 'all') {
                                todo.classList.remove('hidden');
                            } else {
                                todo.classList.toggle('hidden', status !== filter);
                            }
                        });

                        // Show/hide empty state
                        const visibleTodos = document.querySelectorAll('.todo-card:not(.hidden)');
                        const emptyState = document.querySelector('.empty-state');
                        if (emptyState) {
                            emptyState.classList.toggle('hidden', visibleTodos.length > 0);
                        }
                    }

                    function updateSidebarStats(action, isCompleted = false) {
                        const totalCounter = document.getElementById('totalTodosCount');
                        const completedCounter = document.getElementById('completedTodosCount');

                        if (action === 'add') {
                            totalCounter.textContent = parseInt(totalCounter.textContent) + 1;
                        } else if (action === 'delete') {
                            totalCounter.textContent = parseInt(totalCounter.textContent) - 1;
                            if (isCompleted) {
                                completedCounter.textContent = parseInt(completedCounter.textContent) - 1;
                            }
                        } else if (action === 'toggleComplete') {
                            const currentCompleted = parseInt(completedCounter.textContent);
                            completedCounter.textContent = isCompleted ? currentCompleted + 1 : currentCompleted - 1;
                        }
                    }
                </script>
            </x-app-layout>
