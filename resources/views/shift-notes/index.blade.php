@extends('layouts.app')

@section('title', __('shift_notes.title'))
@section('page-title')
    <div class="welcome-container">
        <p
            style="font-size: 12px; font-weight: 600; color: var(--ios-blue); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px;">
            {{ __('shift_notes.subtitle') }}
        </p>
        <span>{{ __('shift_notes.title') }}</span>
    </div>
@endsection

@section('header-actions')
    <button onclick="openAddModal()" data-no-loading
        class="px-5 py-2.5 bg-ios-blue hover:brightness-110 text-white font-semibold rounded-2xl shadow-lg shadow-blue-500/20 transition active-scale flex items-center gap-2">
        <i class="ph-bold ph-plus"></i>
        {{ __('shift_notes.add_note') }}
    </button>
@endsection

@push('styles')
    <style>
        .sticky-note {
            position: relative;
            aspect-ratio: 1/1;
            padding: 1.5rem;
            border-radius: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            flex-direction: column;
        }

        .sticky-note:hover {
            transform: scale(1.02) rotate(1deg);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            z-index: 10;
        }

        .note-yellow {
            background-color: #FEF9C3;
            border: 1px solid #FEF08A;
        }

        .note-blue {
            background-color: #DBEAFE;
            border: 1px solid #BFDBFE;
        }

        .note-pink {
            background-color: #FCE7F3;
            border: 1px solid #FBCFE8;
        }

        .note-green {
            background-color: #DCFCE7;
            border: 1px solid #BBF7D0;
        }

        .note-purple {
            background-color: #F3E8FF;
            border: 1px solid #E9D5FF;
        }

        .note-content {
            font-family: 'Handlee', 'Chalkboard SE', 'Comic Sans MS', cursive;
            font-size: 1.125rem;
            color: #4B5563;
            line-height: 1.6;
            flex-grow: 1;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        .pin-icon {
            position: absolute;
            top: -10px;
            right: 20px;
            color: #EF4444;
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* Color Pill in Modal */
        .color-pill {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 3px solid transparent;
        }

        .color-pill.selected {
            border-color: var(--ios-blue);
            transform: scale(1.1);
        }
    </style>
    <!-- Font for sticky note feel -->
    <link href="https://fonts.googleapis.com/css2?family=Handlee&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($notes as $note)
            <div onclick="openEditModal({{ $note->toJson() }})" data-no-loading
                class="sticky-note note-{{ $note->color }} @if ($note->is_pinned) shadow-lg @else shadow-sm @endif">

                @if ($note->is_pinned)
                    <div class="pin-icon">
                        <i class="ph-fill ph-push-pin"></i>
                    </div>
                @endif

                <div class="note-content">
                    {{ $note->content }}
                </div>

                <div class="mt-4 pt-3 border-t border-black/5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-6 h-6 rounded-full bg-black/5 flex items-center justify-center text-[10px] font-bold text-gray-600">
                            {{ strtoupper(substr($note->user->name, 0, 1)) }}
                        </div>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">
                            {{ $note->user->name }}
                        </span>
                    </div>
                    <span class="text-[10px] font-medium text-gray-400">
                        {{ $note->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-20 bg-white/50 backdrop-blur-md rounded-3xl border border-dashed border-gray-300 text-center">
                <i class="ph ph-note-pencil text-5xl text-gray-300 mb-4"></i>
                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ __('shift_notes.no_notes') }}</h4>
            </div>
        @endforelse
    </div>

    {{-- Modal for Add/Edit --}}
    <div id="note-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[calc(100%-2rem)] md:w-[500px] bg-white rounded-[2rem] shadow-2xl overflow-hidden transition-all scale-95 opacity-0 duration-300 transform"
            id="modal-container">
            <form id="note-form" method="POST" action="{{ route('shift-notes.store') }}">
                @csrf
                <div id="form-method"></div>

                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 id="modal-title" class="text-xl font-black text-gray-900">{{ __('shift_notes.add_note') }}</h3>
                        <button type="button" onclick="closeModal()"
                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Content --}}
                        <div>
                            <label
                                class="block text-sm font-bold text-gray-900 mb-2">{{ __('shift_notes.content') }}</label>
                            <textarea name="content" id="note-content" required rows="4"
                                class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-4 px-4 text-lg font-medium focus:ring-4 focus:ring-ios-blue/20 outline-none transition-all resize-none"
                                placeholder="{{ __('shift_notes.content_placeholder') }}"></textarea>
                        </div>

                        {{-- Colors --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">{{ __('shift_notes.color') }}</label>
                            <div class="flex gap-3">
                                <input type="hidden" name="color" id="note-color" value="yellow">
                                <div onclick="selectColor('yellow')" class="color-pill note-yellow selected"
                                    data-color="yellow"></div>
                                <div onclick="selectColor('pink')" class="color-pill note-pink" data-color="pink"></div>
                                <div onclick="selectColor('blue')" class="color-pill note-blue" data-color="blue"></div>
                                <div onclick="selectColor('green')" class="color-pill note-green" data-color="green"></div>
                                <div onclick="selectColor('purple')" class="color-pill note-purple" data-color="purple">
                                </div>
                            </div>
                        </div>

                        {{-- Pinned --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                                    <i class="ph-bold ph-push-pin text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ __('shift_notes.pinned') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('shift_notes.is_pinned') }}</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_pinned" id="note-pinned" class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ios-blue">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8 bg-gray-50 flex gap-3">
                    <button type="button" id="delete-btn" onclick="deleteNote()"
                        class="hidden px-6 py-3 bg-red-100 text-red-600 font-bold rounded-2xl hover:bg-red-200 transition">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                    <button type="submit"
                        class="flex-1 py-3.5 bg-ios-blue text-white rounded-2xl font-black text-sm shadow-xl shadow-blue-500/20 active:scale-95 transition-all">
                        {{ __('general.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hidden Delete Form --}}
    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('note-modal');
        const container = document.getElementById('modal-container');
        const form = document.getElementById('note-form');
        const methodDiv = document.getElementById('form-method');
        let currentNoteId = null;

        function openAddModal() {
            currentNoteId = null;
            document.getElementById('modal-title').textContent = '{{ __('shift_notes.add_note') }}';
            document.getElementById('note-content').value = '';
            document.getElementById('note-pinned').checked = false;
            document.getElementById('delete-btn').classList.add('hidden');
            selectColor('yellow');

            form.action = '{{ route('shift-notes.store') }}';
            methodDiv.innerHTML = '';

            showModal();
        }

        function openEditModal(note) {
            currentNoteId = note.id;
            document.getElementById('modal-title').textContent = '{{ __('shift_notes.edit_note') }}';
            document.getElementById('note-content').value = note.content;
            document.getElementById('note-pinned').checked = !!note.is_pinned;
            document.getElementById('delete-btn').classList.remove('hidden');
            selectColor(note.color);

            form.action = `/shift-notes/${note.id}`;
            methodDiv.innerHTML = '@method('PUT')';

            showModal();
        }

        function showModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function selectColor(color) {
            document.getElementById('note-color').value = color;
            document.querySelectorAll('.color-pill').forEach(pill => {
                pill.classList.remove('selected');
                if (pill.dataset.color === color) pill.classList.add('selected');
            });
        }

        function deleteNote() {
            if (currentNoteId && confirm('{{ __('shift_notes.delete_confirm') }}')) {
                const deleteForm = document.getElementById('delete-form');
                deleteForm.action = `/shift-notes/${currentNoteId}`;
                deleteForm.submit();
            }
        }
    </script>
@endpush
