<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificación al Cliente</title>
</head>
<body>
    <h2>Hola {{ $cliente->razonSocial ?? $cliente->nombre ?? 'Cliente' }},</h2>
    <p>{!! nl2br(e($mensaje)) !!}</p>
    <p>Gracias por confiar en nosotros.<br>LLFactura.com</p>
</body>
</html>
