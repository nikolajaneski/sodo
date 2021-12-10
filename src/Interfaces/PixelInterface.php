<?php

declare(strict_types=1);

namespace App\Interfaces;

interface PixelInterface
{

    public function trackPixel(array $data): int;

}
