<?php

namespace App\DataFixtures;

use App\Entity\Structure;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->encoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
            $user->setEmail('admin@admin.admin');
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setPassword($this->encoder->encodePassword($user, 'admin'));
            $manager->persist($user);

            $manager->flush();
            
    }
}
