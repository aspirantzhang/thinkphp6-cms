<?php

return [
    'iss' => 'http://test.com',    // issuer, the server
    'aud' => 'http://test.com',
    'exp' => 30, // expired time
    'renew' => 1296000, // default: 1296000 (15 days)
    'alg' => 'HS256',
];
