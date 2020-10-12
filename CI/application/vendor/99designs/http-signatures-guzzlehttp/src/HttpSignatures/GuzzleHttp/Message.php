<?php

namespace HttpSignatures\GuzzleHttp;

use GuzzleHttp\Message\RequestInterface;

/**
 * Class RequestMessage
 *
 * wrapper around the Guzzle Request instance to have a consistent API for the HttpSignatures classes to consume
 *
 * @package HttpSignatures\Guzzle
 */
class Message
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var MessageHeaders
     */
    public $headers;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->headers = new MessageHeaders($request);
    }

    public function getQueryString()
    {
        $qs = $this->request->getQuery();
        return $qs->count() ? $qs : null;
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function getPathInfo()
    {
        return $this->request->getPath();
    }
}
