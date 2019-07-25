<?php

namespace App\Tests;

use App\Entity\ChildClass;
use App\Entity\ParentClass;
use PHPUnit\Framework\TestCase;

class ChildClassTest extends TestCase
{
    public function testCorrectAmountOfParents()
    {
        $child = new ChildClass();
        $parent = new ParentClass();
        $parent_1 = new ParentClass();

        $child->addParent($parent);
        $child->addParent($parent_1);

        $this->assertEquals(2, count($child->getParents()));
    }
}
