<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CampaignItem extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('tiagosampaio_campaign_item', 'item_id');
    }
}

