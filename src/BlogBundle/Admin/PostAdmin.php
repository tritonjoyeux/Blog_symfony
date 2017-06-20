<?php

namespace BlogBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends AbstractAdmin
{
    public $supportsPreviewMode = true;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('author');
        $formMapper->add('title');
        $formMapper->add('content');
        $formMapper->add('pubdate', null, array("date_widget" => "single_text", "time_widget" => "single_text"));
        $formMapper->add('categories', null, array('multiple' => true, 'by_reference' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('author');
        $datagridMapper->add('content');
        $datagridMapper->add('pubdate');
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('author');
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('pubdate');
        $listMapper->addIdentifier('categories');
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'preview':
                return 'BlogBundle:CRUD:preview.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
}