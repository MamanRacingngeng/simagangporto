<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
  echo "autoload not found: $autoload\n";
  exit(1);
}
require $autoload;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::create([
  'name' => 'Test User',
  'email' => 'test@example.com',
  'password' => bcrypt('secret123')
]);

echo "Created user id: {$user->id}\n";
