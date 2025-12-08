<?php

namespace App\Message;

final class DiveLogMessage
{
    public function __construct(
        private array $content
    ) {
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
