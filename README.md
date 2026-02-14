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

## Notes

- API version prefix comes from `API_VERSION` in `.env`.
- Model `fillable` support is available via `onlyFillable()` to prevent unsafe mass assignment.
- This starter keeps dependencies at zero (no framework, no external packages).
