<?php

declare(strict_types=1);

namespace Tests\AppBundle\Controller;

use App\Kernel;
use AppBundle\DataFixtures\ORM\LoadAddressBookData;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class AddressBookControllerTest extends WebTestCase
{
    protected static function getPhpUnitXmlDir(): string
    {
        return Kernel::class;
    }

    public function setUp(): void
    {
        self::bootKernel();
        $this->truncateEntities();
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $this->loadFixtures([LoadAddressBookData::class]);
        $crawler = $client->request('GET', '/addressbook');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Address Book', $crawler->filter('h1')->text());
        $this->assertCount(2, $crawler->filter('table')->children());

        $rowContent = $crawler->filter('table > tr')->last()->text();

        $this->assertContains(LoadAddressBookData::FIRST_NAME, $rowContent);
        $this->assertContains(LoadAddressBookData::LAST_NAME, $rowContent);
        $this->assertContains(LoadAddressBookData::EMAIL, $rowContent);
        $this->assertContains(LoadAddressBookData::CITY, $rowContent);
        $this->assertContains(LoadAddressBookData::COUNTRY, $rowContent);
    }

    private function truncateEntities(): void
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    private function getEntityManager(): EntityManager
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
