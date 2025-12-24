@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- 戻るボタン --}}
    <div class="mb-4">
        <a href="{{ route('sounds.index') }}" class="text-indigo-600 hover:underline">← 一覧に戻る</a>
    </div>

    {{-- 投稿詳細（一覧ページのデザインをベースに作成） --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $sound->title }}</h1>

            <div class="flex items-center justify-between mb-6">
                <p class="text-gray-600">
                    投稿者: <span class="font-bold">{{ $sound->user->name }}</span>
                </p>
                <p class="text-sm text-gray-500">
                    投稿日: {{ $sound->created_at->format('Y/m/d H:i') }}
                </p>
            </div>

            {{-- プレーヤー --}}
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 mb-6">
                <div class="flex items-center gap-4">
                    <button id="play-btn-{{ $sound->id }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0 transition">
                        ▶
                    </button>
                    <div id="waveform-{{ $sound->id }}" class="w-full h-16"></div>
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
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
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
            <p class="text-gray-500 text-center py-4">まだコメントはありません。一番乗りのコメントを投稿しましょう！</p>
            @endforelse
        </div>

        {{-- コメント投稿フォーム --}}
        @auth
        <form action="{{ route('comments.store', $sound->id) }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-2">
                <label for="body" class="block text-sm font-medium text-gray-700 mb-1">コメントを投稿する</label>
                <textarea name="body" id="body" rows="3"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 border"
                    placeholder="素晴らしい曲ですね！..." required></textarea>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                送信
            </button>
        </form>
        @else
        <div class="bg-gray-100 p-4 rounded text-center">
            <p>コメントを投稿するには <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">ログイン</a> してください。</p>
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

        playBtn.addEventListener('click', function() {
            wavesurfer.playPause();
        });

        wavesurfer.on('play', function() {
            playBtn.textContent = '❚❚';
        });

        wavesurfer.on('pause', function() {
            playBtn.textContent = '▶';
        });

        wavesurfer.on('finish', function() {
            playBtn.textContent = '▶';
            wavesurfer.seekTo(0);
        });
    });
</script>
@endsection