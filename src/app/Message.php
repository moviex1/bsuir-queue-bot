<?php

namespace App;

class Message
{
    private string $message;
    private array|bool $data;

    public function __construct($message, $data)
    {
        $this->message = $message;
        $this->data = $data;
    }


    public static function make(string $message, array|bool $data = []) : self
    {
        return new static($message, $data);
    }

    public function render() : string
    {
        ob_start();
        include MESSAGE_PATH . "/" . str_replace(".", "/", $this->message) . ".php";
        return (string) ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function __get(string $name)
    {
        return $this?->data[$name];
    }

}