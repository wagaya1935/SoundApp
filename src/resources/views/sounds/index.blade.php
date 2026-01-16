@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-700">みんなの投稿一覧</h2>
</div>

<div class="mb-8">
    <form action="{{ route('sounds.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}"
            class="w-full border border-gray-300 rounded-md p-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="キーワード・#タグ・名前で検索...">
        <button type="submit" class="bg-indigo-600 text-white p-2 rounded-md hover:bg-indigo-700 transition flex items-center justify-center w-12">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </button>
    </form>
</div>

@if ($sounds->isEmpty())
<div class="text-center py-20 bg-white rounded-lg shadow">
    <p class="text-gray-500 text-lg">まだ投稿がありません。</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($sounds as $sound)
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <div class="p-5">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $sound->title }}</h3>

                <div class="flex items-center gap-2">
                    {{-- いいねボタン --}}
                    @auth
                    {{-- ログインしている場合 --}}
                    <button onclick="toggleLike(this, '{{ $sound->id }}')"
                        class="flex items-center gap-1 text-sm font-medium transition hover:scale-110 focus:outline-none">

                        {{-- ハートアイコン --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            class="w-6 h-6 heart-icon {{ $sound->isLikedBy(Auth::user()) ? 'fill-red-500 text-red-500' : 'fill-none text-gray-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>

                        {{-- いいね数 --}}
                        <span class="like-count text-gray-500">{{ $sound->likes->count() }}</span>
                    </button>
                    @else
                    {{-- 未ログインの場合 --}}
                    <div class="flex items-center gap-1 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span class="text-gray-500">{{ $sound->likes->count() }}</span>
                    </div>
                    @endauth

                    {{-- 詳細・コメントページへのリンク --}}
                    <a href="{{ route('sounds.show', $sound->id) }}" class="flex items-center gap-1 text-gray-500 hover:text-indigo-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>
                        <span>{{ $sound->comments->count() }}</span>
                    </a>
                </div>

                {{-- 削除ボタン --}}
                @if(Auth::id() == $sound->user_id)
                <form action="{{ route('sounds.destroy', $sound->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-xs border border-red-200 px-2 py-1 rounded">
                        削除
                    </button>
                </form>
                @endif
            </div>

            <p class="text-xs text-gray-500 mb-4">
            <p class="mb-1">
                投稿者: <span class="font-bold">{{ $sound->user->name }}</span>
            </p>
            <p>
                投稿日: {{ $sound->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
            </p>
            </p>

            {{-- タグの表示 --}}
            <div class="mt-2 mb-3 flex flex-wrap gap-2">
                @foreach($sound->tags as $tag)
                <a href="{{ route('sounds.index', ['search' => $tag->name]) }}"
                    class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full hover:bg-gray-300 transition">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>

            {{-- 波形プレーヤーエリア --}}
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                {{-- 再生ボタンと波形を横並びにする --}}
                <div class="flex items-center gap-3">
                    {{-- 再生/一時停止ボタン --}}
                    <button id="play-btn-{{ $sound->id }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 transition">
                        ▶
                    </button>

                    {{-- 波形描画エリア --}}
                    <div id="waveform-{{ $sound->id }}" class="w-full h-12"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- 各カードごとのJavaScript設定 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. WaveSurferの初期化
            const wavesurfer = WaveSurfer.create({
                container: '#waveform-{{ $sound->id }}',
                waveColor: '#A5B4FC', // 波形の色
                progressColor: '#4F46E5', // 再生済みの色
                barWidth: 2, // 波形の棒の太さ
                barRadius: 2, // 丸み
                cursorWidth: 1,
                height: 50, // 高さ
                barGap: 2, // 隙間
                url: "{{ asset('storage/' . $sound->file_path) }}", // 音声ファイルのURL
            });

            // 2. ボタンを押した時の動作
            const playBtn = document.getElementById('play-btn-{{ $sound->id }}');

            playBtn.addEventListener('click', function() {
                // 再生中なら一時停止、停止中なら再生
                wavesurfer.playPause();
            });

            // 3. 再生状態が変わった時にボタンのアイコンを変える
            wavesurfer.on('play', function() {
                playBtn.textContent = '❚❚';
                stopOtherPlayers(wavesurfer);
            });

            wavesurfer.on('pause', function() {
                playBtn.textContent = '▶';
            });

            // 4. 再生終了時
            wavesurfer.on('finish', function() {
                playBtn.textContent = '▶';
                wavesurfer.seekTo(0); // 最初に戻す
            });

            // グローバル配列に登録（他のプレーヤーを止めるため）
            if (!window.players) window.players = [];
            window.players.push(wavesurfer);
        });
    </script>
    @endforeach
</div>

{{-- 他のプレーヤーを止めるための共通関数 --}}
<script>
    function stopOtherPlayers(currentPlayer) {
        if (window.players) {
            window.players.forEach(player => {
                if (player !== currentPlayer) {
                    player.pause();
                }
            });
        }
    }

    async function toggleLike(button, soundId) {
        try {
            const response = await fetch(`/sounds/${soundId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
            });

            if (!response.ok) {
                alert('エラーが発生しました');
                return;
            }

            const data = await response.json();
            const icon = button.querySelector('.heart-icon');
            const countSpan = button.querySelector('.like-count');

            if (data.liked) {
                icon.classList.remove('fill-none', 'text-gray-400');
                icon.classList.add('fill-red-500', 'text-red-500');
            } else {
                icon.classList.remove('fill-red-500', 'text-red-500');
                icon.classList.add('fill-none', 'text-gray-400');
            }

            countSpan.textContent = data.count;

        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>
@endif
@endsection