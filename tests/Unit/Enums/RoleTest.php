<?php

declare(strict_types=1);

namespace Tests\Enums;

use App\Enums\Role;

test('label returns translated string', function () {
    $this->assertSame(
        __('roles.super_admin'),
        Role::SuperAdmin->label()
    );
});
