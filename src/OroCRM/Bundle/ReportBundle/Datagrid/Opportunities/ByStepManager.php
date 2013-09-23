<?php

namespace OroCRM\Bundle\ReportBundle\Datagrid\Opportunities;

use Oro\Bundle\GridBundle\Field\FieldDescriptionCollection;
use Oro\Bundle\GridBundle\Field\FieldDescriptionInterface;
use Oro\Bundle\GridBundle\Filter\FilterInterface;

use OroCRM\Bundle\ReportBundle\Datagrid\ReportGridManagerAbstract;

class ByStepManager extends ReportGridManagerAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configureFields(FieldDescriptionCollection $fieldsCollection)
    {
        $this->addField(
            'step',
            array(
                'label'        => 'Step',
                'entity_alias' => 'wi',
                'field_name'   => 'currentStepName',
            ),
            $fieldsCollection
        );

        $this->addField(
            'total_ops',
            array(
                'type'         => FieldDescriptionInterface::TYPE_INTEGER,
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'label'        => 'Number of opportunities',
                'field_name'   => 'numberOfOpp',
                'expression'   => 'numberOfOpp',
            ),
            $fieldsCollection
        );

        $this->addField(
            'closeRevenue',
            array(
                'type'         => FieldDescriptionInterface::TYPE_DECIMAL,
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'label'        => 'Close Revenue',
                'field_name'   => 'closeRevenue',
                'expression'   => 'closeRevenue',
            ),
            $fieldsCollection
        );

        $this->addField(
            'budget',
            array(
                'type'         => FieldDescriptionInterface::TYPE_DECIMAL,
                'filter_type'  => FilterInterface::TYPE_NUMBER,
                'label'        => 'Budget Value',
                'field_name'   => 'budgetAmount',
                'expression'   => 'budgetAmount',
            ),
            $fieldsCollection
        );

        $this->addField(
            'workflowName',
            array(
                'type'         => FieldDescriptionInterface::TYPE_OPTIONS,
                'filter_type'  => FilterInterface::TYPE_ENTITY,
                'class'               => 'OroWorkflowBundle:WorkflowDefinition',
                'property'            => 'name',
                'label'        => 'Workflow',
                'entity_alias' => 'wi',
                'field_name'   => 'workflowName',
                'show_column'  => false,
            ),
            $fieldsCollection
        );
    }


}
