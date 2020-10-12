<?php

namespace HttpSignatures\GuzzleHttp;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use HttpSignatures\Context;

class RequestSubscriber implements SubscriberInterface
{
    /**
     * @var \HttpSignatures\Context
     */
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getEvents()
    {
        return ['before' => ['onBefore', RequestEvents::SIGN_REQUEST]];
    }

    public function onBefore(BeforeEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getConfig()['auth'] != 'http-signatures') {
            return;
        }

        $this->context->signer()->sign(new Message($request));
    }
}
