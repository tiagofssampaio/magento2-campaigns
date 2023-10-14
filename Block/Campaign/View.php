<?php

namespace TiagoSampaio\Campaigns\Block\Campaign;

use Exception;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Catalog\Block\Product\Context;
use TiagoSampaio\Campaigns\Model\Campaign;

class View extends AbstractProduct implements IdentityInterface
{

    /**
     * @var FilterProvider
     */
    protected FilterProvider $_filterProvider;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context        $context,
        Registry       $registry,
        FilterProvider $filterProvider,
        array          $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve current campaign model object
     *
     * @return Campaign
     */
    public function getCurrentCampaign(): Campaign
    {
        if (!$this->hasData('current_campaign')) {
            $this->setData('current_campaign', $this->_coreRegistry->registry('current_campaign'));
        }
        return $this->getData('current_campaign');
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        // TODO Add breadcrumbs
        //$this->getLayout()->createBlock(Breadcrumbs::class);

        $campaign = $this->getCurrentCampaign();
        if ($campaign) {
            $title = $campaign->getTitle();
            if ($title) {
                $this->pageConfig->getTitle()->set($title);
            }
            /*
             * Don't set description from campaign since the description is a WYSIWYG field
            $description = $campaign->getDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            */

            /*
             * TODO - Add canonical URL
            $this->pageConfig->addRemotePageAsset(
                $campaign->getUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
            */

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($title);
            }

            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbs) {
                $breadcrumbs->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'title' => __('Go to Home Page'),
                        'link' => $this->getBaseUrl()
                    ]
                );
                $breadcrumbs->addCrumb(
                    'campaign_view',
                    [
                        'label' => $title,
                        'title' => $title
                    ]
                );
            }

        }

        return $this;
    }

    public function getDescription()
    {
        $description = $this->getCurrentCampaign()->getDescription();
        try {
            return $this->_filterProvider->getPageFilter()->filter($description);
        } catch (Exception) {
            return $description;
        }
    }

    public function getProductCollection()
    {
        return $this->getCurrentCampaign()->getProductCollection();
    }

    public function getIdentities()
    {
        return $this->getCurrentCampaign()->getIdentities();
    }
}
