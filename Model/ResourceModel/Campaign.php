<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model\ResourceModel;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Campaign extends AbstractDb
{

    /**
     * Core event manager proxy
     *
     * @var ManagerInterface
     */
    protected ManagerInterface $_eventManager;

    /**
     * @param Context $context
     * @param ManagerInterface $eventManager
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        ManagerInterface $eventManager,
        $connectionName = null
    ) {
        $this->_eventManager = $eventManager;
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

    /**
     * Process campaign data after save campaign
     *
     * Save related products ids
     *
     * @return $this
     */
    protected function _afterSave(AbstractModel $object): Campaign
    {
        $this->_saveCampaignProducts($object);
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
/*
        echo '<pre>';
        echo 'products: ';var_export($products);echo '<br>';
        echo 'insert: ';var_export($insert);echo '<br>';
        echo 'delete: ';var_export($delete);echo '<br>';
        die;
*/
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

}

