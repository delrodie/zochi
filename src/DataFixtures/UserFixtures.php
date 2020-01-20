<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername("delrodie");
        $user->setEmail("delrodieamoikon@gmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'dev@admin20'));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}
