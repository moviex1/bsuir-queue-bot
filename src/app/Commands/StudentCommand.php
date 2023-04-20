<?php

namespace App\Commands;

use App\Enums\Role;
use App\States\StateManager;
use App\Telegram;

abstract class StudentCommand implements Command
{
    protected Role $role = Role::Student;

    public function __construct(
        protected Telegram $telegram,
        protected array $params,
        protected StateManager $stateManager
    ) {
    }


}