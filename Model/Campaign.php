<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model;

use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Filter\TranslitUrl;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Profiler;
use Magento\Framework\Registry;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
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

    const URL_ENTITY_TYPE = 'campaign';

    const CACHE_TAG = 'campaign';

    /**
     * @var TranslitUrl
     */
    protected TranslitUrl $translitUrl;

    /**
     * @var UrlFinderInterface
     */
    protected UrlFinderInterface $urlFinder;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param TranslitUrl $translitUrl
     * @param UrlFinderInterface $urlFinder
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry                         $registry,
        TranslitUrl                      $translitUrl,
        UrlFinderInterface               $urlFinder,
        AbstractResource                 $resource = null,
        AbstractDb                       $resourceCollection = null,
        array                            $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->translitUrl = $translitUrl;
        $this->urlFinder = $urlFinder;
    }

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
        return $this->setData(self::URL_KEY, $this->translitUrl->filter($urlKey));
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

    public function getProductCollection()
    {
        $collection = $this->getData('product_collection');
        if ($collection === null) {
            $collection = $this->getResource()->getProductCollection($this);
            $this->setData('product_collection', $collection);
        }
        return $collection;
    }

    /**
     * Get category url
     *
     * @return string
     */
    public function getUrl()
    {
        $url = $this->_getData('url');
        if ($url === null) {
            $currentStore = $this->getResource()->getCurrentStore();
            $storeId = (int)$currentStore->getId();
            $rewrite = $this->urlFinder->findOneByData(
                [
                    UrlRewrite::ENTITY_ID => $this->getId(),
                    UrlRewrite::ENTITY_TYPE => self::URL_ENTITY_TYPE,
                    UrlRewrite::STORE_ID => $storeId,
                ]
            );
            if ($rewrite) {
                $url = $rewrite->getRequestPath();
            } else {
                $url = $this->getResource()->getTargetPath($this);
            }

            $this->setData('url', $currentStore->getUrl() . ltrim($url, '/'));
            return $this->getData('url');
        }
        return $url;
    }

    public function getIdentities(): array
    {
        return [
            self::CACHE_TAG . '_' . $this->getId(),
            self::CACHE_TAG
        ];
    }

}

