<?php

namespace App\Repository;

use League\Bundle\OAuth2ServerBundle\Repository\AccessTokenRepository as BaseAccessTokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use App\Entity\AccessToken as AccessTokenEntity;

final class AccessTokenRepository implements AccessTokenRepositoryInterface
{

    private BaseAccessTokenRepository $baseAccessTokenRepository;

    public function __construct(BaseAccessTokenRepository $baseAccessTokenRepository)
    {
        $this->baseAccessTokenRepository = $baseAccessTokenRepository;
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessTokenEntity
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);

        foreach($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->baseAccessTokenRepository->persistNewAccessToken($accessTokenEntity);
    }

    public function revokeAccessToken($tokenId)
    {
        $this->baseAccessTokenRepository->revokeAccessToken($tokenId);
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->baseAccessTokenRepository->isAccessTokenRevoked($tokenId);
    }
}