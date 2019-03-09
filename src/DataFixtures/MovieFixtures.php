<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i<3; $i++){

            $category = new Category();
            $category -> setTitle($faker->sentence())
                      -> setDescription($faker ->paragraph());

            $manager->persist($category);
        }

        $content = '<p>'. join($faker->paragraphs(5), '</p><p></p>');

        for ($j = 1; $j < mt_rand(4,10); $j++){

            $movies = new Movie();
            $movies ->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('- 6 months'))
                    ->setCategory($category);

            $manager->persist($movies);
        }

        $content = '<p>' . join($faker->paragraphs(2), '</p><p></p>');

        $now = new \DateTime();
        $interval = $now->diff($movies->getCreatedAt());
        $days = $interval->days;
        $minimum = '-' . $days . 'days';

        for($k = 1; $k < mt_rand(4,10); $k++){

            $comment = new Comment();
            $comment ->setAuthor($faker->name())
                     ->setContent($content)
                     ->setCreatedAt($faker->dateTimeBetween($minimum))
                     ->setMovie($movies);

            $manager->persist($comment);
        }

        $manager->flush();
    }
}
