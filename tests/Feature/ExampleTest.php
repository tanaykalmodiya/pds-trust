<?php

test('the homepage redirects to the login page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
