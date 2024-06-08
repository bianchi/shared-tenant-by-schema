<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\User;

it('casts email_verified_at to datetime', function () {
    $this->assertSame('datetime', (new User())->getCasts()['email_verified_at']);
});

it('casts password to hashed', function () {
    $this->assertSame('hashed', (new User())->getCasts()['password']);
});
