<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
</head>
<body>
    <h1>Logs del sistema</h1>
    
    <form method="POST" action="{{ route('logs.clear') }}" style="margin-bottom: 1em;">
        @csrf
        <button type="submit" style="background: #e53e3e; color: white; padding: 0.5em 1em; border: none; border-radius: 4px; cursor: pointer;">Borrar todos los logs</button>
    </form>

    <div style="white-space: pre-wrap; font-family: monospace;">
        {!! $logs !!}
    </div>

    <script>
        setInterval(function() {
            fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    // Extrae solo el contenido del div de logs
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newLogs = doc.querySelector('div[style*="white-space: pre-wrap"]');
                    if (newLogs) {
                        document.querySelector('div[style*="white-space: pre-wrap"]').innerHTML = newLogs.innerHTML;
                    }
                });
        }, 2000);
    </script>
</body>
</html>