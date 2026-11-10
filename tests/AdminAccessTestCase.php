<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

abstract class AdminAccessTestCase extends TestCase
{
    private string $isolatedPath;

    public function createApplication()
    {
        if (!extension_loaded('pdo_sqlite')) {
            throw new RuntimeException('Admin access tests require isolated in-memory SQLite.');
        }

        $this->isolatedPath = sys_get_temp_dir().'/superfit-admin-tests-'.bin2hex(random_bytes(8));
        foreach (['bootstrap/cache', 'config', 'storage/framework/views', 'storage/framework/sessions'] as $directory) {
            mkdir($this->isolatedPath.'/'.$directory, 0700, true);
        }

        $app = require dirname(__DIR__).'/bootstrap/app.php';
        $this->traitsUsedByTest = class_uses_recursive(static::class);

        // Never load repository environment files, configuration caches, or route caches.
        $app->useEnvironmentPath($this->isolatedPath);
        $app->useBootstrapPath($this->isolatedPath.'/bootstrap');
        $app->useConfigPath($this->isolatedPath.'/config');
        $app->useStoragePath($this->isolatedPath.'/storage');
        $app->instance(LoadEnvironmentVariables::class, new class
        {
            public function bootstrap($app): void {}
        });

        $app->beforeBootstrapping(RegisterProviders::class, function ($app) {
            $app['config']->set([
                'app.env' => 'testing',
                'app.debug' => false,
                'app.url' => 'http://localhost',
                'app.key' => 'base64:'.base64_encode(str_repeat('a', 32)),
                'auth' => require dirname(__DIR__).'/config/auth.php',
                'sanctum' => require dirname(__DIR__).'/config/sanctum.php',
                'database' => [
                    'default' => 'sqlite',
                    'connections' => [
                        'sqlite' => [
                            'driver' => 'sqlite',
                            'database' => ':memory:',
                            'prefix' => '',
                            'foreign_key_constraints' => true,
                        ],
                    ],
                ],
                'cache.default' => 'array',
                'session.driver' => 'array',
                'session.secure' => false,
                'queue.default' => 'sync',
                'mail.default' => 'array',
                'broadcasting.default' => 'null',
                'logging.default' => 'null',
                'logging.channels.null' => ['driver' => 'monolog', 'handler' => \Monolog\Handler\NullHandler::class],
                'view.paths' => [dirname(__DIR__).'/resources/views'],
                'view.compiled' => $this->isolatedPath.'/storage/framework/views',
                'hashing.bcrypt.rounds' => 4,
            ]);
            $app->detectEnvironment(fn () => 'testing');
        });

        $app->make(Kernel::class)->bootstrap();

        $connection = $app['db']->connection();
        if ($connection->getDriverName() !== 'sqlite'
            || $connection->getConfig('database') !== ':memory:'
            || $connection->selectOne('PRAGMA database_list')->file !== '') {
            throw new RuntimeException('Test database isolation could not be verified.');
        }

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();

        // Minimal, disposable authentication fixtures. No application migrations or seeders run.
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('profile_image')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        try {
            parent::tearDown();
        } finally {
            if (isset($this->isolatedPath)) {
                (new Filesystem)->deleteDirectory($this->isolatedPath);
            }
        }
    }
}
