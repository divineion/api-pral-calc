<?php

namespace App\Tests\EventListener;

use App\Entity\User;
use App\EventListener\AuthenticationSuccessListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AuthenticationSuccessListener tests
 *
 * Verifies that the listener correctly enriches the /api/login_check response
 * with the authenticated user's payload alongside the JWT token.
 */
final class AuthenticationSuccessListenerTest extends TestCase
{
    private AuthenticationSuccessListener $listener;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listener = new AuthenticationSuccessListener();
    }

    public function testResponseContainsUserPayload(): void
    {
        $user = new User();
        $user->setEmail('alice@example.com');
        $user->setUsername('alice');
        $user->setIsVerified(true);

        $event = new AuthenticationSuccessEvent(
            ['token' => 'some-jwt-token'],
            $user,
            new Response()
        );

        $this->listener->onAuthenticationSuccess($event);

        $data = $event->getData();

        $this->assertArrayHasKey('user', $data, "La réponse doit contenir une clé 'user'.");
        $this->assertEquals('alice@example.com', $data['user']['email']);
        $this->assertEquals('alice', $data['user']['username']);
        $this->assertTrue($data['user']['isVerified']);
        $this->assertContains('ROLE_USER', $data['user']['roles']);
    }

    public function testTokenIsPreservedInResponse(): void
    {
        $user = new User();
        $user->setEmail('bob@example.com');

        $event = new AuthenticationSuccessEvent(
            ['token' => 'preserved-token'],
            $user,
            new Response()
        );

        $this->listener->onAuthenticationSuccess($event);

        $this->assertArrayHasKey('token', $event->getData(), "La réponse doit toujours contenir le token JWT.");
        $this->assertEquals('preserved-token', $event->getData()['token']);
    }

    public function testListenerIgnoresNonAppUserInstance(): void
    {
        // Si l'objet user n'est pas App\Entity\User (ex: autre provider),
        // le listener doit retourner sans modifier les données.
        $foreignUser = $this->createMock(UserInterface::class);

        $originalData = ['token' => 'token-intact'];
        $event = new AuthenticationSuccessEvent($originalData, $foreignUser, new Response());

        $this->listener->onAuthenticationSuccess($event);

        $this->assertSame($originalData, $event->getData(), "Les données ne doivent pas être modifiées pour un utilisateur non reconnu.");
    }

    public function testUserIdIsNullWhenNotPersisted(): void
    {
        // Un User non persisté (pas encore en base) a un id null.
        // Le listener doit quand même inclure la clé 'id' dans la réponse.
        $user = new User();
        $user->setEmail('charlie@example.com');

        $event = new AuthenticationSuccessEvent(['token' => 'token'], $user, new Response());

        $this->listener->onAuthenticationSuccess($event);

        $data = $event->getData();
        $this->assertArrayHasKey('id', $data['user'], "La clé 'id' doit être présente dans le payload utilisateur.");
        $this->assertNull($data['user']['id']);
    }

    public function testUnverifiedUserIsCorrectlyReported(): void
    {
        $user = new User();
        $user->setEmail('unverified@example.com');
        // isVerified est false par défaut

        $event = new AuthenticationSuccessEvent(['token' => 'token'], $user, new Response());

        $this->listener->onAuthenticationSuccess($event);

        $this->assertFalse($event->getData()['user']['isVerified'], "Un utilisateur non vérifié doit avoir isVerified à false.");
    }

    protected function tearDown(): void
    {
        unset($this->listener);
    }
}
