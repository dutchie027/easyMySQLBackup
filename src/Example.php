<?php

declare(strict_types=1);

namespace dutchie027\EasyMySQLBackup;

class Example
{
    /**
     * Returns a greeting statement using the provided name.
     */
    public function greet(string $name = 'World'): string
    {
        return "Hello, {$name}!";
    }
}
