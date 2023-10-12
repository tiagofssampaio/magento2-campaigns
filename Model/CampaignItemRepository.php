<?php
declare(strict_types=1);

namespace TiagoSampaio\Campaigns\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use TiagoSampaio\Campaigns\Api\CampaignItemRepositoryInterface;
use TiagoSampaio\Campaigns\Api\Data\CampaignItemInterface;
use TiagoSampaio\Campaigns\Api\Data\CampaignItemInterfaceFactory;
use TiagoSampaio\Campaigns\Api\Data\CampaignItemSearchResultsInterfaceFactory;
use TiagoSampaio\Campaigns\Model\ResourceModel\CampaignItem as ResourceCampaignItem;
use TiagoSampaio\Campaigns\Model\ResourceModel\CampaignItem\CollectionFactory as CampaignItemCollectionFactory;

class CampaignItemRepository implements CampaignItemRepositoryInterface
{

    /**
     * @var ResourceCampaignItem
     */
    protected $resource;

    /**
     * @var CampaignItemInterfaceFactory
     */
    protected $campaignItemFactory;

    /**
     * @var CampaignItemCollectionFactory
     */
    protected $campaignItemCollectionFactory;

    /**
     * @var CampaignItem
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;


    /**
     * @param ResourceCampaignItem $resource
     * @param CampaignItemInterfaceFactory $campaignItemFactory
     * @param CampaignItemCollectionFactory $campaignItemCollectionFactory
     * @param CampaignItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceCampaignItem $resource,
        CampaignItemInterfaceFactory $campaignItemFactory,
        CampaignItemCollectionFactory $campaignItemCollectionFactory,
        CampaignItemSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->campaignItemFactory = $campaignItemFactory;
        $this->campaignItemCollectionFactory = $campaignItemCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(CampaignItemInterface $campaignItem)
    {
        try {
            $this->resource->save($campaignItem);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the campaignItem: %1',
                $exception->getMessage()
            ));
        }
        return $campaignItem;
    }

    /**
     * @inheritDoc
     */
    public function get($campaignItemId)
    {
        $campaignItem = $this->campaignItemFactory->create();
        $this->resource->load($campaignItem, $campaignItemId);
        if (!$campaignItem->getId()) {
            throw new NoSuchEntityException(__('CampaignItem with id "%1" does not exist.', $campaignItemId));
        }
        return $campaignItem;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->campaignItemCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

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
    public function delete(CampaignItemInterface $campaignItem)
    {
        try {
            $campaignItemModel = $this->campaignItemFactory->create();
            $this->resource->load($campaignItemModel, $campaignItem->getItemId());
            $this->resource->delete($campaignItemModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the CampaignItem: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($campaignItemId)
    {
        return $this->delete($this->get($campaignItemId));
    }
}

