<?php

it('asks about domain name', function () {
    $this->artisan('add')
        ->expectsQuestion('What domain name would you like to use for this app?', 'laravel.localhost');
});

it('fails if sail is not installed', function () {
    $this->artisan('add laravel.localhost')
        ->expectsOutput(' Laravel Sail is required for this package')
        ->assertExitCode(1);
});
