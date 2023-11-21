<?php

declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class UserDenormalizer implements \Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface, \Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    private const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';
    private UserPasswordHasherInterface $passwordHasher;
    private Security $security;

    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return User::class === $type && !isset($context[self::ALREADY_CALLED]);
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        /* @var User $user */
        $user = $this->security->getUser();

        $context[self::ALREADY_CALLED] = true;

        if (isset($data['password'])) {
            $data['password'] = $this->passwordHasher->hashPassword($user, $data['password']);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}
