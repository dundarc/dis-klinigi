<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ocr' => [
        'driver' => env('OCR_DRIVER', 'tesseract'),
        'lang' => env('OCR_LANG', 'tur+eng'),
        'psm' => env('OCR_PSM', 6), // Page segmentation mode
        'oem' => env('OCR_OEM', 1), // OCR Engine mode (LSTM)
        'dpi' => env('OCR_DPI', 300), // DPI for PDF rasterization
        'pdf_engine' => env('OCR_PDF_ENGINE', 'imagick'), // imagick | poppler
        'max_pages' => env('OCR_MAX_PAGES', 10), // Maximum pages to process
        'timeout' => env('OCR_TIMEOUT', 45), // Timeout in seconds
        'strict_mode' => env('OCR_STRICT_MODE', false), // Strict parsing mode
        'temp_dir' => env('OCR_TEMP_DIR', sys_get_temp_dir() . '/ocr'),
    ],

];
