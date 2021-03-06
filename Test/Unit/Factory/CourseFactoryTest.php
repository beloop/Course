<?php

/*
 * This file is part of the Beloop package.
 *
 * Copyright (c) 2016 AHDO Studio B.V.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Arkaitz Garro <arkaitz.garro@gmail.com>
 */

namespace Beloop\Component\Course\Test\Unit\Factory;

use PHPUnit\Framework\TestCase;

use Beloop\Component\Course\Factory\CourseFactory;

class CourseFactoryTest extends TestCase
{
    private $course;

    public function setUp(): void
    {
        $factory = new CourseFactory();
        $factory->setEntityNamespace('Beloop\Component\Course\Entity\Course');
        $this->course = $factory->create();
    }

    public function testDefaultValues() {
        $this->assertEquals(true, $this->course->isEnabled());
        $this->assertEquals(false, $this->course->isDemo());
        $this->assertEmpty($this->course->getEnrollments());
    }
}
