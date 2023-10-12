<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api\Data;

interface CampaignItemInterface
{

    const PRODUCT_ID = 'product_id';
    const ITEM_ID = 'item_id';

    /**
     * Get item_id
     * @return string|null
     */
    public function getItemId();

    /**
     * Set item_id
     * @param string $itemId
     * @return \TiagoSampaio\Campaigns\CampaignItem\Api\Data\CampaignItemInterface
     */
    public function setItemId($itemId);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \TiagoSampaio\Campaigns\CampaignItem\Api\Data\CampaignItemInterface
     */
    public function setProductId($productId);
}

