<?php

namespace TiagoSampaio\Campaigns\Block\Product;

use Exception;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Framework\DataObject\IdentityInterface;
use TiagoSampaio\Campaigns\Model\Campaign;

class Tags extends AbstractProduct implements IdentityInterface
{
    public function getIdentities()
    {
        return [];
    }
}
