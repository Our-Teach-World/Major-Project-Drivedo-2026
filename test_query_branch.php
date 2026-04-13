<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = \Illuminate\Support\Facades\DB::table('students')->first();
$teacher = \Illuminate\Support\Facades\DB::table('teachers')->first();

echo "Student Branch: " . ($student->branch ?? 'NULL') . "\n";
echo "Teacher Branch: " . ($teacher->branch ?? 'NULL') . "\n";
