<?php

use App\Models\User;

if (!function_exists('user')) {
    function user(): User|null
    {
        return auth()->user();
    }
}

if (!function_exists('access_token')) {

    function access_token(): string
    {
        return session()->get('access_token');
    }
}