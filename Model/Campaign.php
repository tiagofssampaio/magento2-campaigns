<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model;

use Magento\Framework\Model\AbstractModel;
use TiagoSampaio\Campaigns\Api\Data\CampaignInterface;

class Campaign extends AbstractModel implements CampaignInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\TiagoSampaio\Campaigns\Model\ResourceModel\Campaign::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::CAMPAIGN_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($campaignId)
    {
        return $this->setData(self::CAMPAIGN_ID, $campaignId);
    }

    /**
     * @inheritDoc
     */
    public function getCampaignId()
    {
        return $this->getData(self::CAMPAIGN_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCampaignId($campaignId)
    {
        return $this->setData(self::CAMPAIGN_ID, $campaignId);
    }

    /**
     * @inheritDoc
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }


    /**
     * @inheritDoc
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * @inheritDoc
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }
}

