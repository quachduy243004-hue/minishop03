<?php

namespace Composers;

use DAO\CategoryDAO;
use DAO\BrandDAO;

class HeaderComposer
{
    public static function compose(): array
    {
        $categoryDAO = new CategoryDAO();

        $brandDAO = new BrandDAO();


        return [

            'categories' =>
                $categoryDAO->getByLimit(3),

            'brands' =>
                $brandDAO->getByLimit(3)

        ];
    }
}