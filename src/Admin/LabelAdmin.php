<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LabelAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_label';

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
            ->add('color', TextType::class, [
                'label' => 'admin.color',
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
            ->add('color', null, ['label' => 'admin.color'])
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
            ->add('name', null, ['label' => 'admin.name'])
            ->add('color', null, ['label' => 'admin.color'])
        ;
    }
}
