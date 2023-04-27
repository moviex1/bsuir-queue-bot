<?php

namespace App\States;

interface State
{
    public function handleInput(array $params): void;
}