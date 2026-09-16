<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('users')->get();
echo "ALL USERS IN DB:\n";
foreach($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Username: {$u->username} | Email: {$u->email} | Role: {$u->role} | Supervisor_ID: " . ($u->supervisor_id ?? 'NULL') . "\n";
}
