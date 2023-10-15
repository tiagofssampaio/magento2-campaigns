<?php

namespace TiagoSampaio\Campaigns\Helper;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use TiagoSampaio\Campaigns\Model\CampaignRepository;

class Tags implements ArgumentInterface
{

    /**
     * @var CampaignRepository
     */
    protected CampaignRepository $campaignRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @param CampaignRepository $campaignRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CampaignRepository $campaignRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function getTagsByProductId($productId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('product_id', $productId)
            ->addFilter('is_active', 1);

        $campaignList = $this->campaignRepository->getList($searchCriteria->create());
        return $campaignList->getItems();
    }

}
