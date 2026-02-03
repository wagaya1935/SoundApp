@extends('layouts.app')

@section('content')
<div class="bg-gray-900/60 backdrop-blur-md rounded-lg shadow-xl p-8 border border-white/10 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">プロフィール編集</h2>
        <a href="{{ route('profile.delete.confirm') }}" class="text-red-500 hover:text-red-700 text-sm font-medium">
            退会
        </a>
    </div>

    @if (session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-6 text-center">
            @if($user->icon_path)
                <img src="{{ asset('storage/' . $user->icon_path) }}" 
                    alt="Current Icon" 
                    class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-white/10">
            @else
                <div class="flex justify-center">
                    <span class="material-symbols-outlined text-gray-300 text-[96px]" 
                        style="font-variation-settings: 'FILL' 1">
                        account_circle
                    </span>
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="icon" class="block text-white font-bold mb-2">アイコン画像</label>
            <input type="file" name="icon" id="icon" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-white font-bold mb-2">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
        </div>

        <div class="mb-6">
            <label for="email" class="block text-white font-bold mb-2">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                更新する
            </button>
        </div>
    </form>

    <hr class="my-10 border-white/10">

    <div class="mt-10">
        <h3 class="text-lg font-bold text-white mb-4">パスワード変更</h3>
        
        <form action="{{ route('profile.update.password') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="current_password" class="block text-white font-bold mb-2 text-sm">現在のパスワード</label>
                <input type="password" name="current_password" id="current_password" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
            </div>

            <div class="mb-4">
                <label for="new_password" class="block text-white font-bold mb-2 text-sm">新しいパスワード</label>
                <input type="password" name="new_password" id="new_password" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
            </div>

            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-white font-bold mb-2 text-sm">新しいパスワード（確認）</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('sounds.index') }}" class="text-gray-300 hover:text-white transition mr-4">キャンセル</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition text-sm">
                    パスワードを更新
                </button>
            </div>
        </form>
    </div>
</div>
@endsection