<?php

return [
    'administrators' => [
        // Add the email addresses of users who should be administrators here.
    ],
    'reputation' => [
        'thread_published' => 10,
        'reply_posted' => 2,
        'best_reply_awarded' => 50,
        'reply_favorited' => 5
    ],
    'support' => [
        'suspension' => empty(env('COUNCIL_SUPPORT_SUSPENSION_EMAIL')) ? env('COUNCIL_SUPPORT_EMAIL') : env('COUNCIL_SUPPORT_SUSPENSION_EMAIL')
    ]
];
