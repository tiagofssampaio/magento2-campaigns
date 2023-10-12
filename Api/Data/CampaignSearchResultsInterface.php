<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api\Data;

interface CampaignSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Campaign list.
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignInterface[]
     */
    public function getItems();

    /**
     * Set is_active list.
     * @param \TiagoSampaio\Campaigns\Api\Data\CampaignInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

