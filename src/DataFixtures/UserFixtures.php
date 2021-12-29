<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'firstName' => 'Yusuf',
                'lastName' => 'Borucu',
                'username' => 'yusufborucu',
                'email' => 'yusufborucu@gmail.com'              
            ],
            [
                'firstName' => 'Ali',
                'lastName' => 'Veli',
                'username' => 'aliveli',
                'email' => 'aliveli@gmail.com'              
            ],
            [
                'firstName' => 'Ahmet',
                'lastName' => 'Mehmet',
                'username' => 'ahmetmehmet',
                'email' => 'ahmetmehmet@gmail.com'              
            ]
        ];

        foreach ($users as $item) {
            $user = new User();
            $user->setFirstName($item['firstName']);
            $user->setLastName($item['lastName']);
            $user->setUsername($item['username']);
            $user->setEmail($item['email']);
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $manager->persist($user);
            $manager->flush();
        }
    }
}
