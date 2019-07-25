<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface $passwordEncoder
     */
    protected $passwordEncoder;

    public const TEST_ADMIN_USER_REFERENCE = "test-admin-user";
    public const TEST_USER_REFERENCE = "test-user";

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->userData() as $userDate) {
            $user = new User();
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $userDate['password'])
            );
            $user->setEmail($userDate['mail']);
            $user->setFirstname($userDate['firstname']);
            $user->setSurname($userDate['surname']);
            $user->setIsActive($userDate['is_active']);
            $user->setRoles($userDate['roles']);

            $manager->persist($user);
            $manager->flush();

            if (!empty($userDate['reference_name'])) {
                $this->addReference($userDate['reference_name'], $user);
            }
        }
    }

    protected function userData()
    {
        return [
          [
              'firstname' => 'Test',
              'surname' => 'User',
              'password' => 'qwertz',
              'mail' => 'testuser@docker-symfony.de',
              'is_active' => true,
              'roles' => [],
              'reference_name' => self::TEST_USER_REFERENCE
          ],
          [
              'firstname' => 'Test',
              'surname' => 'User II',
              'password' => 'asdf',
              'mail' => 'testuser2@docker-symfony.de',
              'is_active' => true,
              'roles' => [],
              'reference_name' => null
          ],
          [
              'firstname' => 'Test',
              'surname' => 'Admin',
              'password' => 'abcd',
              'mail' => 'testadmin@docker-symfony.de',
              'is_active' => true,
              'roles' => ['ROLE_ADMIN'],
              'reference_name' => self::TEST_ADMIN_USER_REFERENCE
          ]
        ];
    }

    public function getOrder()
    {
        return 1;
    }
}
