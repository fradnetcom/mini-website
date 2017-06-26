<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class SuccessResponse
 * @package Fradnet\PageBundle\Response
 */
class SuccessResponse extends JsonResponse
{
    /**
     * SuccessResponse constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct([
            'status' => 'success',
            'data' => $data
        ]);
    }
}