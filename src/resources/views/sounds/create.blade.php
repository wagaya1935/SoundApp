@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">新規曲投稿</h2>

            <form action="/sounds" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- タイトル入力 --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">タイトル</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2"
                           placeholder="例：癒しのピアノ曲">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ハッシュタグ</label>
                    <input type="text" name="tags" class="w-full border-gray-300 rounded-md shadow-sm border p-2" placeholder="例：初投稿 #ロック">
                    <p class="text-xs text-gray-500 mt-1">スペースで区切って複数入力可能</p>
                </div>

                {{-- ファイル選択 --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">曲ファイル</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="sound_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>ファイルを選択</span>
                                    <input id="sound_file" name="sound_file" type="file" class="sr-only" accept="audio/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">MP3, WAV, M4A up to 10MB</p>
                        </div>
                    </div>
                    {{-- 選択したファイル名を表示するための簡易スクリプト --}}
                    <p id="file-name" class="text-sm text-gray-500 mt-2 text-center"></p>
                    
                    @error('voice_file')
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
        document.getElementById('sound_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'ファイルが選択されていません';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
@endsection