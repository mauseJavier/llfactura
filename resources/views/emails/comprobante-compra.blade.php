<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hola {{ $cliente ?? 'Cliente' }}, te enviamos tu comprobante de compra</title>
</head>
<body>
    <h2>Hola {{ $cliente ?? 'Cliente' }},</h2>
    <p>{!! nl2br(e($mensaje)) !!}</p>
    <p>Adjuntamos su comprobante de compra en PDF.<br>Gracias por confiar en nosotros.<br>LLFactura.com</p>
</body>
</html>
