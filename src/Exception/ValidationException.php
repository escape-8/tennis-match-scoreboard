<?php

declare(strict_types=1);

namespace App\Exception;

use DomainException;

class ValidationException extends DomainException
{
    private string $templatePath;
    private array $args;

    public function __construct(string $templatePath, array $args, int $code = 409)
    {
        parent::__construct();
        $this->templatePath = $templatePath;
        $this->args = $args;
        $this->code = $code;
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    public function getArgs(): array
    {
        return $this->args;
    }
}