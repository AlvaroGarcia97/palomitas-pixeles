# Palomitas & Pixeles

Aplicación web (Laravel) para gestionar un **club de cine**: busca películas, guárdalas
en tu lista de pendientes, puntúalas cuando las veas y deja que la **ruleta** decida qué
toca esta noche. Incluye un mini-juego de adivinar películas por su carátula.

Es un proyecto de aprendizaje: autenticación propia, consumo de una API externa (TMDB),
base de datos relacional y lógica de juego con estado en sesión.

## Funcionalidades

- **Cuentas de usuario**: registro, login y logout (contraseñas cifradas con bcrypt).
- **Buscador de películas**: consulta TMDB y muestra carátula, año y nota.
- **Mi club**: guarda películas como *Quiero ver* o *Vista*, con **nota (1–5)** y **reseña**.
- **Ruleta**: elige al azar una película pendiente con una pequeña animación.
- **Juego "¿Qué película es?"**: 10 rondas de adivinar el título a partir de la carátula, con puntuación guardada.
- **Resumen (dashboard)**: totales del club, nota media, estadísticas del juego y actividad reciente.

## Tecnologías

- PHP 8.3 + **Laravel 13**
- **SQLite** (base de datos por defecto)
- Tailwind CSS (vía CDN)
- **TMDB API** (The Movie Database)

## Requisitos

- PHP 8.3 o superior
- Composer 2
- Una **API key gratuita** de TMDB

## Instalación paso a paso (local)

Sigue estos pasos para que funcione correctamente en tu equipo:

```bash
# 1. Clonar el repositorio
git clone https://github.com/AlvaroGarcia97/palomitas-pixeles.git
cd palomitas-pixeles

# 2. Instalar dependencias
composer install

# 3. Copiar el archivo de entorno
# En Windows (CMD): copy .env.example .env
# En Linux/Mac: cp .env.example .env
cp .env.example .env

# 4. Generar la clave de la aplicación
php artisan key:generate

# 5. Crear la base de datos SQLite (si no existe)
# En Windows (CMD): type nul > database\database.sqlite
# En Linux/Mac: touch database/database.sqlite
type nul > database\database.sqlite

# 6. Ejecutar las migraciones
php artisan migrate

# 7. Arrancar el servidor
php artisan serve
```

La app quedará en `http://127.0.0.1:8000`.

## Configurar TMDB

1. Crea una cuenta en [themoviedb.org](https://www.themoviedb.org).
2. En **Ajustes → API**, solicita una clave de desarrollador y copia la **API Key (v3 auth)**.
3. Añádela a tu `.env`:

```env
TMDB_API_KEY=tu_api_key
```

4. Limpia la caché de configuración si la app ya estaba arrancada:

```bash
php artisan config:clear
```

## Uso

1. `php artisan serve` y abre `http://127.0.0.1:8000`.
2. **Crea una cuenta** o entra con la tuya.
3. **Busca** una película y pulsa *Añadir a mi club*.
4. En **Mi club**, abre la ficha para marcarla como *Vista*, ponerle nota y reseña.
5. Prueba la **Ruleta** con tus pendientes y el **Juego** para adivinar carátulas.
6. El **Resumen** reúne tus estadísticas.

## Solución de problemas

- **No aparecen películas al buscar**: comprueba que `TMDB_API_KEY` esté bien en `.env` y que hayas hecho `php artisan config:clear`.
- **Errores de migración**: asegúrate de haber creado `database/database.sqlite` antes de `php artisan migrate`.
- **Errores 500 tras clonar**: ejecuta `php artisan key:generate` (falta `APP_KEY`).

## Estructura y decisiones

- `app/Services/TmdbService.php`: centraliza las llamadas a TMDB.
- Auth manual con sesiones de Laravel.
- `library_items` guarda un snapshot de los datos de TMDB.
- En el juego, la respuesta correcta vive solo en la sesión del servidor.

## Roadmap

- [ ] Tests automáticos con `Http::fake()`.
- [ ] Filtrar el buscador por género y año.
- [ ] Despliegue en producción.
- [ ] Front-end en **Angular** consumiendo una API JSON propia.
