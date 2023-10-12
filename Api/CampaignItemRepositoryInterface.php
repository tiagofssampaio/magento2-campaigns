<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CampaignItemRepositoryInterface
{

    /**
     * Save CampaignItem
     * @param \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface $campaignItem
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface $campaignItem
    );

    /**
     * Retrieve CampaignItem
     * @param string $itemId
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($itemId);

    /**
     * Retrieve CampaignItem matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignItemSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete CampaignItem
     * @param \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface $campaignItem
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface $campaignItem
    );

    /**
     * Delete CampaignItem by ID
     * @param string $itemId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($itemId);
}

