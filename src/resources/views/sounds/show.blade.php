@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- 戻るボタン --}}
    <div class="mb-4">
        <a href="{{ route('sounds.index') }}" class="text-indigo-600 hover:underline">← 一覧に戻る</a>
    </div>

    {{-- 投稿詳細（一覧ページのデザインをベースに作成） --}}
    <div class="bg-gray-900/60 backdrop-blur-md rounded-2xl shadow-2xl border border-white/10 mb-8 overflow-hidden">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $sound->title }}</h1>

            <div class="flex items-center justify-between mb-6">
                <p class="text-gray-300">
                    投稿者: <span class="font-bold">{{ $sound->user->name }}</span>
                </p>
                <p class="text-sm text-gray-300">
                    投稿日: {{ $sound->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                </p>
            </div>

            {{-- プレーヤー --}}
            <div class="bg-white/5 rounded-2xl p-6 border border-white/5 mb-8">
                <div class="flex items-center gap-4">
                    <button id="play-btn-{{ $sound->id }}" 
                            class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-full w-16 h-16 flex items-center justify-center flex-shrink-0 transition-all hover:scale-105 shadow-xl shadow-indigo-600/30">
                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1">
                            play_arrow
                        </span>
                    </button>
                    <div id="waveform-{{ $sound->id }}" class="flex-1"></div>
                </div>
            </div>

            {{-- タグ --}}
            <div class="flex flex-wrap gap-2">
                @foreach($sound->tags as $tag)
                <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- コメント機能エリア --}}
    <div class="bg-gray-900/60 backdrop-blur-md rounded-2xl shadow-2xl border border-white/10 p-8">
        <h3 class="text-xl font-bold text-white mb-4 border-b pb-2">
            コメント・アドバイス ({{ $sound->comments->count() }})
        </h3>

        {{-- コメント一覧 --}}
        <div class="mb-8 space-y-4">
            @forelse($sound->comments as $comment)
            <div class="border-b pb-4">
                <div class="flex justify-between items-center mb-1">
                    <strong class="text-gray-800">{{ $comment->user->name }}</strong>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        @auth
                        @if(auth()->id() === $comment->user_id)
                        <form action="{{ route('comments.destroy', ['sound' => $sound, 'comment' => $comment]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold">削除</button>
                        </form>
                        @endif
                        @endauth
                    </div>
                </div>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->body }}</p>
            </div>
            @empty
            <p class="text-gray-300 text-center py-4">まだコメントはありません。一番乗りのコメントを投稿しましょう！</p>
            @endforelse
        </div>

        {{-- コメント投稿フォーム --}}
        @auth
        <form action="{{ route('comments.store', $sound->id) }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-2">
                <label for="body" class="block text-sm font-medium text-gray-300 mb-1">コメントを投稿する</label>
                <textarea name="body" id="body" rows="3"
                    class="w-full block bg-gray-900/90 border-2 border-white/60 rounded-xl shadow-inner p-4 text-white placeholder-gray-500 transition-all resize-y focus:outline-none focus:border-white focus:ring-0"
                    placeholder="素晴らしい曲ですね！..." required></textarea>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-10 py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/30 active:scale-95">
                    <span class="material-symbols-outlined text-sm">send</span>
                    送信する
                </button>
            </div>
        @else
        <div class="bg-gray-100 p-4 rounded text-center">
            <p class="text-gray-400">コメントを投稿するには <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">ログイン</a> してください。</p>
        </div>
        @endauth
    </div>
</div>

<script src="https://unpkg.com/wavesurfer.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wavesurfer = WaveSurfer.create({
            container: '#waveform-{{ $sound->id }}',
            waveColor: '#A5B4FC',
            progressColor: '#4F46E5',
            barWidth: 3,
            barRadius: 3,
            cursorWidth: 1,
            height: 64,
            barGap: 3,
            url: "{{ asset('storage/' . $sound->file_path) }}",
        });

        const playBtn = document.getElementById('play-btn-{{ $sound->id }}');
        const icon = playBtn.querySelector('.material-symbols-outlined');

        playBtn.addEventListener('click', function() {
            wavesurfer.playPause();
        });

        wavesurfer.on('play', function() {
            icon.textContent = 'pause';
        });

        wavesurfer.on('pause', function() {
            icon.textContent = 'play_arrow';
        });

        wavesurfer.on('finish', function() {
            icon.textContent = 'play_arrow';
            wavesurfer.seekTo(0);
        });
    });
</script>
@endsection