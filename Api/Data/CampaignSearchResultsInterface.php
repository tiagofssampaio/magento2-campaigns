<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CampaignSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Campaign list.
     * @return CampaignInterface[]
     */
    public function getItems();

    /**
     * Set is_active list.
     * @param CampaignInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

