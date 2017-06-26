<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class InfoResponse
 * @package Fradnet\PageBundle\Response
 */
class InfoResponse extends JsonResponse
{
    /**
     * InfoResponse constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct([
            'status' => 'info',
            'data' => $data
        ]);
    }
}