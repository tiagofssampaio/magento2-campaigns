<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model\ResourceModel;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Filter\TranslitUrl;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;

class Campaign extends AbstractDb
{

    /**
     * Core event manager proxy
     *
     * @var ManagerInterface
     */
    protected ManagerInterface $_eventManager;

    /**
     * Application Cache Manager
     *
     * @var CacheInterface
     */
    protected CacheInterface $_cacheManager;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $_productCollectionFactory;

    /**
     * @var Visibility
     */
    protected Visibility $_productVisibility;

    /**
     * @var TranslitUrl
     */
    protected TranslitUrl $_translitUrl;

    /**
     * @var UrlPersistInterface
     */
    protected UrlPersistInterface $urlPersist;

    /**
     * @var UrlRewriteFactory
     */
    protected UrlRewriteFactory $urlRewriteFactory;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @param Context $context
     * @param ManagerInterface $eventManager
     * @param CacheInterface $cacheManager
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $productVisibility
     * @param TranslitUrl $translitUrl
     * @param UrlPersistInterface $urlPersist
     * @param StoreManagerInterface $storeManager
     * @param null $connectionName
     */
    public function __construct(
        Context          $context,
        ManagerInterface $eventManager,
        CacheInterface $cacheManager,
        CollectionFactory $productCollectionFactory,
        Visibility $productVisibility,
        TranslitUrl $translitUrl,
        UrlPersistInterface $urlPersist,
        UrlRewriteFactory $urlRewriteFactory,
        StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        $this->_eventManager = $eventManager;
        $this->_cacheManager = $cacheManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
        $this->_translitUrl = $translitUrl;
        $this->urlPersist = $urlPersist;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $connectionName);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('tiagosampaio_campaign', 'campaign_id');
    }

    /**
     * Get products associated to campaign
     *
     * @param \TiagoSampaio\Campaigns\Model\Campaign $campaign
     * @return array
     */
    public function getProducts(\TiagoSampaio\Campaigns\Model\Campaign $campaign): array
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable('tiagosampaio_campaign_product'),
            ['product_id']
        )->where(
            "{$this->getTable('tiagosampaio_campaign_product')}.campaign_id = ?",
            $campaign->getId()
        );
        return $this->getConnection()->fetchCol($select);
    }

    public function getProductCollection(\TiagoSampaio\Campaigns\Model\Campaign $campaign)
    {
        $productIds = $campaign->getProducts();

        $productCollection = $this->_productCollectionFactory->create()
            ->addAttributeToSelect(
                'name'
            )->addAttributeToSelect(
                'sku'
            )->addAttributeToSelect(
                'small_image'
            )->addAttributeToSelect(
                'visibility'
            )->addAttributeToSelect(
                'status'
            )->addAttributeToSelect(
                'price'
            )
            ->addAttributeToFilter('entity_id', ['in' => $productIds])
            ->addAttributeToFilter('status', 1)
            ->setVisibility($this->_productVisibility->getVisibleInSiteIds());

        return $productCollection;
    }

    protected function _deleteAllUrlRewrites(AbstractModel $object)
    {
        $this->urlPersist->deleteByData([
            UrlRewrite::ENTITY_TYPE   => 'campaign',
            UrlRewrite::ENTITY_ID     => $object->getId(),
            UrlRewrite::REDIRECT_TYPE => 0,
            UrlRewrite::STORE_ID      => $this->_getStoreIds()
        ]);
    }

    /**
     * Before deletion delete url rewrites, so they don't become orphaned.
     *
     * @param AbstractModel $object
     * @return Campaign
     */
    protected function _beforeDelete(AbstractModel $object)
    {
        $this->_deleteAllUrlRewrites($object);
        return parent::_beforeDelete($object);
    }

    /**
     * Before it saves the campaign, it will generate the url key. If not url_key is provided, it will use the title
     *
     * @param AbstractModel $object
     * @return Campaign
     */
    protected function _beforeSave(AbstractModel $object): Campaign
    {
        if (!$object->getUrlKey()) {
            $object->setUrlKey($object->getTitle());
        }
        $object->setUrlKey($this->_translitUrl->filter($object->getUrlKey()));
        return parent::_afterSave($object);
    }

    /**
     * Process campaign data after save campaign
     *
     * Save related products ids
     * Generate campaign urls
     *
     * @return $this
     * @throws UrlAlreadyExistsException
     */
    protected function _afterSave(AbstractModel $object): Campaign
    {
        $this->_saveCampaignProducts($object);
        $this->_generateUrls($object);
        $this->_eventManager->dispatch('clean_cache_by_tags', ['object' => $object]);
        $this->_cacheManager->clean($object->getIdentities());
        return parent::_afterSave($object);
    }

    /**
     * Save campaign products
     *
     * @return $this
     */
    protected function _saveCampaignProducts(AbstractModel $campaign): Campaign
    {
        $campaign->setIsChangedProductList(false);
        $id = $campaign->getId();

        /**
         * new campaign-product relationships
         */
        $products = $campaign->getPostedProducts();

        /**
         * Continue campaign save
         */
        if ($products === null) {
            return $this;
        }

        /**
         * old campaign-product relationships
         */
        $oldProducts = $campaign->getProducts();

        /**
         * Find preexisting product ids that are no longer in campaign
         */
        $insert = array_diff($products, $oldProducts);
        $delete = array_diff($oldProducts, $products);

        $connection = $this->getConnection();

        /**
         * Delete products from campaign
         */
        if (!empty($delete)) {
            $cond = ['product_id IN(?)' => $delete, 'campaign_id=?' => $id];
            $connection->delete($this->getTable('tiagosampaio_campaign_product'), $cond);
        }

        /**
         * Add products to campaign
         */
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId) {
                $data[] = [
                    'campaign_id' => (int)$id,
                    'product_id' => (int)$productId
                ];
            }
            $connection->insertMultiple($this->getTable('tiagosampaio_campaign_product'), $data);
        }

        /**
         * Setting IsChangedProductList and product ids for modularity porpuses
         * i.e.: Its own index
         */
        if (!empty($insert) || !empty($delete)) {
            $campaign->setIsChangedProductList(true);

            $productIds = array_merge($insert, $delete);
            $this->_eventManager->dispatch(
                'tiagosampaio_campaign_change_products',
                ['campaign' => $campaign, 'product_ids' => $productIds]
            );
            $campaign->setAffectedProductIds($productIds);
        }
        return $this;
    }

    protected function _getStoreIds()
    {
        $stores = $this->_storeManager->getStores(true, true);
        $storeIds = [];
        foreach ($stores as $store) {
            $storeIds[] = $store->getId();
        }
        return $storeIds;
    }

    /**
     */
    protected function _generateUrls(AbstractModel $campaign)
    {
        $newUrls = [];
        foreach ($this->_getStoreIds() as $storeId) {
            /** @var UrlRewrite $urlRewrite */
            $newUrls[] = $this->urlRewriteFactory->create()
                    ->setStoreId($storeId)
                    ->setEntityType('campaign')
                    ->setEntityId($campaign->getId())
                    ->setRequestPath($campaign->getUrlKey())
                    ->setTargetPath('tiagosampaio/campaign/view/id/' . $campaign->getId() . '/')
                    ->setIsAutogenerated(1);
        }

        if(!empty($newUrls)) {
            /**
             * Delete all URL's before it recreates the new ones
             *
             * Improvements that cna be done:
             * Check diff, so it only deletes the unecessary ones
             */
            $this->_deleteAllUrlRewrites($campaign);
            try {
                $this->urlPersist->replace($newUrls);
            } catch (UrlAlreadyExistsException $e) {
                echo $e->getMessage();die;
            }
        }
    }

}

