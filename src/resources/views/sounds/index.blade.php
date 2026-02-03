@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-100">
        みんなの投稿一覧
    </h2>
</div>

<div class="mb-8">
    <form action="{{ route('sounds.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}"
            class="w-full border border-gray-300 rounded-md p-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="キーワード・#タグ・名前で検索...">
        <button type="submit" class="bg-indigo-600 text-white p-2 rounded-md hover:bg-indigo-700 transition flex items-center justify-center w-12">
            <span class="material-symbols-outlined">
                search
            </span>
        </button>
    </form>
</div>

@if ($sounds->isEmpty())
<div class="text-center py-20 bg-gray-800/50 rounded-lg shadow border border-white/10">
    <p class="text-gray-500 text-lg">
        まだ投稿がありません。
    </p>
</div>
@else
<div class="space-y-6 pb-12">
    @foreach ($sounds as $sound)
        <div class="bg-gray-900/60 backdrop-blur-sm rounded-lg shadow-xl overflow-hidden border border-white/10 hover:border-indigo-500/50 transition-all duration-300 hover:-translate-y-1">
            <a href="{{ route('sounds.show', $sound->id) }}" class="absolute inset-0 z-0">
                <span class="sr-only">
                    詳細を見る
                </span>
            </a>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-white truncate pointer-events-none">
                        {{ $sound->title }}
                    </h3>

                    <div class="relative z-10 flex items-center gap-2">
                        {{-- いいねボタン --}}
                        @auth
                        {{-- ログインしている場合 --}}
                        <button onclick="toggleLike(this, '{{ $sound->id }}')"
                            class="flex items-center gap-1 text-sm font-medium transition hover:scale-110 focus:outline-none">
                            {{-- ハートアイコン --}}
                            <span class="material-symbols-outlined heart-icon {{ $sound->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-400' }}"
                                style="font-variation-settings: 'FILL' {{ $sound->isLikedBy(Auth::user()) ? 1 : 0 }}">
                                favorite
                            </span>
                            <span class="like-count text-gray-400 text-sm">
                                {{ $sound->likes->count() }}
                            </span>
                        </button>
                        @else
                        <div class="flex items-center gap-1 text-gray-400">
                            <span class="material-symbols-outlined">
                                favorite
                            </span> 
                            <span class="text-sm">
                                {{ $sound->likes->count() }}
                            </span>
                        </div>
                        @endauth
                        
                        @if(Auth::id() == $sound->user_id)
                        <div class="relative z-10 flex items-center gap-2">
                            <a href="{{ route('sounds.edit', $sound->id) }}" class="text-indigo-400 hover:text-indigo-300 transition p-1">
                                <span class="material-symbols-outlined">
                                    edit
                                </span>
                            </a>
                            {{-- 削除ボタン --}}
                            <form action="{{ route('sounds.destroy', $sound->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-xs border border-red-200 px-2 py-1 rounded">
                                    <span class="material-symbols-outlined">
                                        delete
                                    </span>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-x-6 gap-y-2 justify-between items-center text-sm text-gray-200 mb-4 pointer-events-none">
                    <p class="pointer-events-none">
                        投稿者: <span class="font-bold text-gray-200">{{ $sound->user->name }}</span>
                    </p>
                    <p class="pointer-events-none">
                        投稿日: {{ $sound->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                    </p>
                </div>

                {{-- タグの表示 --}}
                <div class="relative z-10 mt-2 mb-3 flex flex-wrap gap-2">
                    @foreach($sound->tags as $tag)
                    <a href="{{ route('sounds.index', ['search' => $tag->name]) }}"
                        class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full hover:bg-gray-300 transition">
                        {{ $tag->name }}
                    </a>
                    @endforeach
                </div>

                {{-- 波形プレーヤーエリア --}}
                <div class="relative z-10 bg-white/5 rounded-xl p-4 border border-white/5 flex items-center gap-4">
                    {{-- 再生/一時停止ボタン --}}
                    <button id="play-btn-{{ $sound->id }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0 transition">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1">
                            play_arrow
                        </span>
                    </button>
                    
                    {{-- 波形描画エリア --}}
                    <div id="waveform-{{ $sound->id }}" class="flex-1 h-12"></div>
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
                const btnIcon = playBtn.querySelector('.material-symbols-outlined');

                playBtn.addEventListener('click', function(e) {
                    // 再生中なら一時停止、停止中なら再生
                    e.preventDefault();
                    wavesurfer.playPause();
                });

                // 3. 再生状態が変わった時にボタンのアイコンを変える
                wavesurfer.on('play', function() {
                    btnIcon.textContent = 'pause';
                    stopOtherPlayers(wavesurfer);
                });

                wavesurfer.on('pause', function() {
                    btnIcon.textContent = 'play_arrow';
                });

                // 4. 再生終了時
                wavesurfer.on('finish', function() {
                    btnIcon.textContent = 'play_arrow';
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