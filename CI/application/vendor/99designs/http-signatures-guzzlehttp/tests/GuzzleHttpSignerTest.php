<?php

namespace HttpSignatures\Test;

use HttpSignatures\GuzzleHttp\Message;
use HttpSignatures\GuzzleHttp\RequestSubscriber;
use HttpSignatures\Context;

class GuzzleHttpSignerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function setUp()
    {
        $this->context = new Context(array(
            'keys' => array('pda' => 'secret'),
            'algorithm' => 'hmac-sha256',
            'headers' => array('(request-target)', 'date'),
        ));

        $this->client = new \GuzzleHttp\Client([
            'auth' => 'http-signatures'
        ]);
        $this->client->getEmitter()->attach(new RequestSubscriber($this->context));
    }

    /**
     * test signing a message
     */
    public function testGuzzleRequestHasExpectedHeaders()
    {
        $message = $this->client->createRequest('GET', '/path?query=123', array(
            'headers' => array('date' => 'today', 'accept' => 'llamas')
        ));

        $this->context->signer()->sign(new Message($message));

        $expectedString = implode(
            ',',
            array(
                'keyId="pda"',
                'algorithm="hmac-sha256"',
                'headers="(request-target) date"',
                'signature="SFlytCGpsqb/9qYaKCQklGDvwgmrwfIERFnwt+yqPJw="',
            )
        );

        $this->assertEquals(
            $expectedString,
            (string) $message->getHeader('Signature')
        );

        $this->assertEquals(
            'Signature ' . $expectedString,
            (string) $message->getHeader('Authorization')
        );
    }

    /**
     * test signing a message with a URL that doesn't contain a ?query
     */
    public function testGuzzleRequestHasExpectedHeaders2()
    {
        $message = $this->client->createRequest('GET', '/path', array(
            'headers' => array('date' => 'today', 'accept' => 'llamas')
        ));

        $this->context->signer()->sign(new Message($message));

        $expectedString = implode(
            ',',
            array(
                'keyId="pda"',
                'algorithm="hmac-sha256"',
                'headers="(request-target) date"',
                'signature="DAtF133khP05pS5Gh8f+zF/UF7mVUojMj7iJZO3Xk4o="',
            )
        );

        $this->assertEquals(
            $expectedString,
            (string) $message->getHeader('Signature')
        );

        $this->assertEquals(
            'Signature ' . $expectedString,
            (string) $message->getHeader('Authorization')
        );
    }

    public function testVerifyGuzzleRequest()
    {
        $message = $this->client->createRequest('GET', '/path?query=123', array(
            'headers' => array('date' => 'today', 'accept' => 'dogs')
        ));

        $this->context->signer()->sign(new Message($message));

        $this->assertTrue($this->context->verifier()->isValid(new Message($message)));
    }
}
