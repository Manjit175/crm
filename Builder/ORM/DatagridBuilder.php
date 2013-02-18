<?php

namespace Oro\Bundle\GridBundle\Builder\ORM;

use Symfony\Component\Form\FormFactoryInterface;
use Oro\Bundle\GridBundle\Field\FieldDescriptionInterface;
use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Builder\DatagridBuilderInterface;
use Oro\Bundle\GridBundle\Datagrid\Datagrid;
use Oro\Bundle\GridBundle\Datagrid\DatagridInterface;
use Oro\Bundle\GridBundle\Filter\FilterFactoryInterface;
use Oro\Bundle\GridBundle\Datagrid\ProxyQueryInterface;
use Oro\Bundle\GridBundle\Datagrid\PagerInterface;
use Oro\Bundle\GridBundle\Datagrid\ParameterContainerInterface;

class DatagridBuilder implements DatagridBuilderInterface
{
    /**
     * @var FilterFactoryInterface
     */
    protected $filterFactory;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     * @param FilterFactoryInterface $filterFactory
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        FilterFactoryInterface $filterFactory
    ) {
        $this->formFactory     = $formFactory;
        $this->filterFactory   = $filterFactory;
    }

    /**
     * @param DatagridInterface $datagrid
     * @param FieldDescriptionInterface $fieldDescription
     * @return void
     */
    public function addFilter(
        DatagridInterface $datagrid,
        FieldDescriptionInterface $fieldDescription = null
    ) {
        $filter = $this->filterFactory->create(
            $fieldDescription->getName(),
            $fieldDescription->getType(),
            $fieldDescription->getOptions()
        );
        $datagrid->addFilter($filter);
    }

    /**
     * @param ProxyQueryInterface $query
     * @param FieldDescriptionCollection $fieldCollection
     * @param ParameterContainerInterface $values
     * @return DatagridInterface
     */
    public function getBaseDatagrid(
        ProxyQueryInterface $query,
        FieldDescriptionCollection $fieldCollection,
        ParameterContainerInterface $values = null
    ) {
        // TODO: inject pager instance
        /** @var $pager PagerInterface */
        $pager = null;

        $formBuilder = $this->formFactory->createNamedBuilder(
            'filter',
            'form',
            array(),
            array('csrf_protection' => false)
        );

        return new Datagrid($query, $fieldCollection, $pager, $formBuilder, $values);
    }
}
