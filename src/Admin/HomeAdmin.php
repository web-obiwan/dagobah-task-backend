<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class HomeAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'home';
    protected $baseRouteName = 'home';
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->clearExcept(['list']);
    }
}
