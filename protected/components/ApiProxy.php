<?php
declare(strict_types=1);

namespace prime\components;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Utils;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\None;
use League\Tactician\Handler\Locator\InMemoryLocator;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use yii\web\IdentityInterface;
use yii\web\Request;
use Lcobucci\JWT\Configuration;
use yii\web\Response;

class ApiProxy
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private ClientInterface $client,
        private Configuration $configuration
    )
    {
    }


    public function forwardRequestToCore(Request $request, IdentityInterface $user): ResponseInterface
    {
        return $this->forwardRequest(strtr($request->getAbsoluteUrl(), [
            'https://herams.test' => 'https://172.30.2.1',
            '/api-proxy/core' => '/api'
        ]), $request, $user);
    }

    private function forwardRequest(string $targetUri, Request $request, IdentityInterface $user): ResponseInterface
    {
        $token = $this->configuration->builder()
            ->issuedBy('https://app.herams.org')
            ->issuedAt(Carbon::now()->toDateTimeImmutable())
            ->canOnlyBeUsedAfter(Carbon::now()->toDateTimeImmutable())

            ->expiresAt(Carbon::now()->addMinute()->toDateTimeImmutable())
            ->permittedFor('https://api.herams.org')
            ->withClaim('userId', $user->getId())
            ->getToken($this->configuration->signer(), $this->configuration->signingKey())
            ->toString();
        /**
         * 1. Create JTW with short lifetime
         * 2. Add bearer header
         */


        $request = $this->requestFactory->createRequest($request->getMethod(), $targetUri)
            ->withHeader('Host', 'herams.test')
            ->withAddedHeader('Authorization', "Bearer $token")
            ->withBody(Utils::streamFor($request->getRawBody()))
        ;
        $response = $this->client->sendRequest($request);
        foreach($request->getHeaders() as $key => $value) {
            $response = $response->withAddedHeader("X-Upstream-Request-$key", $value);
        }
        return $response;

    }
}
