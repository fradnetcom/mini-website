<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class RedirectResponse
 * @package Fradnet\PageBundle\Response
 */
class RedirectResponse extends JsonResponse
{
    /**
     * RedirectResponse constructor.
     * @param string $url
     */
    public function __construct($url)
    {
        parent::__construct([
            'status' => 'redirect',
            'redirect' => $url
        ]);
    }
}