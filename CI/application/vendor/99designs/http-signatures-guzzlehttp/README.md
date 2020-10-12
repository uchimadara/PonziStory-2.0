HTTP Signatures Guzzle 4
========================

Guzzle 4 support for 99designs http-signatures library

[![Build Status](https://travis-ci.org/99designs/http-signatures-guzzlehttp.svg)](https://travis-ci.org/99designs/http-signatures-guzzlehttp)

Adds [99designs/http-signatures][99signatures] support to Guzzle 4.  
For Guzzle 3 see the [99designs/http-signatures-guzzle][99signatures-guzzle] repo.

Signing with Guzzle 4
---------------------

This library includes support for automatically signing Guzzle requests using an event subscriber.

```php
use HttpSignatures\Context;
use HttpSignatures\GuzzleHttp\RequestSubscriber;

$context = new Context(array(
  'keys' => array('examplekey' => 'secret-key-here'),
  'algorithm' => 'hmac-sha256',
  'headers' => array('(request-target)', 'Date', 'Accept'),
));

$client = new \Guzzle\Http\Client('http://example.org');
$client->getEmitter()->attach(new RequestSubscriber($context));

// The below will now send a signed request to: http://example.org/path?query=123
$client->get('/path?query=123', array(
  'Date' => 'Wed, 30 Jul 2014 16:40:19 -0700',
  'Accept' => 'llamas',
));
```

## Contributing

Pull Requests are welcome.

[99signatures]: https://github.com/99designs/http-signatures-php
[99signatures-guzzle]: https://github.com/99designs/http-signatures-guzzle
