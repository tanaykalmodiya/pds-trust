<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('beneficiaries cannot access official or FPS pages', function () {
    $user = User::factory()->create([
        'role' => 'beneficiary',
    ]);

    $this->actingAs($user)
        ->get('/official/grievances')
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/fps/stock')
        ->assertForbidden();
});

test('FPS owners cannot access beneficiary or official pages', function () {
    $user = User::factory()->create([
        'role' => 'fps_owner',
    ]);

    $this->actingAs($user)
        ->get('/beneficiary/grievances')
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/official/discrepancies')
        ->assertForbidden();
});

test('government officials cannot access beneficiary or FPS pages', function () {
    $user = User::factory()->create([
        'role' => 'government_official',
    ]);

    $this->actingAs($user)
        ->get('/beneficiary/sms')
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/fps/distributions')
        ->assertForbidden();
});

test('guests are redirected from protected pages', function () {
    $this->get('/beneficiary/grievances')
        ->assertRedirect(route('login'));

    $this->get('/fps/stock')
        ->assertRedirect(route('login'));

    $this->get('/official/grievances')
        ->assertRedirect(route('login'));
});
