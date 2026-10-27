# Palomitas & Pixeles

Aplicacin web (Laravel) para gestionar un **club de cine**: busca pelculas, gurdalas
en tu lista de pendientes, puntalas cuando las veas y deja que la **ruleta** decida qu
toca esta noche. Incluye un mini-juego de adivinar pelculas por su cartula.

Es un proyecto de aprendizaje: autenticacin propia, consumo de una API externa (TMDB),
base de datos relacional y lgica de juego con estado en sesin.

## Funcionalidades

- **Cuentas de usuario**: registro, login y logout (contraseas cifradas con bcrypt).
- **Buscador de pelculas**: consulta TMDB y muestra cartula, ao y nota.
- **Mi club**: guarda pelculas como *Quiero ver* o *Vista*, con **nota (15)** y **resea**.
- **Ruleta**: elige al azar una pelcula pendiente con una pequea animacin.
- **Juego "Qu pelcula es?"**: 10 rondas de adivinar el ttulo a partir de la cartula, con puntuacin guardada.
- **Resumen (dashboard)**: totales del club, nota media, estadsticas del juego y actividad reciente.

## Tecnologas

- PHP 8.3 + **Laravel 13**
- **SQLite** (base de datos por defecto)
- Tailwind CSS (va CDN)
- **TMDB API** (The Movie Database)

## Requisitos

- PHP 8.3 o superior
- Composer 2
- Una **API key gratuita** de TMDB

## Instalacin paso a paso (local)

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

# 4. Generar la clave de la aplicacin
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

La app quedar en `http://127.0.0.1:8000`.

## Configurar TMDB

1. Crea una cuenta en [themoviedb.org](https://www.themoviedb.org).
2. En **Ajustes  API**, solicita una clave de desarrollador y copia la **API Key (v3 auth)**.
3. Adela a tu `.env`:

```env
TMDB_API_KEY=tu_api_key
```

4. Limpia la cach de configuracin si la app ya estaba arrancada:

```bash
php artisan config:clear
```

## Uso

1. `php artisan serve` y abre `http://127.0.0.1:8000`.
2. **Crea una cuenta** o entra con la tuya.
3. **Busca** una pelcula y pulsa *Aadir a mi club*.
4. En **Mi club**, abre la ficha para marcarla como *Vista*, ponerle nota y resea.
5. Prueba la **Ruleta** con tus pendientes y el **Juego** para adivinar cartulas.
6. El **Resumen** rene tus estadsticas.

## Solucin de problemas

- **No aparecen pelculas al buscar**: comprueba que `TMDB_API_KEY` est bien en `.env` y que hayas hecho `php artisan config:clear`.
- **Errores de migracin**: asegrate de haber creado `database/database.sqlite` antes de `php artisan migrate`.
- **Errores 500 tras clonar**: ejecuta `php artisan key:generate` (falta `APP_KEY`).

## Estructura y decisiones

- `app/Services/TmdbService.php`: centraliza las llamadas a TMDB.
- Auth manual con sesiones de Laravel.
- `library_items` guarda un snapshot de los datos de TMDB.
- En el juego, la respuesta correcta vive solo en la sesin del servidor.

## Roadmap

- [ ] Tests automticos con `Http::fake()`.
- [ ] Filtrar el buscador por gnero y ao.
- [ ] Despliegue en produccin.
- [ ] Front-end en **Angular** consumiendo una API JSON propia.
