<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_user';

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
            ->add('username', TextType::class, [
                'label' => 'label.user.username',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.user.email',
                'required' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'label.user.enabled'
            ])
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('username', null, ['label' => 'admin.user.username'])
            ->add('email', null, ['label' => 'admin.user.email'])
            ->add('enabled', null, ['label' => 'admin.user.enabled'])
        ;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'admin.id'])
            ->add('username', null, ['label' => 'admin.user.username'])
            ->add('email', null, ['label' => 'admin.user.email'])
            ->add('roles', null, ['label' => 'admin.user.roles'])
            ->add('enabled', null, ['editable' => true, 'label' => 'admin.user.enabled'])
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
            ->add('username', null, ['label' => 'admin.user.username'])
            ->add('email', null, ['label' => 'admin.user.email'])
            ->add('roles', null, ['label' => 'admin.user.roles'])
            ->add('enabled', null, ['label' => 'admin.user.enabled'])
            ->add('password', null, ['label' => 'admin.user.password'])
        ;
    }
}
