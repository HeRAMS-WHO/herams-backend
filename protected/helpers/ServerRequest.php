<?php

declare(strict_types=1);

namespace prime\helpers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use yii\base\NotSupportedException;
use yii\web\Request;

use function GuzzleHttp\Psr7\stream_for;

class ServerRequest implements ServerRequestInterface
{
    private Request $request;

    private string $version;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->version = $_SERVER['SERVER_PROTOCOL'] ?? '1.0';
    }


    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        throw new NotSupportedException();
    }

    public function getHeaders()
    {
        return $this->request->headers->toArray();
    }

    public function hasHeader($name)
    {
        return $this->request->headers->has($name);
    }

    public function getHeader($name)
    {
        return $this->request->headers->get($name, null, false);
    }

    public function getHeaderLine($name)
    {
        return $this->request->headers->get($name, null, true);
    }

    public function withHeader($name, $value)
    {
        throw new NotSupportedException();
    }

    public function withAddedHeader($name, $value)
    {
        throw new NotSupportedException();
    }

    public function withoutHeader($name)
    {
        throw new NotSupportedException();
    }

    public function getBody()
    {
        return stream_for($this->request->rawBody);
    }

    public function withBody(StreamInterface $body)
    {
        throw new NotSupportedException();
    }

    public function getRequestTarget()
    {
        return $this->request->url;
    }

    public function withRequestTarget($requestTarget)
    {
        throw new NotSupportedException();
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function withMethod($method)
    {
        throw new NotSupportedException();
    }

    public function getUri()
    {
        return $this->request->getUrl();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        throw new NotSupportedException();
    }

    public function getServerParams()
    {
        throw new NotSupportedException();
    }

    public function getCookieParams()
    {
        throw new NotSupportedException();
    }

    public function withCookieParams(array $cookies)
    {
        throw new NotSupportedException();
    }

    public function getQueryParams()
    {
        return $this->request->queryParams;
    }

    public function withQueryParams(array $query)
    {
        throw new NotSupportedException();
    }

    public function getUploadedFiles()
    {
        throw new NotSupportedException();
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        throw new NotSupportedException();
    }

    public function getParsedBody()
    {
        return $this->request->bodyParams;
    }

    public function withParsedBody($data)
    {
        throw new NotSupportedException();
    }

    public function getAttributes()
    {
        throw new NotSupportedException();
    }

    public function getAttribute($name, $default = null)
    {
        throw new NotSupportedException();
    }

    public function withAttribute($name, $value)
    {
        throw new NotSupportedException();
    }

    public function withoutAttribute($name)
    {
        throw new NotSupportedException();
    }
}
