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
- PostgreSQL 14+
- PHP `pdo_pgsql` extension

## Setup

1. Copy `.env.example` to `.env`.
2. Set PostgreSQL connection string in `.env`:

```bash
DATABASE_URL=postgresql://user:password@localhost:5432/mvc_api
```

3. Create table schema:

```bash
psql "$DATABASE_URL" -f database/schema.sql
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
   - `DATABASE_URL`
4. Optional: set `DB_SSLMODE` (`prefer`, `require`, or `disable`) if needed.
5. Deploy and test `https://<your-service>.onrender.com/v1/health`.

Notes:

- App expects `DB_DRIVER=pgsql`.
- Do not commit real credentials in `.env`.

## Notes

- API version prefix comes from `API_VERSION` in `.env`.
- Model `fillable` support is available via `onlyFillable()` to prevent unsafe mass assignment.
- This starter keeps dependencies at zero (no framework, no external packages).
