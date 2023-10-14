<?php

namespace TiagoSampaio\Campaigns\Block\Adminhtml\Campaign;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\BlockInterface;
use TiagoSampaio\Campaigns\Block\Adminhtml\Campaign\Tab\Product;
use TiagoSampaio\Campaigns\Model\Campaign;

class AssignProducts extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'TiagoSampaio_Campaigns::campaign/edit/assign_products.phtml';

    /**
     * @var Product
     */
    protected $blockGrid;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * AssignProducts constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return BlockInterface
     * @throws LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                Product::class,
                'campaign.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     * @throws LocalizedException
     */
    public function getGridHtml(): string
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getProducts(): string
    {
        return implode(',', $this->getCampaign()->getProducts());
    }

    /**
     * Retrieve current campaign instance
     *
     * @return Campaign|null
     */
    public function getCampaign(): Campaign|null
    {
        return $this->registry->registry('tiagosampaio_campaign');
    }
}
