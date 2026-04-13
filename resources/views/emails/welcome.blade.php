<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a StarKit</title>
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
        .badge{display:inline-flex;align-items:center;gap:8px;background:#e0eeee;color:#0a6b6e;border-radius:6px;padding:8px 14px;font-size:13px;font-weight:500;margin-bottom:20px}
        .btn{display:inline-block;background:#0a6b6e;color:white!important;text-decoration:none;font-size:14px;font-weight:600;padding:12px 28px;border-radius:8px;margin:6px 0 20px}
        .divider{height:1px;background:#e2e0dc;margin:20px 0}
        .detail-row{display:flex;gap:12px;margin-bottom:8px;font-size:13px}
        .detail-label{font-weight:600;color:#1c1a16;min-width:70px}
        .detail-value{color:#6b6860}
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
            <div class="badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Cuenta creada exitosamente
            </div>

            <h1>¡Hola, {{ $user->name }}!</h1>
            <p>Tu cuenta en <strong>StarKit</strong> ha sido creada correctamente. Ya puedes acceder a la plataforma y disfrutar de todos los recursos disponibles.</p>

            <a href="{{ route('dashboard') }}" class="btn">Ir al dashboard →</a>

            <div class="divider"></div>

            <div class="detail-row">
                <span class="detail-label">Correo:</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Rol:</span>
                <span class="detail-value">{{ $user->getRoleNames()->first() ?? 'Usuario' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Registro:</span>
                <span class="detail-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>

            <div class="divider"></div>
            <p style="font-size:13px">Si no creaste esta cuenta, ignora este mensaje o contáctanos.</p>
        </div>

        <footer>© {{ date('Y') }} StarKit &nbsp;·&nbsp; Este correo fue enviado automáticamente.</footer>
    </div>
</div>
</body>
</html>
