<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use mysqli_stmt;

final class User extends Model
{
    protected array $fillable = ['name', 'email'];

    public function all(): array
    {
        $result = Database::connection()->query('SELECT id, name, email, created_at, updated_at FROM users ORDER BY id DESC');
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT id, name, email, created_at, updated_at FROM users WHERE id = ? LIMIT 1');
        $this->bindParams($stmt, [$id]);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc() ?: null;

        $stmt->close();

        return $row;
    }

    public function create(array $attributes): array
    {
        $data = $this->onlyFillable($attributes);

        $stmt = Database::connection()->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
        $this->bindParams($stmt, [(string) $data['name'], (string) $data['email']]);
        $stmt->execute();
        $id = Database::connection()->insert_id;

        $stmt->close();

        return $this->find((int) $id) ?? [];
    }

    public function update(int $id, array $attributes): ?array
    {
        $data = $this->onlyFillable($attributes);

        $stmt = Database::connection()->prepare('UPDATE users SET name = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        $this->bindParams($stmt, [(string) $data['name'], (string) $data['email'], $id]);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $stmt->close();
            return null;
        }

        $stmt->close();

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM users WHERE id = ?');
        $this->bindParams($stmt, [$id]);
        $stmt->execute();
        $affected = $stmt->affected_rows;

        $stmt->close();

        return $affected > 0;
    }

    private function bindParams(mysqli_stmt $stmt, array $params): void
    {
        if ($params === []) {
            return;
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
                continue;
            }

            if (is_float($param)) {
                $types .= 'd';
                continue;
            }

            $types .= 's';
        }

        $values = [$types];
        foreach ($params as $index => $value) {
            $values[] = &$params[$index];
        }

        call_user_func_array([$stmt, 'bind_param'], $values);
    }
}
