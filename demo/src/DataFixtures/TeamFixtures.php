<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Team;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $team = new Team();
            $team -> setName("Equipier n°$i")
                     -> setCompany("Company AGL n°$i")
                     -> setSalary(rand(1200, 4500));

            $manager -> persist($team);
        }

        $manager->flush();
    }
}
