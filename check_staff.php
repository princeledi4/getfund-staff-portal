<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$count = DB::table('staff')->count();
echo "Staff records: $count\n";

if ($count > 0) {
    $staff = DB::table('staff')->first();
    echo "First staff ID: {$staff->staff_id}\n";
    echo "Name: {$staff->first_name} {$staff->last_name}\n";
    echo "\nAccess the profile at:\n";
    echo "http://getfund-ussd-main.test/staff/{$staff->staff_id}/profile\n";
} else {
    echo "\nNo staff records found. Please add a staff member via the admin panel:\n";
    echo "http://getfund-ussd-main.test/admin/staff/create\n";
}
