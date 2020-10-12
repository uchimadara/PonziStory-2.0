<?php

namespace HttpSignatures\GuzzleHttp;

use GuzzleHttp\Message\MessageInterface;

/**
 * Class MessageHeaders
 *
 * wrapper around the Guzzle Request instance to have a consistent API for the HttpSignatures classes to consume
 *
 * @package HttpSignatures\Guzzle
 */
class MessageHeaders
{
    /**
     * @var MessageInterface
     */
    private $request;

    public function __construct(MessageInterface $request)
    {
        $this->request = $request;
    }

    public function has($header)
    {
        return $this->request->hasHeader($header);
    }

    public function get($header)
    {
        return $this->request->getHeader($header);
    }

    public function set($header, $value)
    {
        $this->request->setHeader($header, $value);
    }
}
