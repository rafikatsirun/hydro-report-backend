<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_headers' => ['*'],
    'allowed_methods' => ['*'],
    'supports_credentials' => true,


];
