<?php

namespace App\Tests\repository;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FactureRepositoryTest extends KernelTestCase
{

    /** @var AbstractDatabaseTool */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        static::bootKernel();
        $this->databaseTool = self::$container->get(DatabaseToolCollection::class)->get();
    }

    public function getEntity(): Facture
    {
        return (new Facture())
            ->setRefClient(1)
            ->setDateEmission(new \DateTime())
            ->setStatutPaiement(11)
            ->setDatePaiement(new \DateTime())
            ->setPrixTotal(500);
    }

    public function testCount()
    {
        self::bootKernel();
        // $this->databaseTool->loadFixtures([ClientFixtures::class]); AVEC FIXTURE
        $factures = self::$container->get(FactureRepository::class)->count([]);
        $this->assertEquals(5, $factures);
    }


    public function assertHasErrors(Facture $factures, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($factures);
        $this->assertCount($number, $error);
    }

    public function testInvalidCreate()
    {
        $this->assertHasErrors($this->getEntity()->setRefClient('11111'), 1);
    }

    public function testInvalidCreateNE()
    {
        $this->assertHasErrors($this->getEntity()->setRefClient(''), 1);
    }

    public function testTotalPrice()
    {
        self::bootKernel();
        // $mock = [
        //     'id' => 3,
        //     'refClient' => '3',
        //     'dateEmission' => '2020-06-06',
        //     'statutPaiement' => 1,
        //     'datePaiement' => '2020-06-06',
        //     'prixTotal' => 900


        // ]; // trnansformer en objet

        // $mock1 = (object) $mock;
        //var_dump($mock1);
        $factures = self::$container->get(FactureRepository::class)->FindOneBy(['id' => '3']);
        $factures = (array)$factures;
        var_dump($factures);
        $this->assertContains(650, $factures);
    }
}
