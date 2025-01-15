<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SprintAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_sprint';

    /**
     * @param RouteCollectionInterface $collection
     */
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('delete');
    }

    /**
     * @param FormMapper $form
     * @return void
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', TextType::class, [
                'label' => 'admin.name',
                'required' => true,
            ])
            ->add('begunAt', DateType::class, [
                'label' => 'admin.begunAt',
                'required' => true,
            ])
            ->add('endedAt', DateType::class, [
                'label' => 'admin.endedAt',
                'required' => true,
            ])
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name', null, ['label' => 'admin.name'])
        ;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'admin.id'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('begunAt', null, ['label' => 'admin.begunAt'])
            ->add('endedAt', null, ['label' => 'admin.endedAt'])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
        ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id', null, ['label' => 'admin.id'])
            ->add('begunAt', null, ['label' => 'admin.begunAt'])
            ->add('endedAt', null, ['label' => 'admin.endedAt'])
        ;
    }
}
