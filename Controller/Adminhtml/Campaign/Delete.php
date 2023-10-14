<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Controller\Adminhtml\Campaign;

use Exception;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use TiagoSampaio\Campaigns\Model\Campaign;

class Delete extends \TiagoSampaio\Campaigns\Controller\Adminhtml\Campaign
{

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('campaign_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(Campaign::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Campaign.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['campaign_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Campaign to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

