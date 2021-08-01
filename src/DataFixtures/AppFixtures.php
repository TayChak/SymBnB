<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * class AppFixtures
 */
class AppFixtures implements FixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    
    /**
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    /**
     * @param ObjectManager $manager
     * 
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        // Create Role Admin
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        //Admin User
        $adminUser = new User();
        $adminUser->setFirstName('Tayeb')
                ->setLastName('Chakroun')
                ->setEmail('tayebchakroune@gmail.com')
                ->setIntroduction($faker->sentence(4))
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                ->setPicture('https://randomuser.me/api/portraits/men/'.$faker->numberBetween(1, 99) . '.jpg')
                ->addUserRole($adminRole);

        $manager->persist($adminUser);

        //default User
        $users = [];
        $genres = ['male', 'female'];
        
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $picId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre === 'male' ? 'men/' :'women/') . $picId;

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence(4))
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($this->encoder->encodePassword($user, 'password'))
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();

            $ad->setTitle($faker->sentence(4))
                ->setCoverImage($faker->imageUrl(1000,350))
                ->setIntroduction($faker->paragraph(2))
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                ->setRooms(mt_rand(1,4))
                ->setPrice(mt_rand(40,200))
                ->setAuthor($users[mt_rand(0, count($users) - 1)]);
                

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence(4))
                      ->setAd($ad);

                $manager->persist($image);
            }
        
            $manager->persist($ad);
        }

        $manager->flush();
    }
}
