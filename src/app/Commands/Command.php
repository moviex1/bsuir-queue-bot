<?php

namespace App\Commands;

interface Command
{
    public function execute() : void;
}