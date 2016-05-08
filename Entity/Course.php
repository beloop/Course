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

namespace Beloop\Component\Course\Entity;

use DateTime;

use Doctrine\Common\Collections\Collection;

use Beloop\Component\Core\Entity\Traits\DateTimeTrait;
use Beloop\Component\Core\Entity\Traits\EnabledTrait;
use Beloop\Component\Core\Entity\Traits\IdentifiableTrait;
use Beloop\Component\Course\Entity\Interfaces\CourseInterface;
use Beloop\Component\Course\Entity\Interfaces\LessonInterface;
use Beloop\Component\Language\Entity\Traits\LanguageTrait;

/**
 * Class Course entity.
 */
class Course implements CourseInterface
{
    use IdentifiableTrait,
        LanguageTrait,
        EnabledTrait,
        DateTimeTrait;

    protected $code;
    protected $name;
    protected $description;
    protected $lessons;
    protected $startDate;
    protected $endDate;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return $this Self object
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this Self object
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return $this Self object
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * @param Collection $lessons
     * @return $this Self object
     */
    public function setLessons(Collection $lessons)
    {
        $this->lessons = $lessons;
        return $this;
    }

    /**
     * @param LessonInterface $lesson
     * @return $this Self object
     */
    public function addLesson(LessonInterface $lesson)
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);

            $lesson->setCourse($this);
        }

        return $this;
    }

    /**
     * @param LessonInterface $lesson
     * @return $this Self object
     */
    public function removeLesson(LessonInterface $lesson)
    {
        $this
            ->lessons
            ->removeElement($lesson);

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     * @return $this Self object
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     * @return $this Self object
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Course is accessible by today
     */
    public function isAvailable()
    {
        $today = new DateTime();

        return $today >= $this->getStartDate() && $today <= $this->getEndDate();
    }
}