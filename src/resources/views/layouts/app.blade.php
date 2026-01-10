<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sound App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/wavesurfer.js@7"></script>
    <style>
        .animated-bg {
            background: linear-gradient(-45deg, #d5f4fcff, #a1e0edff, #79e2f2ff, #65dedeff);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            min-height: 100vh;
        }

        @keyframes gradientAnimaiton {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class="animated-bg text-gray-800 font-sans antialiased">

    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-indigo-600">Sound App</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                    {{-- ログイン中 --}}
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 hover:opacity-75 transition" title="プロフィールを編集">
                        @if(Auth::user()->icon_path)
                        <img src="{{ asset('storage/' . Auth::user()->icon_path) }}"
                            alt="Avatar"
                            class="w-8 h-8 rounded-full object-cover border border-gray-200">
                        @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        @endif
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}さん</span>
                    </a>
                    <a href="{{ route('sounds.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        投稿する
                    </a>

                    {{-- ログアウト --}}
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium ml-2">
                            ログアウト
                        </button>
                    </form>

                    <a href="{{ route('profile.delete.confirm') }}" class="text-red-400 hover:text-red-600 font-medium ml-2">
                        退会
                    </a>
                    @else
                    {{-- 未ログイン --}}
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium text-sm">
                        ログイン
                    </a>
                    <a href="{{ route('register') }}" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        ユーザー登録
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center text-gray-500 text-sm py-8 mt-8">
        &copy; 2025 Sound App Portfolio.
    </footer>

</body>

</html>