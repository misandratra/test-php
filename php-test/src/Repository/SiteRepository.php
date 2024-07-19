<?php

namespace App\Repository;

use App\Entity\Site;
use App\Repository\Repository;

class SiteRepository implements Repository
{
    /**
     * @param int $id
     *
     * @return Site
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $generator = \Faker\Factory::create();
        $generator->seed($id);

        return new Site($id, $generator->url);
    }
}
