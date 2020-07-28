<?php

declare (strict_types = 1);

namespace App\Service;

abstract class BaseService
{
    const QUALITATIVE  = 0;
    const QUANTITATIVE = 1;

    public static $ir = [
        0.00,
        0.00,
        0.58,
        0.90,
        1.12,
        1.24,
        1.32,
        1.41,
        1.45,
        1.49,
        1.51,
        1.48,
        1.56,
        1.57,
        1.59,
    ];

    public function getIr($matrixSize):  ? float
    {
        return isset(self::$ir[$matrixSize - 1]) ? self::$ir[$matrixSize - 1] : null;
    }
}
