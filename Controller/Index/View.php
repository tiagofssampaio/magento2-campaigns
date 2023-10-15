<?php

namespace TiagoSampaio\Campaigns\Controller\Index;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use TiagoSampaio\Campaigns\Model\Campaign;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use TiagoSampaio\Campaigns\Model\CampaignRepository;

/**
 * View a campaign on storefront
 *
 */
class View implements ActionInterface
{

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var ForwardFactory
     */
    protected ForwardFactory $resultForwardFactory;

    /**
     * @var Registry
     */
    protected Registry $_coreRegistry;

    /**
     * @var RequestInterface
     */
    protected RequestInterface $_request;

    /**
     * @var CampaignRepository
     */
    protected CampaignRepository $campaignRepository;

    /**
     * Constructor
     *
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Registry $coreRegistry
     * @param RequestInterface $request
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        RequestInterface $request,
        CampaignRepository $campaignRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_request = $request;
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Initialize requested campaign object
     *
     * @return Campaign|bool
     */
    protected function _initCampaign(): bool|Campaign
    {
        $campaignId = (int)$this->_request->getParam('id', false);
        if (!$campaignId) {
            return false;
        }

        try {
            $campaign = $this->campaignRepository->get($campaignId);
        } catch (NoSuchEntityException) {
            return false;
        }

        return $campaign;
    }

    /**
     * Campaign view action
     */
    public function execute(): Page|ResultInterface|ResponseInterface
    {
        $campaign = $this->_initCampaign();
        if (!$campaign) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $this->_coreRegistry->register('current_campaign', $campaign);
        return $this->resultPageFactory->create();
    }

}
