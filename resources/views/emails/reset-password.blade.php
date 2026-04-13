<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
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
        p{font-size:14px;color:#6b6860;line-height:1.7;margin-bottom:16px}
        .btn{display:inline-block;background:#0a6b6e;color:white!important;text-decoration:none;font-size:14px;font-weight:600;padding:14px 32px;border-radius:8px;margin:8px 0 24px}
        .divider{height:1px;background:#e2e0dc;margin:20px 0}
        .url-box{background:#f5f4f0;border:1px solid #e2e0dc;border-radius:8px;padding:12px 16px;font-size:12px;color:#6b6860;word-break:break-all;margin-bottom:16px}
        .expire-note{display:flex;align-items:center;gap:8px;font-size:13px;color:#6b6860;background:#fef3e2;border:1px solid #f6cc7a;border-radius:8px;padding:12px 16px}
        .expire-note svg{flex-shrink:0;color:#d97706}
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
            <h1>Restablecer contraseña</h1>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón para continuar:</p>

            <a href="{{ $url }}" class="btn">Restablecer contraseña →</a>

            <div class="expire-note">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
                Este enlace expirará en <strong>&nbsp;{{ $count }} minutos</strong>.
            </div>

            <div class="divider"></div>

            <p style="font-size:13px">Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
            <div class="url-box">{{ $url }}</div>

            <p style="font-size:13px">Si no solicitaste este cambio, ignora este correo. Tu contraseña no será modificada.</p>
        </div>

        <footer>© {{ date('Y') }} StarKit &nbsp;·&nbsp; Este correo fue enviado automáticamente.</footer>
    </div>
</div>
</body>
</html>
