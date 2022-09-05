<?php


namespace Bytepattern\HideEmptyCategories\Observer\Catalog;

class CategoryCollectionLoadBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    )
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $categoryCollection = $observer->getCategoryCollection();
        $categoryCollection->addAttributeToSelect('is_anchor');
    }
}
