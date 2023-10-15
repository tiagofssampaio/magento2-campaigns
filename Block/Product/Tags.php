<?php

namespace TiagoSampaio\Campaigns\Block\Product;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

/**
 *
 * @method setProductId(int $productId) Set Product ID
 * @method setTagsHelper(\TiagoSampaio\Campaigns\Helper\Tags $tagsHelper) Set Tags Helper
 *
 */
class Tags extends Template
{

    /**
     * @var string
     */
    protected $_template = "TiagoSampaio_Campaigns::product/tags.phtml";

    /**
     * @var Registry
     */
    protected Registry $_coreRegistry;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->_coreRegistry = $registry;
    }

    /**
     * If product_id is empty, it means it's on the product page and will get the product id from the registry.
     *
     * @return int
     */
    public function getProductId()
    {
        $productId = $this->getData('product_id');
        if (!$productId) {
            $product = $this->_coreRegistry->registry('product');
            return $product ? (int)$product->getId() : null;
        }
        return (int)$productId;
    }

}
