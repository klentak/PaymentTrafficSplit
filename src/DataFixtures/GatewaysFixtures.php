<?php

namespace App\DataFixtures;

use App\App\GatewayConfiguration\Domain\GatewayConfiguration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GatewaysFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 4; $i++) {
            $gatewayConfiguration = new GatewayConfiguration(
                'Gateway'.$i,
                25
            );

            $manager->persist($gatewayConfiguration);
        }

        $manager->flush();
    }
}
