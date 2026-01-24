@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center">ユーザー登録</h2>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">お名前</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 rounded">
            @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded">
            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">パスワード</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
            @error('password') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">パスワード（確認）</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
        </div>
        <div class="flex justify-center">
            <div class="cf-turnstile" data-sitekey="0x4AAAAAACOrKLnAzktYj3RK"></div>
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700">登録</button>
    </form>
</div>
@endsection