<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('dev', function () {
    $this->info('Starting Laravel dev server and Vite...');

    $process = new Process([
        'npx', 'concurrently',
        '-c', '#93c5fd,#c4b5fd',
        '--names', 'server,vite',
        '--kill-others',
        'php artisan serve',
        'npm run dev',
    ]);

    $process->setTimeout(null);
    $process->setTty(true);
    $process->run(function ($type, $buffer) {
        echo $buffer;
    });
})->purpose('Run Laravel dev server and Vite HMR concurrently');
