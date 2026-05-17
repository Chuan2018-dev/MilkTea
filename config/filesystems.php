<?php

$publicStorageRoot = env('PUBLIC_STORAGE_PATH');

if ($publicStorageRoot) {
    $isAbsolutePath = str_starts_with($publicStorageRoot, DIRECTORY_SEPARATOR)
        || preg_match('/^[A-Z]:\\\\/i', $publicStorageRoot) === 1;

    $publicStorageRoot = $isAbsolutePath
        ? $publicStorageRoot
        : base_path($publicStorageRoot);
}

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => $publicStorageRoot ?: storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
