@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-gray-900/60 backdrop-blur-md rounded-lg shadow-xl p-8 border border-white/10">
        <h2 class="text-2xl font-bold text-white mb-6">投稿を編集</h2>

        <form action="{{ route('sounds.update', $sound->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="block text-gray-300 mb-2">
                    タイトル
                </label>
                <input type="text" name="title" value="{{ old('title', $sound->title) }}"
                    class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="mb-6">
                <label for="tags" class="flex items-center gap-2 text-sm font-bold text-white mb-2">
                    <span class="material-symbols-outlined text-indigo-400 text-sm">
                        sell
                    </span>
                    タグ (スペース区切りで複数入力)
                </label>
                <input type="text" name="tags" id="tags" 
                    value="{{ old('tags', $sound->tags->pluck('name')->implode(' ')) }}" 
                    class="w-full bg-gray-900/80 border-2 border-white/20 rounded-xl p-4 text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition"
                    placeholder="例: 癒しのピアノ曲 #リラックス #ピアノ">
                <p class="text-[10px] text-gray-500 mt-2 ml-1">
                    ※既存のタグを消して新しいタグを入力すると更新されます。
                </p>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('sounds.index') }}" class="text-gray-400 hover:text-white transition">
                    キャンセル
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-lg font-bold transition shadow-lg shadow-indigo-600/20">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection