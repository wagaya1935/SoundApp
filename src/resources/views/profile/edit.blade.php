@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">プロフィール編集</h2>

        @if (session('massage'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('massage') }}
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
                    <img src="{{ asset('storage/' . $user->icon_path) }}" alt="Current Icon" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-gray-200">
                @else
                    <div class="w-24 h-24 rounded-full mx-auto bg-gray-300 flex items-center justify-center text-gray-500 text-2xl">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="icon" class="block text-gray-700 font-bold mb-2">アイコン画像</label>
                <input type="file" name="icon" id="icon" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">ユーザー名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold mb-2">メールアドレス</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('sounds.index') }}" class="text-gray-600 hover:underline">キャンセル</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection