<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glasanje za Jelo</title>
</head>
<body>
    <div class="container">
        <h1>Glasanje za Jelo</h1>
        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        <form action="{{ url('/glasanje') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="clan_id">Član koji glasa:</label>
                <select name="clan_id" id="clan_id" required>
                    @foreach($clanovi as $clan)
                        <option value="{{ $clan->id }}" {{ $clan->id == $trenutni_clan_id ? 'selected' : '' }}>
                            {{ $clan->ime }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="jelo_id">Odaberi Jelo:</label>
                <select name="jelo_id" id="jelo_id" required>
                    @foreach($jela as $jelo)
                        <option value="{{ $jelo->id }}">{{ $jelo->naziv }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Glasaj</button>
            </div>
        </form>
    </div>
</body>
</html>
