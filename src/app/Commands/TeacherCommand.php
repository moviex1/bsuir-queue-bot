<?php

namespace App\Commands;

use App\Enums\Role;
use App\States\StateManager;
use App\Telegram;
use Database\Entity\User;

abstract class TeacherCommand implements Command
{
    protected Role $role = Role::Teacher;

    public function __construct(
        protected Telegram $telegram,
        protected array $params,
        protected StateManager $stateManager
    ) {
    }

    protected function checkIfTeacher(User $user) : bool
    {
        return $user->getRole() == $this->role;
    }


}