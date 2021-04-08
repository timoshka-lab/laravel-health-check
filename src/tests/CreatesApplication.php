<?php

namespace HealthCheckTests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require HC_LARAVEL_DIR . '/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
