<?php

declare(strict_types=1);

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\AddressBook;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAddressBookData extends AbstractFixture implements OrderedFixtureInterface
{
    public const FIRST_NAME = 'Mohammed';
    public const LAST_NAME = 'Elattar';
    public const EMAIL = 'mseel3ttar@gmail.com';
    public const CITY = 'Dusseldorf';
    public const COUNTRY = 'Germany';
    public function load(ObjectManager $manager)
    {
        $addressBook = new AddressBook();

        $addressBook->setFirstName(self::FIRST_NAME);
        $addressBook->setLastName(self::LAST_NAME);
        $addressBook->setEmail(self::EMAIL);
        $addressBook->setPhone('015115116957');
        $addressBook->setBirthDate(new \DateTime('1990-07-07'));
        $addressBook->setStreet('Ahnfeld straße');
        $addressBook->setHouseNo('14');
        $addressBook->setZip('40239');
        $addressBook->setCity(self::CITY);
        $addressBook->setCountry(self::COUNTRY);

        $manager->persist($addressBook);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
