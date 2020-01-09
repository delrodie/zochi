<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
        // Enregistrement de 10 articles dans le système
        $summary = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin purus massa, tempor at nunc sagittis, fringilla mollis nunc. Proin posuere turpis nec tellus varius, et condimentum mi pretium. Suspendisse cursus feugiat massa in consectetur.";
        $content = "Maecenas ut pharetra arcu. Integer viverra odio quam, nec congue magna lobortis posuere. Donec volutpat magna nec risus porta auctor. Pellentesque vehicula enim ut massa mollis, in pretium nunc tempor. Morbi libero urna, ultrices non malesuada ac, malesuada non nulla. Fusce a diam est. Aenean nunc magna, ornare quis lacinia placerat, maximus vel tellus. Integer hendrerit dui vitae ligula semper sollicitudin.
        
Aenean fermentum pretium malesuada. Nulla facilisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Integer scelerisque viverra sapien, eu rhoncus urna ullamcorper at. Proin est erat, tristique quis nulla sit amet, vehicula cursus purus. Morbi sed laoreet metus. Vestibulum eget tortor aliquam, bibendum magna et, mollis quam. Suspendisse potenti. Nam eu posuere nibh, a auctor nulla. Fusce at nibh velit. Nullam at gravida erat, in faucibus orci. Vivamus non faucibus eros, vel maximus purus. Fusce quis nibh tortor. Nunc mollis rutrum tortor ac faucibus. Fusce id turpis vulputate, ultricies enim quis, tristique nisi.";
        //$date = new \DateTime('Y-m-d H:i:s', time());

        for ($i=0; $i<10; $i++){
            $article = new Article();
            $date = new \DateTime();
            $article->setTitle("Le titre de mon article N°: ".$i);
            $article->setSummary($i.' - '.$summary);
            $article->setContent($i.' - '.$content);
            $article->setTags("testx,tdtste");
            $article->setStatut(true);
            //$article->setCreatedAt($date);
            //$article->setUpdatedAt($date);

            $manager->persist($article);
            $manager->flush();
        }
        // $product = new Product();
        // $manager->persist($product);
        */
    }
}
