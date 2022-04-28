<?php

namespace AppFinance\Domain\ValueObjects;

interface IDocument
{
    public function __construct(string $rawValue);
    public function getValue(): string;
    public function getType(): string;
}