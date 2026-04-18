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

    // Backend API root used for server-to-server calls (session revocation, user lookups, etc.).
    // CLERK_SECRET_KEY is required to authenticate against this.
    'api_base' => env('CLERK_API_BASE', 'https://api.clerk.com'),

    'admin_allowed_emails' => array_values(array_filter(array_map(
        fn ($e) => strtolower(trim($e)),
        explode(',', (string) env('ADMIN_ALLOWED_EMAILS', ''))
    ))),

];
