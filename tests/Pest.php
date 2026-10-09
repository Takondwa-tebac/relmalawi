<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Parallel test isolation
|--------------------------------------------------------------------------
|
| `php artisan test --parallel` runs several worker processes. Each one needs its
| own compiled-Blade directory, otherwise Windows reports "rename ... Access is
| denied" when two workers compile the same view. The in-memory SQLite database
| is already per process, and Storage::fake() below is per worker too.
|
*/
if (($token = getenv('TEST_TOKEN')) !== false && $token !== '') {
    $viewPath = dirname(__DIR__).'/storage/framework/views/testing-'.$token;

    if (! is_dir($viewPath)) {
        mkdir($viewPath, 0777, true);
    }

    putenv('VIEW_COMPILED_PATH='.$viewPath);
    $_ENV['VIEW_COMPILED_PATH'] = $_SERVER['VIEW_COMPILED_PATH'] = $viewPath;
}

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    // Never let tests write uploads/conversions into the real storage/app/public.
    ->beforeEach(fn () => Storage::fake('public'))
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
