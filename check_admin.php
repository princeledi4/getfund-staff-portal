<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "\n=== Admin User Check ===\n\n";

$user = User::where('email', 'admin@getfund.gov.gh')->first();

if (!$user) {
    echo "❌ Admin user not found!\n";
    echo "\nCreating admin user...\n";

    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@getfund.gov.gh',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);

    echo "✅ Admin user created successfully!\n";
} else {
    echo "✅ Admin user exists\n";
}

echo "\nUser Details:\n";
echo "- Name: {$user->name}\n";
echo "- Email: {$user->email}\n";
echo "- Email Verified: " . ($user->email_verified_at ? "Yes ({$user->email_verified_at})" : "No") . "\n";
echo "- Password Set: " . ($user->password ? "Yes" : "No") . "\n";

// Check if email ends with correct domain
$emailCheck = str_ends_with($user->email, '@getfund.gov.gh');
echo "- Email Domain Check: " . ($emailCheck ? "✅ Pass" : "❌ Fail") . "\n";

// Check if email is verified
$verifiedCheck = $user->hasVerifiedEmail();
echo "- Email Verified Check: " . ($verifiedCheck ? "✅ Pass" : "❌ Fail") . "\n";

echo "\n";

if (!$user->email_verified_at) {
    echo "⚠️  Email not verified. Verifying now...\n";
    $user->email_verified_at = now();
    $user->save();
    echo "✅ Email verified!\n\n";
}

echo "Login Credentials:\n";
echo "- URL: http://getfund-ussd-main.test/admin\n";
echo "- Email: admin@getfund.gov.gh\n";
echo "- Password: password\n";
echo "\n⚠️  Remember to change the password after first login!\n\n";
