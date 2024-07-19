<?php

declare(strict_types=1);

namespace App\Context;

use App\Entity\Site;
use App\Entity\User;
use Faker\Factory;
use Faker\Generator;

class ApplicationContext
{
    private $currentSite;
    private $currentUser;
    private $faker;

    public function __construct(Generator $faker = null)
    {
        $this->faker = $faker ?? Factory::create();
        $this->initializeCurrentSite();
        $this->initializeCurrentUser();
    }

    public function getCurrentSite(): Site
    {
        return $this->currentSite;
    }

    public function getCurrentUser(): User
    {
        return $this->currentUser;
    }

    private function initializeCurrentSite(): void
    {
        $this->currentSite = new Site(
            $this->faker->randomNumber(),
            $this->faker->url
        );
    }

    private function initializeCurrentUser(): void
    {
        $this->currentUser = new User(
            $this->faker->randomNumber(),
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->email
        );
    }
}