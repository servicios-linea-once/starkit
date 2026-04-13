<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo inicio de sesión</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f5f4f0;color:#1c1a16;padding:40px 16px}
        .wrap{max-width:520px;margin:0 auto}
        .card{background:#fafaf8;border:1px solid #e2e0dc;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .head{background:#0a6b6e;padding:28px 40px;display:flex;align-items:center;gap:10px}
        .logo-icon{width:32px;height:32px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center}
        .logo-name{font-weight:700;font-size:18px;color:white;letter-spacing:-.03em}
        .body{padding:36px 40px}
        h1{font-size:20px;font-weight:700;margin-bottom:10px;letter-spacing:-.02em}
        p{font-size:14px;color:#6b6860;line-height:1.7;margin-bottom:14px}
        .info-box{background:#f5f4f0;border:1px solid #e2e0dc;border-radius:8px;padding:18px 20px;margin:20px 0}
        .detail-row{display:flex;gap:12px;margin-bottom:8px;font-size:13px}
        .detail-row:last-child{margin-bottom:0}
        .detail-label{font-weight:600;color:#1c1a16;min-width:90px}
        .detail-value{color:#6b6860;word-break:break-all}
        .alert{display:flex;align-items:flex-start;gap:10px;background:#fef3e2;border:1px solid #f6cc7a;border-radius:8px;padding:14px 18px;font-size:13px;color:#92400e;margin-top:20px}
        .alert svg{flex-shrink:0;margin-top:1px}
        footer{background:#f5f4f0;border-top:1px solid #e2e0dc;padding:18px 40px;font-size:12px;color:#a8a59e;text-align:center}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">

        <div class="head">
            <div class="logo-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <span class="logo-name">StarKit</span>
        </div>

        <div class="body">
            <h1>Nuevo inicio de sesión</h1>
            <p>Hola <strong>{{ $user->name }}</strong>, detectamos un nuevo inicio de sesión en tu cuenta StarKit.</p>

            <div class="info-box">
                <div class="detail-row">
                    <span class="detail-label">Fecha y hora:</span>
                    <span class="detail-value">{{ $time }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Dirección IP:</span>
                    <span class="detail-value">{{ $ip }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Navegador:</span>
                    <span class="detail-value">{{ \Illuminate\Support\Str::limit($userAgent, 70) }}</span>
                </div>
            </div>

            <div class="alert">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span>Si no reconoces esta actividad, <strong>cambia tu contraseña de inmediato</strong> y contacta al soporte.</span>
            </div>
        </div>

        <footer>© {{ date('Y') }} StarKit &nbsp;·&nbsp; Notificación de seguridad automática.</footer>
    </div>
</div>
</body>
</html>
