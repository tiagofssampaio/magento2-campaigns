<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model\ResourceModel\Campaign;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use TiagoSampaio\Campaigns\Model\ResourceModel\Campaign;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'campaign_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \TiagoSampaio\Campaigns\Model\Campaign::class,
            Campaign::class
        );
    }


    /**
     * Filter campaigns by product id(s)
     *
     * @param array $productsFilter
     * @return Collection
     */
    public function addProductsFilter(array $productsFilter)
    {
        foreach ($productsFilter as $conditionType => $values) {
            $campaignProductsSelect = $this->getConnection()->select()->from(
                ['prod' => $this->getTable('tiagosampaio_campaign_product')],
                'prod.campaign_id'
            )->where($this->getConnection()->prepareSqlCondition('prod.product_id', ['in' => $values]));
            $selectCondition = [
                $this->mapConditionType($conditionType) => $campaignProductsSelect
            ];
            $this->getSelect()->where($this->getConnection()->prepareSqlCondition('main_table.campaign_id', $selectCondition));
        }
        return $this;
    }

    /**
     * Map equal and not equal conditions to in and not in
     *
     * @param string $conditionType
     * @return mixed
     */
    private function mapConditionType($conditionType)
    {
        $conditionsMap = [
            'eq' => 'in',
            'neq' => 'nin'
        ];
        return $conditionsMap[$conditionType] ?? $conditionType;
    }

}

