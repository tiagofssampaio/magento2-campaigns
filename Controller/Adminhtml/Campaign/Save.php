<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Controller\Adminhtml\Campaign;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    protected $campaignModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \TiagoSampaio\Campaigns\Model\Campaign $campaignModel
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->campaignModel = $campaignModel;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('campaign_id');

            $model = $this->campaignModel->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Campaign no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $campaignProducts = explode(',', $data['campaign_products']);
            unset($data['campaign_products']);

            $model->setData($data);

            if (!empty($campaignProducts) && is_array($campaignProducts)) {
                // var_dump($campaignProducts);die;
                $model->setPostedProducts($campaignProducts);
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Campaign.'));
                $this->dataPersistor->clear('tiagosampaio_campaign');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['campaign_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Campaign.'));
            }

            $this->dataPersistor->set('tiagosampaio_campaign', $data);
            return $resultRedirect->setPath('*/*/edit', ['campaign_id' => $this->getRequest()->getParam('campaign_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

