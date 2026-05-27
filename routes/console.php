<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('deploy:seed-if-empty', function () {
    $hasAdmin = User::query()->where('role', 'admin')->exists();
    $hasCatalog = Product::query()->exists();
    $hasAdminPassword = filled(env('SEED_ADMIN_PASSWORD'));

    if ($hasCatalog && ($hasAdmin || ! $hasAdminPassword)) {
        $this->info('Seed skipped because application data already exists.');

        return self::SUCCESS;
    }

    if (app()->environment('production') && ! $hasAdminPassword) {
        $this->warn('SEED_ADMIN_PASSWORD is not set; seeding public demo data without an admin account.');
    }

    $this->call('db:seed', ['--force' => true]);

    return self::SUCCESS;
})->purpose('Seed initial ecommerce data only when the database is empty');
