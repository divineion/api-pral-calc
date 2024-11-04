<?php

namespace App\Tests\Entity;
use App\Entity\Event;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Envent entity tests
 *
 * this test verify the functionality of the Event entity
 *
 */

final class EventTest extends TestCase
{
    private ?Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = new Event();
    }

    /**
     * first tests session : getters and setters verification
     *
     * The following tests make sure that the entity's properties are correctly defined
     * and retrieved, including type checking.
     *
     */

    public function testUserSetting(): void
    {
        $this->assertNull($this->event->getUser());

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);
        $this->event->setUser($user);

        $this->assertInstanceOf(User::class, $this->event->getUser());
        $this->assertEquals(1, $this->event->getUser()->getId(), "Erreur : l'id de l'utilisateur qui a enregistré cet événement est 1");

        $this->event->setUser(null);
        $this->assertNull($this->event->getUser());
    }

    public function testDateFormatting(): void
    {
        $date = '2024-10-24 12:00:00';
        $this->assertInstanceOf(Event::class, $this->event->setDate($date));
        $this->assertEquals($date, $this->event->getDate(), "Erreur : la date attendue est '2024-10-16 02:00:00' ; date reçue sous la forme : '$date'.");
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $this->event->getDate(), "Le format de la date est incorrect.");
    }

    public function testTitleSetting(): void
    {
        $title = "Déjeuner";
        $this->assertInstanceOf(Event::class, $this->event->setTitle($title));
        $this->assertEquals($title, $this->event->getTitle(), "Erreur : le titre attendu est '$title' ; titre reçu : '{$this->event->getTitle()}'.");
    }

    public function testContentSetting(): void
    {
        $content = [4 => 50, 4111 => 200];
        $this->assertInstanceOf(Event::class, $this->event->setContent($content));
        $this->assertEquals($content, $this->event->getContent(), "Erreur : le contenu attendu est " . json_encode($content) . "; contenu reçu : " . json_encode($this->event->getContent()) . ".");
    }

    public function testTotalPralIndexSetting(): void
    {
        $total_pral_index = 16.50;
        $this->assertInstanceOf(Event::class, $this->event->setTotalPralIndex($total_pral_index));
        $this->assertEquals($total_pral_index, $this->event->getTotalPralIndex(), "Erreur : l'indice pral total attendu est $total_pral_index ; l'indice pral reçu est : '{$this->event->getTotalPralIndex()}'.");
    }

    protected function tearDown(): void
    {
        $this->event = null;
    }
}