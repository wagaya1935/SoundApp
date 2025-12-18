@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-700">みんなの投稿一覧</h2>
    </div>

    <div class="mb-8">
        <form action="{{ route('sounds.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   class="w-full border border-gray-300 rounded-md p-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="キーワードや#タグで検索...">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-bold">
                検索
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
                            
                            {{-- 削除ボタン --}}
                            <form action="{{ route('sounds.destroy', $sound->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-xs border border-red-200 px-2 py-1 rounded">
                                    削除
                                </button>
                            </form>
                        </div>
                        
                        <p class="text-xs text-gray-500 mb-4">
                            {{ $sound->created_at->format('Y/m/d H:i') }}
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

                                {{-- 波形描画エリア (ここに波形が出ます) --}}
                                <div id="waveform-{{ $sound->id }}" class="w-full h-12"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 各カードごとのJavaScript設定 --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // 1. WaveSurferの初期化
                        const wavesurfer = WaveSurfer.create({
                            container: '#waveform-{{ $sound->id }}',
                            waveColor: '#A5B4FC', // 波形の色（薄い紫）
                            progressColor: '#4F46E5', // 再生済みの色（濃い紫）
                            barWidth: 2, // 波形の棒の太さ
                            barRadius: 2, // 丸み
                            cursorWidth: 1, 
                            height: 50, // 高さ
                            barGap: 2, // 隙間
                            url: "{{ asset('storage/' . $sound->file_path) }}", // 音声ファイルのURL
                        });

                        // 2. ボタンを押した時の動作
                        const playBtn = document.getElementById('play-btn-{{ $sound->id }}');
                        
                        playBtn.addEventListener('click', function () {
                            // 再生中なら一時停止、停止中なら再生
                            wavesurfer.playPause();
                        });

                        // 3. 再生状態が変わった時にボタンのアイコンを変える
                        wavesurfer.on('play', function () {
                            playBtn.textContent = '❚❚'; // 一時停止マーク
                            stopOtherPlayers(wavesurfer);
                        });

                        wavesurfer.on('pause', function () {
                            playBtn.textContent = '▶'; // 再生マーク
                        });
                        
                        // 4. 再生終了時
                        wavesurfer.on('finish', function () {
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
        </script>
    @endif
@endsection