<?php
declare(strict_types=1);

namespace prime\components;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Utils;
use herams\common\values\UserId;
use Lcobucci\JWT\Configuration;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use yii\web\Request;

class ApiProxy
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private ClientInterface $client,
        private Configuration $configuration
    )
    {
    }


    public function forwardRequestToCore(Request $request, UserId $user): ResponseInterface
    {
        return $this->forwardRequest(strtr($request->getAbsoluteUrl(), [
            'https://herams.test' => 'https://172.30.2.1',
            '/api-proxy/core' => '/api'
        ]), $request, $user);
    }

    private function forwardRequest(string $targetUri, Request $request, UserId $user): ResponseInterface
    {
        $token = $this->configuration->builder()
            ->issuedBy('https://app.herams.org')
            ->issuedAt(Carbon::now()->toDateTimeImmutable())
            ->canOnlyBeUsedAfter(Carbon::now()->toDateTimeImmutable())

            ->expiresAt(Carbon::now()->addMinute()->toDateTimeImmutable())
            ->permittedFor('https://api.herams.org')
            ->withClaim('userId', $user)
            ->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
        /**
         * 1. Create JTW with short lifetime
         * 2. Add bearer header
         */

        $upstreamRequest = $this->requestFactory->createRequest($request->getMethod(), $targetUri)->withBody(Utils::streamFor($request->getRawBody()));
        $headers = $request->getHeaders();

        foreach([
            'Content-Type', 'Accept', 'Cache-Control'
                ] as $forwardHeader) {
            if ($headers->has($forwardHeader) && !empty($headers->get($forwardHeader))) {
                $upstreamRequest = $upstreamRequest->withHeader($forwardHeader, $headers->get($forwardHeader));
            }
        }



        $upstreamRequest = $upstreamRequest
            ->withHeader('Host', 'herams.test')
            ->withHeader('Accept', 'application/json')
            ->withHeader('Authorization', "Bearer $token")
        ;
        $response = $this->client->sendRequest($upstreamRequest);
        return $response;

    }
}
