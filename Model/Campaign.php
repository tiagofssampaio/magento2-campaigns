<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model;

use Magento\Framework\Model\AbstractModel;
use TiagoSampaio\Campaigns\Api\Data\CampaignInterface;

/**
 * Campaign model
 *
 * @api
 * @method array setPostedProducts(array $campaignProducts) Set products ids to add to campaign
 * @method array getPostedProducts() Get products ids to add to campaign
 * @method array getChangedProductIds() Get products ids that inserted or deleted for campaign
 * @method bool setIsChangedProductList(bool $changed) Set flag if campaign product list was changed
 * @method bool getIsChangedProductList() Get flag if campaign product list was changed
 *
 */
class Campaign extends AbstractModel implements CampaignInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Campaign::class);
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
    public function setUrlKey($urlKey): CampaignInterface
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * Retrieve array of product id's for campagin
     *
     * The array returned has the following format:
     * array($productId)
     *
     * @return array
     */
    public function getProducts(): array
    {
        if (!$this->getId()) {
            return [];
        }

        $array = $this->getData('products');
        if ($array === null) {
            $array = $this->getResource()->getProducts($this);
            $this->setData('products', $array);
        } else {
            $array = [];
        }
        return $array;
    }

}

