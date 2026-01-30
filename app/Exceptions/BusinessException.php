<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    public function __construct(string $message = "Business rule violated", int $code = 409)
    {
        parent::__construct($message, $code);
    }
}


// if ($job->status == 'completed') {
//     throw new \App\Exceptions\BusinessException('Job already completed');
// }