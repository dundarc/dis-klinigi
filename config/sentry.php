<?php

return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    'environment' => env('SENTRY_ENVIRONMENT', env('APP_ENV', 'production')),

    'breadcrumbs' => [
        'logs' => true,
        'sql_queries' => true,
        'sql_bindings' => true,
        'queue_info' => true,
        'command_info' => true,
    ],

    'tracing' => [
        'default_integrations' => true,
        'db_query' => env('SENTRY_TRACE_DB_QUERIES', false),
        'db_query_origin' => env('SENTRY_TRACE_DB_QUERIES_ORIGIN', false),
        'view_render' => env('SENTRY_TRACE_VIEW_RENDERS', false),
        'http_client' => env('SENTRY_TRACE_HTTP_CLIENT_REQUESTS', false),
        'redis_command' => env('SENTRY_TRACE_REDIS_COMMANDS', false),
        'queue_job' => env('SENTRY_TRACE_QUEUE_JOBS', false),
        'command' => env('SENTRY_TRACE_COMMANDS', false),
    ],

    'send_default_pii' => env('SENTRY_SEND_DEFAULT_PII', false),

    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 0.1),

    'profiles_sample_rate' => env('SENTRY_PROFILES_SAMPLE_RATE', 0.1),
];