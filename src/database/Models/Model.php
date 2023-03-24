<?php

namespace Database\Models;

interface Model
{
    public function getById(int $id);

    public function add(array $params);

    public function remove(int $id);

    public function getAll();
}