<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide here will be executed for every test file within
| your application. This is the perfect place to set up a shared environment
| for all your tests which significantly reduces boilerplate code.
|
*/

// BU SATIR EN ÖNEMLİSİ: Feature testlerinin Laravel uygulamasını başlatmasını sağlar.
uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect" function gives you access to a set of assertion methods that are designed to be
| eloquent and readable.
|
| Instead of expecting a result to be `true`, you can `expect($result)->toBeTrue()`.
| Instead of expecting that something is not `null`, you can `expect($result)->not->toBeNull()`.
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
| global functions to help you to reduce boilerplate.
|
*/

function something()
{
    // ..
}