<?php

declare(strict_types=1);

namespace Tests\AppBundle\Controller;

use App\Kernel;
use AppBundle\DataFixtures\ORM\LoadAddressBookData;
use AppBundle\Entity\AddressBook;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

class AddressBookControllerTest extends WebTestCase
{
    private const UPDATED_FORM_VALUES = [
        'first_name' => 'Mohammed updated',
        'last_name' => 'Elattar updated',
        'email' => 'update@email.com',
        'city' => 'Cairo',
        'country' => 'Egypt'
    ];

    protected static function getPhpUnitXmlDir(): string
    {
        return Kernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
        $this->truncateEntities();
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $this->loadFixtures([LoadAddressBookData::class]);

        $this->assertIndexPageContainsCorrectData($client);
    }

    public function testAddAddressBook(): void
    {
        $addressRepo = $this->getEntityManager()->getRepository(AddressBook::class);
        $allAddressBooks = $addressRepo->findAll();

        $this->assertCount(0, $allAddressBooks, 'No Address Books exist in the DB');

        $client = static::createClient();
        $crawler = $client->request('GET', '/addressbook/new');
        $this->assertStatusCode(200, $client);

        $form = $this->prepareAddressBookForm($crawler);
        $client->submit($form);
        $client->followRedirect();
        $response = $client->getResponse()->getContent();

        $this->assertContains(LoadAddressBookData::FIRST_NAME, $response);
        $this->assertContains(LoadAddressBookData::LAST_NAME, $response);
        $this->assertContains(LoadAddressBookData::EMAIL, $response);
        $this->assertContains(LoadAddressBookData::CITY, $response);
        $this->assertContains(LoadAddressBookData::COUNTRY, $response);

        $this->assertIndexPageContainsCorrectData($client);
        $allAddressBooks = $addressRepo->findAll();
        /**
         * @var AddressBook $insertedAddressBook
         */
        $insertedAddressBook = $allAddressBooks[0];
        $this->assertCount(1, $allAddressBooks, 'Address Book inserted in the DB');

        $this->assertSame(LoadAddressBookData::FIRST_NAME, $insertedAddressBook->getFirstName());
        $this->assertSame(LoadAddressBookData::LAST_NAME, $insertedAddressBook->getLastName());
        $this->assertSame(LoadAddressBookData::EMAIL, $insertedAddressBook->getEmail());
        $this->assertSame(LoadAddressBookData::CITY, $insertedAddressBook->getCity());
        $this->assertSame(LoadAddressBookData::COUNTRY, $insertedAddressBook->getCountry());
    }

    public function testEditAddressBook(): void
    {
        $this->loadFixtures([LoadAddressBookData::class]);
        $currentAddressBooks = $this->getEntityManager()->getRepository(AddressBook::class)->findAll();

        $this->assertCount(1, $currentAddressBooks);
        $client = static::createClient();

        $crawler = $client->request('GET', sprintf('/addressbook/%d/edit', $currentAddressBooks[0]->getId()));
        $this->assertStatusCode(200, $client);

        $form = $this->prepareAddressBookForm($crawler, self::UPDATED_FORM_VALUES);
        $client->submit($form);
        $client->followRedirect();
        $response = $client->getResponse()->getContent();

        $this->assertContains(self::UPDATED_FORM_VALUES['first_name'], $response);
        $this->assertContains(self::UPDATED_FORM_VALUES['last_name'], $response);
        $this->assertContains(self::UPDATED_FORM_VALUES['email'], $response);
        $this->assertContains(self::UPDATED_FORM_VALUES['city'], $response);
        $this->assertContains(self::UPDATED_FORM_VALUES['country'], $response);

        $this->assertIndexPageContainsCorrectData($client, self::UPDATED_FORM_VALUES);
        $currentAddressBooks = $this->getEntityManager()->getRepository(AddressBook::class)->findAll();
        $this->assertCount(1, $currentAddressBooks, 'Count of records still the same after update');
    }

    private function assertIndexPageContainsCorrectData(Client $client, array $formData = []): void
    {
        $crawler = $client->request('GET', '/addressbook');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Address Book', $crawler->filter('h1')->text());
        $this->assertCount(2, $crawler->filter('table')->children());

        $rowContent = $crawler->filter('table > tr')->last()->text();

        $this->assertContains($formData['first_name'] ?? LoadAddressBookData::FIRST_NAME, $rowContent);
        $this->assertContains($formData['last_name'] ?? LoadAddressBookData::LAST_NAME, $rowContent);
        $this->assertContains($formData['email'] ?? LoadAddressBookData::EMAIL, $rowContent);
        $this->assertContains($formData['city'] ?? LoadAddressBookData::CITY, $rowContent);
        $this->assertContains($formData['country'] ?? LoadAddressBookData::COUNTRY, $rowContent);
    }

    private function prepareAddressBookForm(Crawler $crawler, array $formData = []): Form
    {
        $form = $crawler->selectButton('Save')->form();
        $form->setValues([
            'address_book_form[firstName]' => $formData['first_name'] ?? LoadAddressBookData::FIRST_NAME,
            'address_book_form[lastName]' => $formData['last_name'] ?? LoadAddressBookData::LAST_NAME,
            'address_book_form[email]' => $formData['email'] ?? LoadAddressBookData::EMAIL,
            'address_book_form[phone]' => '015115116957',
            'address_book_form[birthDate]' => '1990-07-07',
            'address_book_form[street]' => 'Mohammed',
            'address_book_form[houseNo]' => 'Mohammed',
            'address_book_form[zip]' => 'Mohammed',
            'address_book_form[city]' => $formData['city'] ?? LoadAddressBookData::CITY,
            'address_book_form[country]' => $formData['country'] ?? LoadAddressBookData::COUNTRY,
        ]);

        return $form;
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
