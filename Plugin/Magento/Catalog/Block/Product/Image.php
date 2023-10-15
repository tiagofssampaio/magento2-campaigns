<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Plugin\Magento\Catalog\Block\Product;

use Closure;
use TiagoSampaio\Campaigns\Block\Product\Tags as TagsBlock;
use TiagoSampaio\Campaigns\Helper\Tags as TagsHelper;

class Image
{

    /**
     * @var TagsBlock
     */
    protected TagsBlock $tagsBlock;

    protected TagsHelper $tagsHelper;

    /**
     * @param TagsBlock $tagsBlock
     * @param TagsHelper $tagsHelper
     */
    public function __construct(
        TagsBlock $tagsBlock,
        TagsHelper $tagsHelper
    ) {
        $this->tagsBlock = $tagsBlock;
        $this->tagsHelper = $tagsHelper;
    }

    public function aroundToHtml(
        \Magento\Catalog\Block\Product\Image $subject,
        Closure $proceed
    ) {
        $result = $proceed();

        $productId = $subject->getData('product_id');

        $block = $this->tagsBlock;
        $block->setProductId($productId);
        $block->setData('tagsHelper', $this->tagsHelper);

        $result .= $block->toHtml();
        return $result;
    }
}

