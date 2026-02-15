# Vanilla PHP MVC REST API

A lightweight REST API starter in vanilla PHP using a Controller/Model pattern.

## Project Structure

- `public/index.php` - Front controller and app bootstrap
- `config/routes.php` - API routes
- `src/Controllers` - Request handlers
- `src/Models` - Data access layer
- `src/Core` - Router, DB connection, env loader, request/response helpers
- `database/schema.sql` - SQL schema for manual setup
- `mysql/` - Docker image files to run MySQL service on Render
- `docs/API.md` - Endpoint documentation

## Requirements

- PHP 8.1+
- MySQL 8+
- PHP `mysqli` extension
- `mysqlnd` enabled (used by `mysqli_stmt::get_result`)

## Local Setup

1. Copy `.env.example` to `.env`.
2. Update `.env` with your database credentials.
3. Create DB/table:

```bash
mysql -u root -p < database/schema.sql
```

4. Run local server from project root:

```bash
php -S localhost:8000 -t public
```

## Quick Check

```bash
curl http://localhost:8000/v1/health
```

## Deploy To Render (Docker + MySQL Private Service)

This repo includes:

- `Dockerfile` for API service
- `mysql/Dockerfile` for MySQL private service
- `render.yaml` blueprint defining both services

Steps:

1. Push repo to GitHub/GitLab.
2. In Render, create a new service using Blueprint from this repo.
3. In Blueprint env setup, set the same value for:
   - `MYSQL_PASSWORD` (MySQL service)
   - `DB_PASS` (API service)
4. Deploy.
5. Validate health at `https://<your-service>.onrender.com/v1/health`.

Notes:

- MySQL runs as a separate private service with a persistent disk mounted at `/var/lib/mysql`.
- Initial table creation is handled by `mysql/init/001_schema.sql` on first MySQL boot.
- Do not commit real credentials in `.env`.

## Notes

- API version prefix comes from `API_VERSION` in `.env`.
- Model `fillable` support is available via `onlyFillable()` to prevent unsafe mass assignment.
- This starter keeps dependencies at zero (no framework, no external packages).