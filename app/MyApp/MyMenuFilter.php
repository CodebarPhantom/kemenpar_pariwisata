<?php

namespace App\MyApp;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;

class MyMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        if (isset($item['permission']) && ! Laratrust::isAbleTo($item['permission'])) {
            $item['restricted'] = true;
        }

        return $item;
    }
}