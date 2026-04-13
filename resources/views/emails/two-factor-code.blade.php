<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de verificación</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f5f4f0;color:#1c1a16;padding:40px 16px}
        .wrap{max-width:520px;margin:0 auto}
        .card{background:#fafaf8;border:1px solid #e2e0dc;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .head{background:#0a6b6e;padding:28px 40px;display:flex;align-items:center;gap:10px}
        .logo-icon{width:32px;height:32px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center}
        .logo-name{font-weight:700;font-size:18px;color:white;letter-spacing:-.03em}
        .body{padding:36px 40px;text-align:center}
        h1{font-size:20px;font-weight:700;margin-bottom:10px;letter-spacing:-.02em}
        p{font-size:14px;color:#6b6860;line-height:1.7;margin-bottom:20px}
        .code-wrap{margin:24px 0}
        .code-box{display:inline-block;background:#e0eeee;border:2px dashed #4f98a3;border-radius:12px;padding:22px 40px}
        .code{font-size:42px;font-weight:700;letter-spacing:14px;color:#0a6b6e;font-family:'Courier New',monospace;line-height:1}
        .expire{font-size:12px;color:#a8a59e;margin-top:10px}
        .divider{height:1px;background:#e2e0dc;margin:20px 0}
        .warn{display:flex;align-items:flex-start;gap:10px;text-align:left;background:#f5f4f0;border:1px solid #e2e0dc;border-radius:8px;padding:14px 18px;font-size:13px;color:#6b6860}
        .warn svg{flex-shrink:0;margin-top:1px;color:#0a6b6e}
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
            <h1>Código de verificación</h1>
            <p>Hola <strong>{{ $user->name }}</strong>. Ingresa el siguiente código para completar tu inicio de sesión con verificación en dos pasos.</p>

            <div class="code-wrap">
                <div class="code-box">
                    <div class="code">{{ $code }}</div>
                    <div class="expire">Válido por <strong>10 minutos</strong></div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="warn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <span>Nunca compartas este código con nadie. El equipo de StarKit jamás te lo pedirá. Si no intentaste iniciar sesión, ignora este correo.</span>
            </div>
        </div>

        <footer>© {{ date('Y') }} StarKit &nbsp;·&nbsp; No compartas este código.</footer>
    </div>
</div>
</body>
</html>
