# Prueba Técnica - Sistema de Gestión de Proyectos COTECMAR

Sistema web desarrollado con Laravel y Vue.js para la gestión de proyectos, bloques y piezas de construcción naval.

## 📋 Requisitos del Sistema

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 16.0
- **NPM** o **Yarn**
- **MySQL** >= 8.0 o **PostgreSQL** >= 13
- **Git**

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/pruebaTecnica.git
cd pruebaTecnica
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
# o si prefieres yarn
yarn install
```

### 4. Configurar variables de entorno

```bash
# Copiar el archivo de configuración
copy .env.example .env

# Generar la clave de aplicación
php artisan key:generate
```

### 5. Configurar la base de datos

Edita el archivo `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Ejecutar migraciones

```bash
# Crear las tablas en la base de datos
php artisan migrate

```

### 7. Configurar storage

```bash
# Crear enlace simbólico para archivos públicos
php artisan storage:link
```

### 8. Compilar assets

```bash
# Para desarrollo
npm run dev

```

## 🏃‍♂️ Ejecutar el Proyecto

### Desarrollo

```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Compilación de assets en tiempo real
npm run dev

# Terminal 3: Optimizador
php artisan optimize
```

La aplicación estará disponible en: `http://localhost:8000`


## 📁 Estructura del Proyecto

```
pruebaTecnica/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── ...
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/            # Seeders
├── resources/
│   ├── js/
│   │   ├── Components/     # Componentes Vue
│   │   ├── Pages/         # Páginas Vue
│   │   └── Layouts/       # Layouts
│   └── views/             # Vistas Blade
├── routes/
│   ├── web.php            # Rutas web
│   └── api.php            # Rutas API
└── public/               # Archivos públicos
```

## 🎨 Tecnologías Utilizadas

- **Backend**: Laravel 10
- **Frontend**: Vue.js 3 + Inertia.js
- **CSS**: Tailwind CSS
- **Base de Datos**: MySQL/PostgreSQL