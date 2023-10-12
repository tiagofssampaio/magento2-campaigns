<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model\ResourceModel\Campaign;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'campaign_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \TiagoSampaio\Campaigns\Model\Campaign::class,
            \TiagoSampaio\Campaigns\Model\ResourceModel\Campaign::class
        );
    }
}

