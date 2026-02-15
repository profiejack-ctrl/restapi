# Vanilla PHP MVC REST API

A lightweight REST API starter in vanilla PHP using a Controller/Model pattern.

## Project Structure

- `public/index.php` - Front controller and app bootstrap
- `config/routes.php` - API routes
- `src/Controllers` - Request handlers
- `src/Models` - Data access layer
- `src/Core` - Router, DB connection, env loader, request/response helpers
- `database/schema.sql` - SQL schema
- `docs/API.md` - Endpoint documentation

## Requirements

- PHP 8.1+
- MySQL 8+
- PHP `mysqli` extension
- `mysqlnd` enabled (used by `mysqli_stmt::get_result`)

## Setup

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

## Deploy To Render (Docker)

This repo includes:

- `Dockerfile` (Render-compatible, listens on `$PORT`)
- `.dockerignore`
- `render.yaml` (Blueprint for one web service)

Steps:

1. Push this repo to GitHub/GitLab.
2. In Render, create a new service using Blueprint and select this repo.
3. Set secret env vars in Render for:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
4. Make sure your MySQL server allows connections from Render.
5. Deploy and test `https://<your-service>.onrender.com/v1/health`.

Notes:

- Render does not provide managed MySQL directly; use an external MySQL provider.
- Do not commit real credentials in `.env`.

## Notes

- API version prefix comes from `API_VERSION` in `.env`.
- Model `fillable` support is available via `onlyFillable()` to prevent unsafe mass assignment.
- This starter keeps dependencies at zero (no framework, no external packages).
