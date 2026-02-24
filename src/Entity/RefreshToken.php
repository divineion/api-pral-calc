<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens')]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(name: 'refresh_token', type: 'string', length: 128, unique: true)]
    protected $refreshToken;

    #[ORM\Column(name: 'username', type: 'string', length: 255)]
    protected $username;

    #[ORM\Column(name: 'valid', type: 'datetime')]
    protected $valid;
}
