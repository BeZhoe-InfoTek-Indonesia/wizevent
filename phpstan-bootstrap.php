<?php

declare(strict_types=1);

use Illuminate\Contracts\Foundation\Application;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

if ($app instanceof Application) {
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
}
