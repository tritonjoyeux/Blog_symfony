<?php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CommentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('post');
        $formMapper->add('author');
        $formMapper->add('pubdate', null, array("date_widget" => "single_text", "time_widget" => "single_text"));
        $formMapper->add('content');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('post');
        $datagridMapper->add('author');
        $datagridMapper->add('pubdate');
        $datagridMapper->add('content');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('author');
        $listMapper->addIdentifier('post');
        $listMapper->addIdentifier('pubdate');
    }
}