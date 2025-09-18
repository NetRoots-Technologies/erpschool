<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Load Laravel configuration
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check existing fee permissions
$permissions = \Spatie\Permission\Models\Permission::where('name', 'like', 'Fee%')->pluck('name');

echo "Existing Fee Permissions:\n";
foreach ($permissions as $permission) {
    echo "- " . $permission . "\n";
}

echo "\nTotal Fee Permissions: " . $permissions->count() . "\n";