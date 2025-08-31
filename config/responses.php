<?php

return [
    'http' => [
        'messages' => [
            'success' => 'Operation completed successfully.',
            'error' => 'An error occurred while processing your request, please try again later.',
            'fail' => 'The operation failed.',
            'not_found' => 'Resource not found.',
            'unauthorized' => 'Unauthorized access.',
            'forbidden' => 'Forbidden action.',
            'validation_error' => 'Validation failed.',
        ],
        'status' => [
            'success' => 'success',
            'error' => 'error',
            'fail' => 'fail',
        ],
        'codes' => [
            'success' => 200,
            'error' => 500,
            'fail' => 400,
            'not_found' => 404,
            'unauthorized' => 401,
            'forbidden' => 403,
            'validation_error' => 422,
        ]
    ]
];