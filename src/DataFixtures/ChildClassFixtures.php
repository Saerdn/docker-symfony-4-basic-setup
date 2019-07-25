<?php

namespace App\DataFixtures;

use App\Entity\ChildClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ChildClassFixtures extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE = "child";

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $child = new ChildClass();
        $child->setName('Fixture Child');

        $manager->persist($child);
        $manager->flush();

        $this->addReference(self::REFERENCE, $child);
    }

    public function getOrder()
    {
        return 2;
    }
}
