@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-700">みんなの投稿一覧</h2>
    </div>

    @if ($sounds->isEmpty())
        <div class="text-center py-20 bg-white rounded-lg shadow">
            <p class="text-gray-500 text-lg">まだ投稿がありません。</p>
            <p class="text-gray-400 mt-2">最初の投稿者になりましょう！</p>
        </div>
    @else
        {{-- グリッドレイアウト: PCでは3列、タブレットは2列、スマホは1列 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($sounds as $sound)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-5">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $voice->title }}</h3>
                            
                            {{-- 削除ボタン（フォーム） --}}
                            <form action="{{ route('sounds.destroy', $sound->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-semibold">
                                    削除
                                </button>
                            </form>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-1 mb-4">
                            {{ $sound->created_at->format('Y/m/d H:i') }}
                        </p>

                        <div class="bg-gray-50 rounded-md p-2">
                            <audio controls class="w-full h-8" src="{{ asset('storage/' . $sound->file_path) }}">
                            </audio>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection