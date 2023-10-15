<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Observer;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use TiagoSampaio\Campaigns\Model\Campaign;
use Magento\Framework\App\Cache\TypeListInterface;

class CampaignChangeProducts implements ObserverInterface
{

    /**
     * Application Cache Manager
     *
     * @var CacheInterface
     */
    protected CacheInterface $_cacheManager;

    /**
     * @var TypeListInterface
     */
    protected TypeListInterface $_typeList;

    /**
     * @param CacheInterface $cacheManager
     * @param TypeListInterface $typeList
     */
    public function __construct(
        CacheInterface $cacheManager,
        TypeListInterface $typeList
    ) {
        $this->_cacheManager = $cacheManager;
        $this->_typeList = $typeList;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(
        Observer $observer
    ) {
        /** @var Campaign $campaign */
        $campaign = $observer->getEvent()->getCampaign();
        $productIds = $observer->getEvent()->getProductIds();

        $identities = [];
        foreach ($productIds as $productId) {
            $identities[] = Product::CACHE_TAG . '_' . $productId;
        }

        $identities = [
            ...$identities,
            ...$campaign->getIdentities()
        ];

        foreach ($identities as $identity) {
            $this->_typeList->cleanType($identity);
        }

        $this->_typeList->invalidate('FULL_PAGE');
        $this->_typeList->invalidate('BLOCK_HTML');
    }
}
