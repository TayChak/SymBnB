<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

/**
 * class AppFixtures
 */
class AppFixtures implements FixtureInterface
{
    /**
     * @param ObjectManager $manager
     * 
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();
            
            $title        = $faker->sentence(4);
            $coverImage   = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content      = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setRooms(mt_rand(1,4))
                ->setPrice(mt_rand(40,200));

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
