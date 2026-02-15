<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class User extends Model
{
    protected array $fillable = ['name', 'email'];

    public function all(): array
    {
        $stmt = Database::connection()->query('SELECT id, name, email, created_at, updated_at FROM users ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT id, name, email, created_at, updated_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }

    public function create(array $attributes): array
    {
        $data = $this->onlyFillable($attributes);

        $stmt = Database::connection()->prepare('INSERT INTO users (name, email) VALUES (:name, :email) RETURNING id');
        $stmt->execute([
            'name' => (string) $data['name'],
            'email' => (string) $data['email'],
        ]);
        $id = (int) $stmt->fetchColumn();

        return $this->find($id) ?? [];
    }

    public function update(int $id, array $attributes): ?array
    {
        $data = $this->onlyFillable($attributes);

        $stmt = Database::connection()->prepare('UPDATE users SET name = :name, email = :email, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'name' => (string) $data['name'],
            'email' => (string) $data['email'],
        ]);

        if ($stmt->rowCount() === 0) {
            return null;
        }

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
