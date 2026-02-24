<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * LoginEndpointTest
 *
 * Functional tests for POST /api/login_check.
 * Verifies the full HTTP cycle: authentication, status codes,
 * and the enriched response format { token, user }.
 *
 * Each test creates a fresh HTTP client but shares the same test user,
 * which is inserted before the test class runs and deleted after.
 */
final class LoginEndpointTest extends WebTestCase
{
    private const TEST_EMAIL    = 'test-login-phpunit@pral.test';
    private const TEST_PASSWORD = 'TestPassword1';
    private const TEST_USERNAME = 'phpunit-login';

    /**
     * Creates the test user in the database before each test.
     * Cleans up any leftover from a previously interrupted test run.
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        /** @var UserPasswordHasherInterface $hasher */
        $hasher = $container->get(UserPasswordHasherInterface::class);

        $existing = $em->getRepository(User::class)->findOneBy(['email' => self::TEST_EMAIL]);
        if ($existing) {
            $em->remove($existing);
            $em->flush();
        }

        $user = new User();
        $user->setEmail(self::TEST_EMAIL);
        $user->setUsername(self::TEST_USERNAME);
        $user->setPassword($hasher->hashPassword($user, self::TEST_PASSWORD));
        $user->setIsVerified(true);
        $user->setMemberSince((new \DateTimeImmutable())->format('Y-m-d'));

        $em->persist($user);
        $em->flush();

        // Shut down the kernel so createClient() can boot a fresh isolated instance
        self::ensureKernelShutdown();
    }

    /**
     * Deletes the test user after each test to leave the database clean.
     */
    protected function tearDown(): void
    {
        self::bootKernel();
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)->findOneBy(['email' => self::TEST_EMAIL]);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }

        parent::tearDown();
    }

    // --- Status codes ---

    public function testLoginWithValidCredentialsReturns200(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginWithWrongPasswordReturns401(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => self::TEST_EMAIL, 'password' => 'mauvais-mot-de-passe'])
        );

        $this->assertResponseStatusCodeSame(401);
    }

    public function testLoginWithUnknownEmailReturns401(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'inconnu@pral.test', 'password' => self::TEST_PASSWORD])
        );

        $this->assertResponseStatusCodeSame(401);
    }

    // --- Response structure ---

    public function testLoginResponseContainsTokenAndUserKeys(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data, "La réponse doit contenir un JWT.");
        $this->assertArrayHasKey('user', $data, "La réponse doit contenir un objet 'user'.");
        $this->assertIsString($data['token'], "Le token doit être une chaîne de caractères.");
        $this->assertIsArray($data['user'], "Le payload utilisateur doit être un tableau.");
    }

    public function testLoginResponseUserPayloadContainsExpectedFields(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD])
        );

        $user = json_decode($client->getResponse()->getContent(), true)['user'];

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('roles', $user);
        $this->assertArrayHasKey('isVerified', $user);
    }

    public function testLoginResponseUserPayloadContainsCorrectValues(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/login_check',
            [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => self::TEST_EMAIL, 'password' => self::TEST_PASSWORD])
        );

        $user = json_decode($client->getResponse()->getContent(), true)['user'];

        $this->assertIsInt($user['id'], "L'id doit être un entier (clé primaire Doctrine).");
        $this->assertEquals(self::TEST_EMAIL, $user['email']);
        $this->assertEquals(self::TEST_USERNAME, $user['username']);
        $this->assertTrue($user['isVerified']);
        $this->assertContains('ROLE_USER', $user['roles']);
    }
}
