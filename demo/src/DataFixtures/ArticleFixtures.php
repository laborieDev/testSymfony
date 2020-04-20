<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 catégories fakées
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());
            $manager->persist($category);

            //Créer entre 4 et 6 articles

            for ($j = 1; $j <= mt_rand(4,6); $j++){
                $article = new Article();

                $content = "<p>";
                $content .= join($faker->paragraphs(5),'</p><p>');
                $content .= "</p>";

                $article -> setTitle($faker->sentence())
                         -> setContent($content)
                         -> setImage("http://www.aterplo.fr/wp-content/uploads/2020/03/LOGO-ATERPLO.png")
                         -> setCreateAt($faker->dateTimeBetween('-6 months'))
                         -> setCategory($category);

                $manager -> persist($article);

                for($k=1; $k <= mt_rand(4,10); $k++){
                    $comment = new Comment();

                    $content = "<p>".join($faker->paragraphs(2),'</p><p>')."</p>";
                    
                    $interval = (new \DateTime())->diff($article->getCreateAt());
                    $days = '-'.$interval->days.' days';

                    $comment -> setAutor($faker->name())
                             -> setContent($content)
                             -> setCreateAt($faker->dateTimeBetween($days))
                             -> setArticle($article);
                    
                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
