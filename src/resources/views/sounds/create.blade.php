@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10">
        <div class="bg-gray-900/60 backdrop-blur-md rounded-lg shadow-xl p-8 border border-white/10">
            <h2 class="text-2xl font-bold mb-6 text-white">新規曲投稿</h2>

            <form action="/sounds" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- タイトル入力 --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-white mb-1">タイトル</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2"
                           placeholder="例：癒しのピアノ曲">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-white mb-1">ハッシュタグ</label>
                    <input type="text" name="tags" 
                           class="w-full border-gray-300 rounded-md shadow-sm border p-2" 
                           placeholder="例：初投稿 #ロック">
                    <p class="text-xs text-gray-300 mt-1">スペースで区切って複数入力可能</p>
                </div>

                {{-- ファイル選択 --}}
                <div>
                    <label class="block text-sm font-medium text-white mb-1">曲ファイル</label>
                    <div id="drop-zone" 
                         class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-white/5 hover:border-indigo-400 transition-all cursor-pointer group">
                        <div class="space-y-2 text-center">
                            <span class="material-symbols-outlined text-5xl text-gray-400 group-hover:text-indigo-400 transition-colors">
                                upload_file
                            </span>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="sound_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>ファイルを選択</span>
                                    <input id="sound_file" name="sound_file" type="file" class="sr-only" accept="audio/*">
                                </label>
                                <p class="pl-1">またはドラッグ&ドロップ</p>
                            </div>
                            <p class="text-xs text-gray-500">MP3, WAV, M4A up to 10MB</p>
                        </div>
                    </div>
                    {{-- 選択したファイル名を表示するための簡易スクリプト --}}
                    <div id="file-info" class="hidden mt-4 flex items-center justify-center gap-2 text-sm text-indigo-300 bg-indigo-500/10 py-2 rounded-md border border-indigo-500/20">
                        <span class="material-symbols-outlined text-base">
                            music_note
                        </span>
                        <p id="file-name" class="text-sm text-gray-500 mt-2 text-center"></p>
                    </div>
                    
                    @error('sound_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ボタン --}}
                <div class="flex items-center justify-end">
                    <a href="/" class="text-sm text-gray-600 hover:text-gray-900 mr-4">キャンセル</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow-sm transition duration-150 ease-in-out">
                        投稿する
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ファイル名を表示するJS --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('sound_file');
        const fileName = document.getElementById('file-name');
        const fileInfo = document.getElementById('file-info');

        dropZone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('border-indigo-400', 'bg-white/10');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-indigo-400', 'bg-white/10');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files; 
                handleFiles(files);
            }
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                fileName.textContent = files[0].name;
                fileInfo.classList.remove('hidden'); 
            }
        }
    });
    </script>
@endsection