# API Documentation

Base URL (local): `http://localhost:8000`
Version prefix: `/v1` (configurable via `API_VERSION` in `.env`)

## Mass Assignment Safety

`User` model uses Laravel-style `fillable` fields (`name`, `email`).
Only these keys are accepted from request payloads.

## Health

### `GET /v1/health`
Returns API health information.

Success `200`

```json
{
  "status": "ok",
  "app": "Vanilla PHP MVC API",
  "version": "v1",
  "timestamp": "2026-02-14T10:00:00+00:00"
}
```

## Users

### `GET /v1/users`
List users.

Success `200`

```json
{
  "data": [
    {
      "id": 1,
      "name": "Jane",
      "email": "jane@example.com",
      "created_at": "2026-02-14 12:00:00",
      "updated_at": "2026-02-14 12:00:00"
    }
  ]
}
```

### `GET /v1/users/{id}`
Get one user by id.

Success `200`, Not Found `404`

### `POST /v1/users`
Create user.

Body:

```json
{
  "name": "Jane",
  "email": "jane@example.com"
}
```

Success `201`, Validation Error `422`

### `PUT /v1/users/{id}`
Update user.

Body:

```json
{
  "name": "Jane Updated",
  "email": "jane.updated@example.com"
}
```

Success `200`, Not Found `404`, Validation Error `422`

### `DELETE /v1/users/{id}`
Delete user.

Success `204`, Not Found `404`

## Example cURL

Create:

```bash
curl -X POST http://localhost:8000/v1/users \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane","email":"jane@example.com"}'
```

List:

```bash
curl http://localhost:8000/v1/users
```