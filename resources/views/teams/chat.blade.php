<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Tim - {{ $team->name }} | Minderly</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            color: #1f2937;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 16px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0 0 6px;
            font-size: 24px;
        }

        .header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .back {
            color: #4f46e5;
            text-decoration: none;
            font-weight: bold;
            white-space: nowrap;
        }

        .chat-card {
            overflow: hidden;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.04);
        }

        .chat-heading {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .chat-heading h2 {
            margin: 0 0 5px;
            font-size: 18px;
        }

        .chat-heading p {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }

        .messages {
            height: 430px;
            overflow-y: auto;
            padding: 20px;
            background: #fafbff;
        }

        .message {
            margin-bottom: 18px;
            max-width: 80%;
        }

        .message.mine {
            margin-left: auto;
        }

        .sender {
            margin-bottom: 5px;
            color: #6b7280;
            font-size: 12px;
        }

        .bubble {
            padding: 11px 14px;
            background: #e8ebf3;
            border-radius: 12px 12px 12px 3px;
            overflow-wrap: anywhere;
            white-space: pre-wrap;
            line-height: 1.5;
        }

        .mine .bubble {
            background: #4f46e5;
            color: white;
            border-radius: 12px 12px 3px 12px;
        }

        .time {
            margin-top: 5px;
            color: #9ca3af;
            font-size: 11px;
        }

        .mine .time {
            text-align: right;
        }

        .empty {
            padding: 80px 15px;
            color: #9ca3af;
            text-align: center;
        }

        .pagination {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination nav > div:last-child {
            display: flex;
            justify-content: center;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 6px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            text-decoration: none;
            color: #4f46e5;
            font-size: 13px;
        }

        .notice {
            margin-bottom: 15px;
            padding: 12px 15px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
        }

        .errors {
            margin-bottom: 15px;
            padding: 12px 15px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
        }

        .composer {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            padding: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .composer textarea {
            flex: 1;
            min-width: 0;
            min-height: 45px;
            max-height: 130px;
            resize: vertical;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font: inherit;
            outline: none;
        }

        .composer textarea:focus {
            border-color: #6366f1;
        }

        .composer button {
            padding: 13px 20px;
            border: none;
            border-radius: 10px;
            background: #4f46e5;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .composer button:hover {
            background: #4338ca;
        }

        @media (max-width: 600px) {
            .container {
                margin: 18px auto;
            }

            .header h1 {
                font-size: 20px;
            }

            .messages {
                height: 55vh;
                min-height: 300px;
            }

            .message {
                max-width: 92%;
            }

            .composer {
                padding: 10px;
            }

            .composer button {
                padding: 13px 12px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <div>
                <h1>Chat Tim</h1>
                <p>{{ $team->name }}</p>
            </div>

            <a class="back" href="{{ route('teams.show', $team->id) }}">
                ← Kembali ke Tim
            </a>
        </header>

        @if (session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="chat-card">
            <div class="chat-heading">
                <h2>Percakapan Tim</h2>
                <p>Pesan hanya dapat diakses oleh pemilik dan anggota tim.</p>
            </div>

            <div class="messages" id="messages">
                @forelse ($messages as $message)
                    @php
                        $isMine = (int) $message->user_id
                            === (int) auth()->user()->id_pengguna;
                    @endphp

                    <article class="message {{ $isMine ? 'mine' : '' }}">
                        <div class="sender">
                            {{ $isMine ? 'Kamu' : ($message->pengguna->nama ?? $message->pengguna->name ?? $message->pengguna->email ?? 'Anggota') }}
                        </div>

                        <div class="bubble">{{ $message->message }}</div>

                        <div class="time">
                            {{ $message->created_at->format('d M Y, H:i') }}
                        </div>
                    </article>
                @empty
                    <div class="empty">
                        <p>Belum ada pesan di tim ini.</p>
                        <p>Jadilah yang pertama memulai percakapan!</p>
                    </div>
                @endforelse
            </div>

            @if ($messages->hasPages())
                <div class="pagination">
                    {{ $messages->links() }}
                </div>
            @endif

            <form
                class="composer"
                action="{{ route('teams.chat.store', $team->id) }}"
                method="POST"
            >
                @csrf

                <textarea
                    name="message"
                    placeholder="Tulis pesan untuk tim..."
                    maxlength="5000"
                    required
                >{{ old('message') }}</textarea>

                <button type="submit">Kirim</button>
            </form>
        </section>
    </main>

    <script>
        const messages = document.getElementById('messages');

        if (messages && !window.location.search.includes('page=')) {
            messages.scrollTop = messages.scrollHeight;
        }
    </script>
</body>
</html>