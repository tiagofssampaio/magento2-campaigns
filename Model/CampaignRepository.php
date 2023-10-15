<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use TiagoSampaio\Campaigns\Api\CampaignRepositoryInterface;
use TiagoSampaio\Campaigns\Api\Data\CampaignInterface;
use TiagoSampaio\Campaigns\Api\Data\CampaignInterfaceFactory;
use TiagoSampaio\Campaigns\Api\Data\CampaignSearchResultsInterfaceFactory;
use TiagoSampaio\Campaigns\Model\ResourceModel\Campaign as ResourceCampaign;
use TiagoSampaio\Campaigns\Model\ResourceModel\Campaign\CollectionFactory as CampaignCollectionFactory;

class CampaignRepository implements CampaignRepositoryInterface
{

    /**
     * @var ResourceCampaign
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var CampaignCollectionFactory
     */
    protected $campaignCollectionFactory;

    /**
     * @var CampaignInterfaceFactory
     */
    protected $campaignFactory;

    /**
     * @var Campaign
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceCampaign $resource
     * @param CampaignInterfaceFactory $campaignFactory
     * @param CampaignCollectionFactory $campaignCollectionFactory
     * @param CampaignSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceCampaign $resource,
        CampaignInterfaceFactory $campaignFactory,
        CampaignCollectionFactory $campaignCollectionFactory,
        CampaignSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->campaignFactory = $campaignFactory;
        $this->campaignCollectionFactory = $campaignCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(CampaignInterface $campaign)
    {
        try {
            $this->resource->save($campaign);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the campaign: %1',
                $exception->getMessage()
            ));
        }
        return $campaign;
    }

    /**
     * @inheritDoc
     */
    public function get($campaignId)
    {
        $campaign = $this->campaignFactory->create();
        $this->resource->load($campaign, $campaignId);
        if (!$campaign->getId()) {
            throw new NoSuchEntityException(__('Campaign with id "%1" does not exist.', $campaignId));
        }
        return $campaign;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    ) {
        $collection = $this->campaignCollectionFactory->create();

        /**
         * Apply custom filter and ignore default filter behavior because "product_id" is not a field from the table
         */
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $newFilters = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'product_id') {
                    $collection->addProductsFilter([ 'in' => $filter->getValue()]);
                } else {
                    $newFilters[] = $filter;
                }
            }
            $filterGroup->setFilters($newFilters);
        }

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(CampaignInterface $campaign)
    {
        try {
            $campaignModel = $this->campaignFactory->create();
            $this->resource->load($campaignModel, $campaign->getId());
            $this->resource->delete($campaignModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Campaign: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($campaignId)
    {
        return $this->delete($this->get($campaignId));
    }
}

