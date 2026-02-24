<?php

namespace App\Tests\EventListener;

use App\Entity\User;
use App\EventListener\JWTCreatedListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use PHPUnit\Framework\TestCase;

/**
 * JWTCreatedListener tests
 *
 * Verifies that the listener injects the user's database id into the JWT payload
 * so that controllers can identify the user without an extra database query.
 */
final class JWTCreatedListenerTest extends TestCase
{
    private JWTCreatedListener $listener;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listener = new JWTCreatedListener();
    }

    public function testUserIdIsAddedToPayload(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(42);

        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn(['email' => 'alice@example.com', 'roles' => ['ROLE_USER']]);

        $capturedPayload = null;
        $event->method('setData')->willReturnCallback(function (array $data) use (&$capturedPayload): void {
            $capturedPayload = $data;
        });

        $this->listener->onJWTCreated($event);

        $this->assertArrayHasKey('id', $capturedPayload, "La clé 'id' doit être présente dans le payload JWT.");
        $this->assertEquals(42, $capturedPayload['id'], "L'id injecté dans le JWT doit correspondre à celui de l'entité User.");
    }

    public function testOriginalPayloadFieldsArePreserved(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);

        $originalPayload = [
            'email' => 'bob@example.com',
            'roles' => ['ROLE_USER'],
            'iat'   => 1700000000,
            'exp'   => 1700003600,
        ];

        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn($originalPayload);

        $capturedPayload = null;
        $event->method('setData')->willReturnCallback(function (array $data) use (&$capturedPayload): void {
            $capturedPayload = $data;
        });

        $this->listener->onJWTCreated($event);

        $this->assertEquals('bob@example.com', $capturedPayload['email'], "Le champ 'email' original du payload ne doit pas être altéré.");
        $this->assertEquals(['ROLE_USER'], $capturedPayload['roles'], "Le champ 'roles' original du payload ne doit pas être altéré.");
        $this->assertEquals(1700000000, $capturedPayload['iat'], "Le champ 'iat' original du payload ne doit pas être altéré.");
        $this->assertEquals(1700003600, $capturedPayload['exp'], "Le champ 'exp' original du payload ne doit pas être altéré.");
    }

    public function testSetDataIsCalledExactlyOnce(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(7);

        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn(['email' => 'charlie@example.com']);

        // Vérifie que setData est bien appelé une et une seule fois
        $event->expects($this->once())->method('setData');

        $this->listener->onJWTCreated($event);
    }

    public function testUserIdIsNullWhenUserIsNotPersisted(): void
    {
        // Un User non encore enregistré en base a getId() === null.
        // Le listener doit quand même ajouter la clé 'id' au payload (avec null).
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(null);

        $event = $this->createMock(JWTCreatedEvent::class);
        $event->method('getUser')->willReturn($user);
        $event->method('getData')->willReturn(['email' => 'dave@example.com']);

        $capturedPayload = null;
        $event->method('setData')->willReturnCallback(function (array $data) use (&$capturedPayload): void {
            $capturedPayload = $data;
        });

        $this->listener->onJWTCreated($event);

        $this->assertArrayHasKey('id', $capturedPayload, "La clé 'id' doit être présente même si l'utilisateur n'est pas persisté.");
        $this->assertNull($capturedPayload['id']);
    }

    protected function tearDown(): void
    {
        unset($this->listener);
    }
}
