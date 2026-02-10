<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '匿名掲示板')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-red-600 text-white shadow">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('threads.index') }}" class="text-xl font-bold">匿名掲示板</a>
            <a href="{{ route('threads.create') }}"
                class="bg-white text-red-800 px-4 py-1 rounded text-sm font-semibold hover:bg-gray-100">新規スレッド</a>
        </div>
    </header>
    <main class="max-w-4xl mx-auto px-4 py-6">
        @yield('content')
    </main>
</body>

</html>
