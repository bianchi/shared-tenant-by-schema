<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows view any', function () {
    $this->assertFalse((new CategoryPolicy())->viewAny($this->user));
});

it('allows view', function () {
    $categoryToView = Category::factory()->create();
    $this->assertFalse((new CategoryPolicy())->view($this->user, $categoryToView));
});

it('allows create', function () {
    $this->assertFalse((new CategoryPolicy())->create($this->user));
});

it('allows update', function () {
    $categoryToUpdate = Category::factory()->create();
    $this->assertFalse((new CategoryPolicy())->update($this->user, $categoryToUpdate));
});

it('allows delete', function () {
    $categoryToDelete = Category::factory()->create();
    $this->assertFalse((new CategoryPolicy())->delete($this->user, $categoryToDelete));
});

it('disallows force delete', function () {
    $categoryToDelete = Category::factory()->create();
    $this->assertFalse((new CategoryPolicy())->forceDelete($this->user, $categoryToDelete));
});

it('disallows restore', function () {
    $categoryToRestore = Category::factory()->create();
    $this->assertFalse((new CategoryPolicy())->restore($this->user, $categoryToRestore));
});
