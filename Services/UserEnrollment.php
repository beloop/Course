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

namespace Beloop\Component\Course\Services;

use Beloop\Component\Core\Services\ObjectDirector;
use Beloop\Component\Course\Entity\Interfaces\CourseInterface;
use Beloop\Component\Course\Factory\CourseEnrolledUserFactory;
use Beloop\Component\User\Entity\Interfaces\UserInterface;
use Beloop\Component\User\EventDispatcher\Interfaces\EnrolmentEventDispatcherInterface;
use Beloop\Component\User\Services\UserManager;
use Beloop\Component\User\Transformer\ExtractUsersFromCSV;

/**
 * Class UserEnrollment
 */
class UserEnrollment
{
    /**
     * @var ExtractUsersFromCSV
     */
    private $transformer;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ObjectDirector
     */
    private $courseDirector;

    /**
     * @var CourseEnrolledUserFactory
     */
    private $enrollmentFactory;

    /**
     * @param ExtractUsersFromCSV $transformer
     * @param UserManager $userManager
     * @param ObjectDirector $courseDirector
     * @param CourseEnrolledUserFactory $enrollmentFactory
     * @param EnrolmentEventDispatcherInterface $enrolmentEventDispatcher
     */
    public function __construct(
        ExtractUsersFromCSV $transformer,
        UserManager $userManager,
        ObjectDirector $courseDirector,
        ObjectDirector $userDirector,
        CourseEnrolledUserFactory $enrollmentFactory,
        EnrolmentEventDispatcherInterface $enrolmentEventDispatcher
    ) {
        $this->transformer = $transformer;
        $this->userManager = $userManager;
        $this->courseDirector = $courseDirector;
        $this->userDirector = $userDirector;
        $this->enrollmentFactory = $enrollmentFactory;
        $this->enrolmentEventDispatcher = $enrolmentEventDispatcher;
    }

    /**
     * Enrol users from CSV file on given course
     *
     * @param CourseInterface $course
     * @param $csv
     */
    public function enrolFromCSV(CourseInterface $course, $csv)
    {
        // Extract users from CSV file
        $users = $this->transformer->extractUsers($csv);

        // Insert or update users on database
        $procesedUsers = $this->userManager->insertOrUpdate($users);

        // Enroll users on course
        foreach ($procesedUsers as $user) {
            $user->setLanguage($course->getLanguage());
            $user->addRole('ROLE_USER');
            $this->userDirector->save($user);

            $enrollment = $this->enrollmentFactory->create();
            $enrollment->setUser($user);
            $enrollment->setCourse($course);
            $course->enrollUser($enrollment);

            $this
              ->enrolmentEventDispatcher
              ->dispatchOnEnrolmentEvent(
                  $user,
                  $course
              );
        }

        $this->courseDirector->save($course);
    }

    /**
     * Enrol an user on a given course
     *
     * @param CourseInterface $course
     * @param UserInterface $user
     */
    public function enrol(CourseInterface $course, UserInterface $user) {
        $course->enrollUser($user);
        $this->courseDirector->save($course);
    }
}
