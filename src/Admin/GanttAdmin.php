<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GanttAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_gantt';

    /**
     * @param FormMapper $form
     * @return void
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('infos', ['class' => 'col-md-6'])
            ->add('name', TextType::class, [
                'label' => 'admin.name',
                'required' => true,
            ])
            ->add(
                'parent',
                ModelListType::class,
                [
                    'label' => 'admin.parent',
                    'btn_add' => false,
                    'btn_edit' => false,
                    'btn_delete' => false,
                    'btn_list' => true,
                ]
            )
            ->add('project', null, [
                'label' => 'admin.project',
                'required' => true,
            ])
            ->add('repositories', null, [
                'label' => 'admin.repositories',
                'required' => false,
            ])
            ->end()
            ->with('Dates', ['class' => 'col-md-6'])
            ->add('begunAt', null, [
                'label' => 'admin.begunAt',
                'required' => true,
            ])
            ->add('duration', null, [
                'label' => 'admin.duration',
                'required' => true,
            ])
            ->add('progress', null, [
                'label' => 'admin.progress',
                'required' => true,
            ])
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name', null, ['label' => 'admin.name'])
            ->add('project', null, ['label' => 'admin.project'])
        ;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'admin.id'])
            ->add('project', null, ['label' => 'admin.project'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('begunAt', null, ['label' => 'admin.begunAt'])
            ->add('duration', null, ['label' => 'admin.duration'])
            ->add('progress', null, ['label' => 'admin.progress'])
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
            ->with('infos', ['class' => 'col-md-6'])
            ->add('id', null, ['label' => 'admin.id'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('project', null, ['label' => 'admin.project'])
            ->add('repositories', null, ['label' => 'admin.repositories'])
            ->end()
            ->with('Dates', ['class' => 'col-md-6'])
            ->add('begunAt', null, ['label' => 'admin.begunAt'])
            ->add('duration', null, ['label' => 'admin.duration'])
            ->add('progress', null, ['label' => 'admin.progress'])
            ->end()
            ->with('Childs', ['class' => 'col-md-6'])
            ->add('childs', null, ['label' => 'admin.childs'])
            ->end()
        ;
    }
}
