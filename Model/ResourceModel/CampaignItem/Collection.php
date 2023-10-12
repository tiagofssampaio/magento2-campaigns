<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model\ResourceModel\CampaignItem;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'item_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \TiagoSampaio\Campaigns\Model\CampaignItem::class,
            \TiagoSampaio\Campaigns\Model\ResourceModel\CampaignItem::class
        );
    }
}

