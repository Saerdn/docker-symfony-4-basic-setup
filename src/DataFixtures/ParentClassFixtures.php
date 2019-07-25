<?php

namespace App\DataFixtures;

use App\Entity\ParentClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ParentClassFixtures extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE = "parent";

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $parent = new ParentClass();
        $parent->setName('Fixture Parent I');
        $parent->addChild($this->getReference(ChildClassFixtures::REFERENCE));

        $parent = new ParentClass();
        $parent->setName('Fixture Parent II');
        $parent->addChild($this->getReference(ChildClassFixtures::REFERENCE));

        $manager->persist($parent);
        $manager->flush();

        $this->addReference(self::REFERENCE, $parent);
    }

    public function getOrder()
    {
        return 3;
    }
}
