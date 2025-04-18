<?php

namespace Orchestra\Sidekick\Tests\Php81\Functions;

if (PHP_VERSION_ID < 80100) {
    return;
}

enum TestEnum
{
    case A;
}

enum TestBackedEnum: int
{
    case A = 1;
    case B = 2;
}

enum TestStringBackedEnum: string
{
    case A = 'A';
    case B = 'B';
}
