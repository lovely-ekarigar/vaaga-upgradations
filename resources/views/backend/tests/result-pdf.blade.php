<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Test Results - {{ $test->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        h1 { font-size: 16px; margin-bottom: 5px; }
        .meta { color: #666; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Test Results: {{ $test->title }}</h1>
    <p class="meta">Generated on {{ now()->format('d M Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Attempted</th>
                <th>Total Questions</th>
                <th>Correct</th>
                <th>Unattempted</th>
                <th>Wrong</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $k => $u)
            @if($u)
            <tr>
                <td>{{ $k + 1 }}</td>
                <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                <td>{{ $u->isAttempted ? 'Yes' : 'No' }}</td>
                <td>{{ $u->isAttempted ? $u->totalQuestion : '-' }}</td>
                <td>{{ $u->isAttempted ? $u->totalCorrect : '-' }}</td>
                <td>{{ $u->isAttempted ? $u->totalUnattempted : '-' }}</td>
                <td>{{ $u->isAttempted ? ($u->totalQuestion - $u->totalCorrect - $u->totalUnattempted) : '-' }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
