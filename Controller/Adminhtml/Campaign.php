<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;

abstract class Campaign extends Action
{

    const ADMIN_RESOURCE = 'TiagoSampaio_Campaigns::top_level';

    protected $_coreRegistry;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('TiagoSampaio'), __('TiagoSampaio'))
            ->addBreadcrumb(__('Campaigns'), __('Campaigns'));
        return $resultPage;
    }
}

