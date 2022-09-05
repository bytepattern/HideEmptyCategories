<?php


namespace Bytepattern\HideEmptyCategories\Observer\Catalog;

class CategoryCollectionLoadAfter implements \Magento\Framework\Event\ObserverInterface
{

    private $eventManager;

    /**
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager
    )
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $filteredCategoryCollection = $observer->getCategoryCollection();
        $categoryCollection = clone $filteredCategoryCollection;
        $filteredCategoryCollection->removeAllItems();
        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($categoryCollection as $category) {
            $showCategory = $this->getTotalProductCount($category) > 0;
            $transport = new \Magento\Framework\DataObject(
                [
                    'show_category' => $showCategory
                ]
            );
            $this->eventManager->dispatch(
                'codex_hide_empty_categories_before',
                [
                    'collection' => $categoryCollection,
                    'category' => $category,
                    'transport' => $transport
                ]
            );
            if ($transport->getShowCategory()) {
                $filteredCategoryCollection->addItem($category);
            }
        }
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @return mixed
     */
    protected function getTotalProductCount(\Magento\Catalog\Model\Category $category)
    {
        /**
         * Asif zaman code
         */
        //return $category->getProductCollection()->getSize();

        /**
         * Modified by Micheal
         */
        //return $category->getProductCollection()->addAttributeToFilter('is_discontinued', 0)->getSize();

        /**
         * Modified by Asif zaman
         */
        return $category->getProductCollection()
            ->addAttributeToFilter(array(
                array(
                    'attribute' => 'is_discontinued',
                    'null' => true),
                array(
                    'attribute' => 'is_discontinued',
                    'eq' => '0')
            ))->getSize();
    }
}
