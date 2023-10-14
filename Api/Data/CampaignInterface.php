<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Api\Data;

interface CampaignInterface
{

    const CAMPAIGN_ID = 'campaign_id';
    const DESCRIPTION = 'description';
    const IS_ACTIVE = 'is_active';
    const TITLE = 'title';
    const URL_KEY = 'url_key';

    /**
     * Get campaign_id
     * @return string|null
     */
    public function getId();

    /**
     * Set campaign_id
     * @param string $campaignId
     * @return \TiagoSampaio\Campaigns\Campaign\Api\Data\CampaignInterface
     */
    public function setId($campaignId);

    /**
     * Get is_active
     * @return string|null
     */
    public function getIsActive();

    /**
     * Set is_active
     * @param string $isActive
     * @return \TiagoSampaio\Campaigns\Campaign\Api\Data\CampaignInterface
     */
    public function setIsActive($isActive);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return \TiagoSampaio\Campaigns\Campaign\Api\Data\CampaignInterface
     */
    public function setTitle($title);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \TiagoSampaio\Campaigns\Campaign\Api\Data\CampaignInterface
     */
    public function setDescription($description);

    /**
     * Get url_key
     * @return string|null
     */
    public function getUrlKey();

    /**
     * Set url_key
     * @param string $urlKey
     * @return \TiagoSampaio\Campaigns\Campaign\Api\Data\CampaignInterface
     */
    public function setUrlKey($urlKey);

}

