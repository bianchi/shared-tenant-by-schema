<?php

declare(strict_types=1);

it('renders the landing page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
