<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('deploy:seed-if-empty', function () {
    if (User::query()->exists() || Product::query()->exists()) {
        $this->info('Seed skipped because application data already exists.');

        return self::SUCCESS;
    }

    if (app()->environment('production') && blank(env('SEED_ADMIN_PASSWORD'))) {
        $this->error('SEED_ADMIN_PASSWORD is required before seeding production data.');

        return self::FAILURE;
    }

    $this->call('db:seed', ['--force' => true]);

    return self::SUCCESS;
})->purpose('Seed initial ecommerce data only when the database is empty');
