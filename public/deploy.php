<?php

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Resolve Console Kernel to run artisan commands in-process
use Illuminate\Contracts\Console\Kernel;
$kernel = $app->make(Kernel::class);

// Verify the security token
$token = $_GET['token'] ?? null;
$expectedToken = env('DEPLOY_TOKEN') ?: 'my-default-secret-token';

if (!$token || $token !== $expectedToken) {
    http_response_code(403);
    die('Unauthorized access.');
}

header('Content-Type: text/plain; charset=utf-8');
echo "🚀 Starting post-deployment setup...\n\n";

/**
 * Execute an Artisan command internal to the PHP process.
 */
function runArtisan($kernel, $command, $parameters = []) {
    echo "Running: php artisan {$command}... ";
    try {
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $exitCode = $kernel->call($command, $parameters, $output);
        echo $exitCode === 0 ? "SUCCESS\n" : "FAILED (code: $exitCode)\n";
        echo $output->fetch() . "\n";
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n\n";
    }
}

// 1. Run database migrations
runArtisan($kernel, 'migrate', ['--force' => true]);

// 2. Clear old caches
runArtisan($kernel, 'optimize:clear');

// 3. Rebuild fresh caches for production performance
runArtisan($kernel, 'config:cache');
runArtisan($kernel, 'route:cache');
runArtisan($kernel, 'view:cache');

// 4. Create public storage symlink if missing
runArtisan($kernel, 'storage:link');

echo "✅ Post-deployment setup complete!\n";
