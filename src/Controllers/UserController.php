<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\User;

final class UserController
{
    public function index(): void
    {
        $model = new User();
        Response::json(['data' => $model->all()]);
    }

    public function show(string $id): void
    {
        $model = new User();
        $user = $model->find((int) $id);

        if ($user === null) {
            Response::json(['error' => 'User not found'], 404);
            return;
        }

        Response::json(['data' => $user]);
    }

    public function store(): void
    {
        $model = new User();
        $data = $model->onlyFillable(Request::json());

        if (empty($data['name']) || empty($data['email'])) {
            Response::json(['error' => 'name and email are required'], 422);
            return;
        }

        $created = $model->create($data);
        Response::json(['data' => $created], 201);
    }

    public function update(string $id): void
    {
        $model = new User();
        $data = $model->onlyFillable(Request::json());

        if (empty($data['name']) || empty($data['email'])) {
            Response::json(['error' => 'name and email are required'], 422);
            return;
        }

        $updated = $model->update((int) $id, $data);

        if ($updated === null) {
            Response::json(['error' => 'User not found'], 404);
            return;
        }

        Response::json(['data' => $updated]);
    }

    public function destroy(string $id): void
    {
        $model = new User();
        $deleted = $model->delete((int) $id);

        if (!$deleted) {
            Response::json(['error' => 'User not found'], 404);
            return;
        }

        Response::json([], 204);
    }
}