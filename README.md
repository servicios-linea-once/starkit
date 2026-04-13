<div align="center">

# ⚡ StarKit

**Laravel 12 · Inertia v2 · Vue 3 · TypeScript**

Un starter kit moderno, elegante y listo para producción.

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue](https://img.shields.io/badge/Vue-3-42b883?style=flat-square&logo=vue.js&logoColor=white)](https://vuejs.org)
[![TypeScript](https://img.shields.io/badge/TypeScript-5-3178C6?style=flat-square&logo=typescript&logoColor=white)](https://www.typescriptlang.org)
[![Inertia](https://img.shields.io/badge/Inertia-v2-9553E9?style=flat-square)](https://inertiajs.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

[Instalación](#-instalación) · [Características](#-características) · [Stack](#-stack) · [Configuración](#️-configuración) · [Estructura](#-estructura)

</div>

---

## ✨ Características

### 🔐 Autenticación completa
- Registro, login y recuperación de contraseña
- Autenticación de dos factores (2FA) por correo
- Cierre de sesión de otros dispositivos
- Historial de accesos con IP, navegador, SO y ubicación

### 👥 Gestión de usuarios
- CRUD completo de usuarios con paginación y filtros
- Roles y permisos con Spatie Laravel Permission
- Avatar con subida a Cloudinary
- Activar / desactivar cuentas

### ⚙️ Panel de configuración
- Editar perfil (nombre, email, avatar)
- Cambiar contraseña
- Gestionar dispositivos activos
- Ver historial de sesiones

### 🎨 UI / UX
- Modo claro / oscuro con toggle y persistencia
- Toast notifications con sonido (dim) y barra de progreso
- 4 variantes: `success`, `error`, `warning`, `info`
- Animaciones de entrada/salida fluidas
- Diseño responsive — mobile first

### 🧩 Sistema de componentes
- `AppButton` — variantes primary, secondary, ghost, danger
- `AppInput` — con label, error y slot de icono
- `AppBadge` — con 5 variantes de color
- `AppModal` — tamaños sm, md, lg con slots header/footer
- `AppToast` — notificaciones globales con auto-hide
- `AppAvatar` — con fallback a iniciales
- `DataTable` — con ordenamiento, filtros y paginación

---

## 🚀 Instalación

### Opción A — Laravel Installer (recomendada)

```bash
laravel new my-app --using=servicios-linea-once/starkit
```

> Requiere Laravel Installer actualizado: `composer global update laravel/installer`

### Opción B — Clonando el repositorio

```bash
# 1. Clonar
git clone https://github.com/servicios-linea-once/starkit.git my-app
cd my-app

# 2. Configurar entorno
cp .env.example .env
composer install
php artisan key:generate

# 3. Base de datos
php artisan migrate --seed

# 4. Frontend
npm install && npm run build

# 5. Iniciar servidor
php artisan serve
```

### Credenciales por defecto (seeders)

| Campo | Valor |
|-------|-------|
| Email | `admin@starkit.test` |
| Contraseña | `password` |
| Rol | `super-admin` |

---

## 🛠 Stack

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 |
| Frontend | Vue 3 + TypeScript |
| Bridge | Inertia.js v2 |
| Estilos | Tailwind CSS v4 |
| Componentes UI | PrimeVue 4 |
| Autenticación | Laravel Sanctum |
| Roles y permisos | Spatie Permission |
| Imágenes / Avatares | Cloudinary |
| Base de datos | MySQL / PostgreSQL / SQLite |
| Cola de trabajos | Database / Redis |

---

## ⚙️ Configuración

El archivo `.env.example` incluye todas las variables necesarias. Las más importantes:

```dotenv
# Aplicación
APP_NAME="StarKit"
APP_URL=http://localhost:8000
APP_TIMEZONE=America/Lima

# Base de datos
DB_CONNECTION=mysql
DB_DATABASE=starterkit
DB_USERNAME=root
DB_PASSWORD=

# Cola
QUEUE_CONNECTION=sync        # sync | database | redis

# Correo
MAIL_MAILER=log              # log | smtp | resend

# Cloudinary (avatares — opcional)
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_UPLOAD_PRESET=starterkit_avatars

# 2FA
TWO_FACTOR_CODE_LENGTH=6
TWO_FACTOR_CODE_EXPIRES_MINUTES=10
```

---

## 📁 Estructura

```
resources/js/
├── components/
│   └── ui/
│       ├── AppButton.vue
│       ├── AppInput.vue
│       ├── AppBadge.vue
│       ├── AppModal.vue
│       ├── AppAvatar.vue
│       ├── AppToast.vue         ← Renderiza los toasts
│       └── FlashToast.vue       ← Conecta flash de Laravel con toasts
├── composables/
│   ├── useToast.ts              ← Sistema de notificaciones global
│   ├── useFlash.ts              ← Lee flash messages de Inertia
│   ├── useTheme.ts              ← Toggle claro/oscuro
│   └── useInitials.ts           ← Genera iniciales de nombre
├── layouts/
│   ├── AppLayout.vue            ← Layout principal con sidebar
│   └── AuthLayout.vue           ← Layout para páginas de auth
├── pages/
│   ├── Auth/
│   │   ├── Login.vue
│   │   ├── Register.vue
│   │   ├── ForgotPassword.vue
│   │   └── TwoFactor.vue
│   ├── Admin/
│   │   ├── Users/
│   │   └── Roles/
│   └── Settings/
│       ├── Profile.vue
│       ├── Password.vue
│       └── LoginHistory.vue
└── types/
    └── index.ts                 ← Tipos globales + PageProps augmentation
```

---

## 🔔 Toast Notifications

Usa `useToast` desde cualquier componente Vue:

```ts
import { useToast } from '@/composables/useToast'
const { add } = useToast()

add('Perfil actualizado.',       'success')
add('Error al guardar.',         'error')
add('Revisa los campos.',        'warning')
add('Tienes 3 mensajes nuevos.', 'info')
```

Desde Laravel (flash messages — se convierten automáticamente a toasts):

```php
return back()->with('success', 'Usuario creado correctamente.');
return back()->with('error',   'No tienes permisos para esta acción.');
return back()->with('warning', 'Tu sesión expirará pronto.');
return back()->with('info',    'Revisa tu correo electrónico.');
```

---

## 🎨 Modo claro / oscuro

El sistema de temas usa `prefers-color-scheme` como valor inicial y permite toggle manual:

```ts
import { useTheme } from '@/composables/useTheme'
const { isDark, toggle } = useTheme()
```

```html
<button @click="toggle">
    {{ isDark ? '☀️ Claro' : '🌙 Oscuro' }}
</button>
```

---

## 📋 Requisitos

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 20
- MySQL 8 / PostgreSQL 15 / SQLite 3
- Laravel Installer >= 5.0 (para `--using`)

---

## 🤝 Contribuir

```bash
# Fork del repositorio
git checkout -b feature/mi-mejora
git commit -m "feat: descripción del cambio"
git push origin feature/mi-mejora
# Abre un Pull Request
```

---

## 📄 Licencia

StarKit está licenciado bajo la [licencia MIT](LICENSE).

---

<div align="center">
  Hecho con ❤️ · <a href="https://github.com/servicios-linea-once/starkit">servicios-linea-once/starkit</a>
</div>
