<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \Illuminate\Support\Facades\DB::table('users')->get(['id', 'name', 'role']);
echo "USERS IN DB:\n";
foreach($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Role: {$u->role}\n";
}
