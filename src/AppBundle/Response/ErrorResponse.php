<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ErrorResponse
 * @package Fradnet\PageBundle\Response
 */
class ErrorResponse extends JsonResponse
{
    /**
     * ErrorResponse constructor.
     * @param array $errors
     */
    public function __construct($errors = [])
    {
        parent::__construct([
            'status' => 'error',
            'errors' => $errors
        ]);
    }
}