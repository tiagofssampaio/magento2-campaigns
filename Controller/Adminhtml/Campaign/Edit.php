<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Controller\Adminhtml\Campaign;

class Edit extends \TiagoSampaio\Campaigns\Controller\Adminhtml\Campaign
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('campaign_id');
        $model = $this->_objectManager->create(\TiagoSampaio\Campaigns\Model\Campaign::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Campaign no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('tiagosampaio_campaign', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Campaign') : __('New Campaign'),
            $id ? __('Edit Campaign') : __('New Campaign')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Campaigns'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Campaign %1', $model->getId()) : __('New Campaign'));
        return $resultPage;
    }
}

