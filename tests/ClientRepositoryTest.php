<?php

namespace App\Tests\repository;

use App\DataFixtures\ClientFixtures;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientRepositoryTest extends KernelTestCase
{

    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
        $this->databaseTool = self::$container->get(DatabaseToolCollection::class)->get();
    }

    public function getEntity(): Client
    {
        return (new Client())
            ->setPrenom("prenom")
            ->setNom("Nom")
            ->setEmail("user@domain.fr")
            ->setDateCreation(new \DateTime());
    }

    public function testCount()
    {
        self::bootKernel();
        // $this->databaseTool->loadFixtures([ClientFixtures::class]); AVEC FIXTURE
        $clients = self::$container->get(ClientRepository::class)->count([]);
        $this->assertEquals(4, $clients);
    }

    public function testCountByName()
    {
        self::bootKernel();
        $clients = self::$container->get(ClientRepository::class)->count(['nom' => 'Edward']);
        var_dump($clients);
        $this->assertEquals(2, $clients);
    }

    public function testCreate()
    {
        $clients = $this->getEntity();
        self::bootKernel();
        $error = self::$container->get('validator')->validate($clients);
        $this->assertCount(0, $error);
    }
}
