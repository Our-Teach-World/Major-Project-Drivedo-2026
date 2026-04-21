<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Teacher;

$t2 = Teacher::where('user_id', 2)->first();
if ($t2) {
    $t2->update(['branch' => 'Computer Science & Engineering']);
    echo "Updated user 2 to Computer Science & Engineering\n";
} else {
    echo "User 2 not found in teachers table\n";
}

$t4 = Teacher::where('user_id', 4)->first();
if ($t4) {
    $t4->update(['branch' => 'Chemical Engineering']);
    echo "Updated user 4 to Chemical Engineering\n";
} else {
    echo "User 4 not found in teachers table\n";
}
