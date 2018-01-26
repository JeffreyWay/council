<?php

return [
    'administrators' => [
        // Add the email addresses of users who should be administrators here.
        'jon@example.com'
    ],
    'reputation' => [
        'thread_published' => 10,
        'reply_posted' => 2,
        'best_reply_awarded' => 50,
        'reply_favorited' => 5
    ],
    'support' => [
        'suspension' => env('COUNCIL_SUPPORT_SUSPENSION', env('COUNCIL_SUPPORT_EMAIL'))
    ]
];
