<?php

return [

    'publishable_key' => env('CLERK_PUBLISHABLE_KEY'),
    'secret_key'      => env('CLERK_SECRET_KEY'),

    'client_id'      => env('CLERK_OAUTH_CLIENT_ID'),
    'client_secret'  => env('CLERK_OAUTH_CLIENT_SECRET'),

    'authorize_url'  => env('CLERK_OAUTH_AUTHORIZE_URL'),
    'token_url'      => env('CLERK_OAUTH_TOKEN_URL'),
    'userinfo_url'   => env('CLERK_OAUTH_USERINFO_URL'),

    'scopes' => env('CLERK_OAUTH_SCOPES', 'openid profile email'),

    'admin_allowed_emails' => array_values(array_filter(array_map(
        fn ($e) => strtolower(trim($e)),
        explode(',', (string) env('ADMIN_ALLOWED_EMAILS', ''))
    ))),

];
