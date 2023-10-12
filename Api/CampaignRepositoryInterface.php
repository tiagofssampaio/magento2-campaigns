<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CampaignRepositoryInterface
{

    /**
     * Save Campaign
     * @param \TiagoSampaio\Campaigns\Api\Data\CampaignInterface $campaign
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \TiagoSampaio\Campaigns\Api\Data\CampaignInterface $campaign
    );

    /**
     * Retrieve Campaign
     * @param string $campaignId
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($campaignId);

    /**
     * Retrieve Campaign matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TiagoSampaio\Campaigns\Api\Data\CampaignSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Campaign
     * @param \TiagoSampaio\Campaigns\Api\Data\CampaignInterface $campaign
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \TiagoSampaio\Campaigns\Api\Data\CampaignInterface $campaign
    );

    /**
     * Delete Campaign by ID
     * @param string $campaignId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($campaignId);
}

