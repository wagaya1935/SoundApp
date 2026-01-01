@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <h2 class="text-2xl font-bold text-red-600 mb-6">退会手続き</h2>

        <p class="text-gray-700 mb-8">
            退会するとこれまでの投稿データやコメント、履歴はすべて削除され、<br>
            <strong>元に戻すことができません。</strong>
        </p>

        <p class="text-gray-600 mb-8">
            本当によろしいですか？
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('sounds.index') }}" class="bg-gray-200 hover:gray-300 text-gray-800 font-bold py-2 px-6 rounded transition">
                キャンセル
            </a>

            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded transition" onclick="return confirm('本当に退会しますか？');">
                    退会する
                </button>
            </form>
        </div>
    </div>
</div>
@endsection