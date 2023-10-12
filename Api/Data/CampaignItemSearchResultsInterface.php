<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api\Data;

interface CampaignItemSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get CampaignItem list.
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

