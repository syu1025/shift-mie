<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LINEメッセージ一覧</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">LINEメッセージ一覧</h1>

        <div class="bg-white rounded-lg shadow-lg p-6">
            @if($messages->isEmpty())
                <p class="text-gray-500">メッセージはまだありません。</p>
            @else
                <div class="space-y-4">
                    @foreach($messages as $message)
                        <div class="border-b pb-4">
                            <div class="flex items-center mb-2">
                                <span class="font-semibold">{{ $message->user_name }}</span>
                                <span class="text-gray-500 text-sm ml-2">
                                    {{ $message->created_at->format('Y/m/d H:i') }}
                                </span>
                            </div>
                            <p class="text-gray-700">{{ $message->message }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
