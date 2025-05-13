<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard -Users</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .no-transition * {
            transition: none !important;
        }

        [x-cloak] {
            display: none !important;
        }

        body::-webkit-scrollbar {
            display: none;
            /* untuk Chrome, Safari, Edge */
        }
    </style>
</head>

<body class="bg-blue-200 select-none">
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="fixed top-4 right-4 bg-green-500 text-white px-3 py-1 rounded shadow-lg z-50 flex">
            {{ session('success') }}
            <svg class="text-white my-auto ml-1 size-5 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
    @endif

    @include('components.sidebar')
    <div x-data="{ showUserModal: false }" id="contentContainer"
        class="flex-1 no-transition bg-blue-200 duration-300 ease-in-out">
        <div class="flex w-full bg-white mb-4 py-3 px-4">
            <h1
                class="text-2xl font-bold bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                Product</h1>
            <h1
                class="ml-2 font-bold select-none text-2xl bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                Management</h1>
        </div>

        <div x-data="{
            showAddModal: false,
            showConfirmDelete: false,
            deleteUrl: '',
            confirmDelete(url) {
                this.deleteUrl = url;
                this.showConfirmDelete = true;
            },
            submitDelete() {
                this.$refs.deleteForm.action = this.deleteUrl;
                this.$refs.deleteForm.submit();
            },
        }">
            <div class="flex justify-end mb-3">
                <button @click="showUserModal = true"
                    class="bg-blue-500 rounded-md mr-2 px-2 py-1 items-center text-white hover:bg-blue-700 focus:outline-none flex flex-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4 md:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="text-sm font-medium leading-6 text-white ml-1" id="modal-title">
                        Add New Admin
                    </h3>
                </button>
            </div>

            <!-- Modal Tambah data Admin -->
            <div x-show="showUserModal" x-transition x-transition.scale.opacity x-transition.duration.100ms
                class="fixed inset-0 z-50 overflow-y-auto " aria-labelledby="modal-title" role="dialog"
                aria-modal="true" style="display: none;">

                <!-- Background overlay -->
                <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" x-transition.opacity></div>

                <!-- Modal content -->
                <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0 ">
                    <div @click.away="showUserModal = false"
                        class="relative bg-white rounded-lg shadow-xl transform transition-all my-8 max-w-lg w-full p-6">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                            <h3 class="text-lg mx-auto font-medium leading-6 text-gray-900 mb-2" id="modal-title">
                                Add New Admin
                            </h3>
                            <button @click="showUserModal = false" type="button"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="space-y-5">
                                <!-- Nama -->
                                <div>
                                    <label for="name"
                                        class="text-start block text-sm font-medium text-gray-700 mb-1">Nama
                                        Lengkap</label>
                                    <input type="text" name="name" id="name" required
                                        class="block w-full border-2 border-gray-900 rounded-md py-2 px-3 sm:text-sm"
                                        placeholder="Full Name">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email"
                                        class="text-start block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="email" required
                                        class="block w-full border-2 border-gray-900 rounded-md py-2 px-3 sm:text-sm"
                                        placeholder="example@gmail.com">
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password"
                                        class="text-start block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" id="password" required
                                        class="block w-full border-2 border-gray-900 rounded-md py-2 px-3 sm:text-sm"
                                        placeholder="Minimal 6 karakter">
                                </div>

                                <!-- Hidden is_admin = 1 -->
                                <input type="hidden" name="is_admin" value="1">
                                <input type="hidden" name="email_verified" value="1">

                                <!-- Buttons -->
                                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                                    <button type="button" @click="showUserModal = false"
                                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-500 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 transition-colors duration-200">
                                        Submit
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg mx-2 rounded-lg overflow-x-auto">
                <div>
                    <table class="w-full rounded-2xl border-collapse text-[0.5rem] md:text-[0.8rem]">
                        <thead>
                            <tr class="bg-blue-100 select-none">
                                <th class="py-2 text-[0.7rem] px-4 border text-center">No</th>
                                <th class="py-2 px-4 text-[0.7rem] border text-center">Name</th>
                                <th class="py-2 px-4 text-[0.7rem] border text-center">Email</th>
                                <th class="py-2 px-4 text-[0.7rem] border text-center">Active</th>
                                <th class="py-2 px-4 text-[0.7rem] border text-center">Role</th>
                                <th class="py-2 px-4 text-[0.7rem] border text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 border-b border-gray-200 text-center text-xs md:text-[16px]">
                                        {{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 border-b border-gray-200 text-center text-xs md:text-[16px]">
                                        {{ $user->name }}</td>
                                    <td
                                        class="px-4 py-3 border-b border-gray-200 text-center truncate text-xs md:text-[16px]">
                                        {{ $user->email }}</td>
                                    <td class="px-4 py-3 border-b border-gray-200  text-center text-xs md:text-[16px]">
                                        {{ $user->email_verified == 1 ? 'Active' : 'Inactive' }}
                                    </td>
                                    <td class="px-4 py-3 border-b border-gray-200 text-xs md:text-[16px] text-center">
                                        {{ $user->is_admin == 1 ? 'Admin' : 'User' }}
                                    </td>
                                    <td class="px-4 py-3 border-b border-gray-200 text-center">
                                        <button type="button"
                                            @click="confirmDelete('{{ route('user.destroy', $user->id) }}')"
                                            class="px-2 py-1 text-red-600 hover:text-red-800 cursor-pointer">
                                            <svg class="mx-auto size-4 md:size-5" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $users->links() }}</div>
                </div>
            </div>

            <!-- Pop up konfirmasi delete -->
            <div x-show="showConfirmDelete" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showConfirmDelete = false">
                </div>

                <div @click.away="showConfirmDelete = false"
                    class="relative bg-white w-full max-w-lg rounded-xl shadow-xl overflow-hidden"
                    style="box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -5px rgba(0,0,0,0.04);">

                    <div class="bg-red-500 h-1 w-full"></div>

                    <div class="p-6">
                        <div class="flex items-start">
                            <!-- Warning icon -->
                            <div class="flex-shrink-0 mr-4">
                                <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>

                            <!-- Text content -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konfirmasi Hapus
                                </h2>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Apakah kamu yakin ingin
                                    menghapus data ini? Tindakan ini tidak bisa dibatalkan.</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <button @click="showConfirmDelete = false"
                                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:ring-2 hover:ring-red-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                                Cancel
                            </button>
                            <form x-ref="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="submitDelete"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const sidebar = document.getElementById('sidebar');
                    const toggleButton = document.getElementById('toggleSidebar');
                    const closeIcon = document.getElementById('closeIcon');
                    const openIcon = document.getElementById('openIcon');
                    const linkTexts = document.querySelectorAll('.link-text');
                    const contentContainer = document.getElementById('contentContainer');

                    function applySidebarState(isClosed) {
                        sidebar.classList.toggle('w-12', isClosed);
                        sidebar.classList.toggle('w-48', !isClosed);
                        closeIcon.classList.toggle('hidden', isClosed);
                        openIcon.classList.toggle('hidden', !isClosed);
                        contentContainer.classList.toggle('ml-48', !isClosed);
                        contentContainer.classList.toggle('ml-12', isClosed);
                        linkTexts.forEach(link => {
                            link.classList.toggle('hidden', isClosed);
                        });
                    }

                    const savedState = localStorage.getItem('sidebarClosed') === 'true';
                    applySidebarState(savedState);

                    // Setelah state diterapkan, hilangkan no-transition biar gak animasi pas load
                    setTimeout(() => {
                        sidebar.classList.remove('no-transition');
                        contentContainer.classList.remove('no-transition');
                    }, 50);

                    toggleButton.addEventListener('click', () => {
                        const isClosed = sidebar.classList.contains('w-12');
                        applySidebarState(!isClosed);
                        localStorage.setItem('sidebarClosed', !isClosed);
                    });
                });
            </script>
        </div>
</body>

</html>
