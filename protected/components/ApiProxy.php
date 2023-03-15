<?php

declare(strict_types=1);

namespace prime\components;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Utils;
use herams\common\values\UserId;
use Lcobucci\JWT\Configuration;
use prime\objects\ApiConfiguration;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use yii\web\Request;

final class ApiProxy
{
    public function __construct(
        private readonly RequestFactoryInterface $requestFactory,
        private readonly UriFactoryInterface $uriFactory,
        private readonly ClientInterface $client,
        private readonly Configuration $configuration,
        private readonly ApiConfiguration $apiConfiguration
    ) {
    }

    public function forwardRequestToCore(Request $request, UserId $user, string $language): ResponseInterface
    {
        $originalUri = $this->uriFactory->createUri($request->getAbsoluteUrl());
        $target = $this->uriFactory->createUri($request->getAbsoluteUrl())
            ->withPath(strtr($originalUri->getPath(), ['/api-proxy/core/' => '/']))
            ->withHost($this->apiConfiguration->host)

        ;
        return $this->forwardRequest($target, $request, $user, $language);
    }

    private function forwardRequest(UriInterface $target, Request $request, UserId $user, string $language): ResponseInterface
    {
        \Yii::beginProfile($target->__toString(), 'ApiProxy::forwardRequest');
        $token = $this->configuration->builder()
            ->issuedBy('https://app.herams.org')
            ->issuedAt(Carbon::now()->toDateTimeImmutable())
            ->canOnlyBeUsedAfter(Carbon::now()->toDateTimeImmutable())

            ->expiresAt(Carbon::now()->addHour()->toDateTimeImmutable())
            ->permittedFor('https://api.herams.org')
            ->withClaim('userId', $user)
            ->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
        /**
         * 1. Create JTW with short lifetime
         * 2. Add bearer header
         */
        $upstreamRequest = $this->requestFactory->createRequest($request->getMethod(), $target)->withBody(Utils::streamFor($request->getRawBody()));
        $headers = $request->getHeaders();

        foreach ([
            'Content-Type', 'Accept', 'Cache-Control'
        ] as $forwardHeader) {
            if ($headers->has($forwardHeader) && ! empty($headers->get($forwardHeader))) {
                $upstreamRequest = $upstreamRequest->withHeader($forwardHeader, $headers->get($forwardHeader));
            }
        }

        $upstreamRequest = $upstreamRequest
            ->withHeader('Authorization', "Bearer $token")
            ->withHeader('Accept-language', $language);
        $response = $this->client->sendRequest($upstreamRequest);
        \Yii::endProfile($target->__toString(), 'ApiProxy::forwardRequest');
        return $response;
    }
}
