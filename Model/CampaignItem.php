<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model;

use Magento\Framework\Model\AbstractModel;
use TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface;

class CampaignItem extends AbstractModel implements CampaignItemInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\TiagoSampaio\Campaigns\Model\ResourceModel\CampaignItem::class);
    }

    /**
     * @inheritDoc
     */
    public function getItemId()
    {
        return $this->getData(self::ITEM_ID);
    }

    /**
     * @inheritDoc
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    /**
     * @inheritDoc
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }
}

