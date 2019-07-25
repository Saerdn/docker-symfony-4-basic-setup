<?php

namespace App\Tests;

use App\Entity\ChildClass;
use App\Entity\ParentClass;
use PHPUnit\Framework\TestCase;

class ParentClassTest extends TestCase
{
    public function testCorrectAmountOfChildren()
    {
        $parent = new ParentClass();
        $child = new ChildClass();
        $child_2 = new ChildClass();

        $parent->addChild($child);
        $parent->addChild($child_2);

        $this->assertEquals(2, count($parent->getChildren()));
    }
}
