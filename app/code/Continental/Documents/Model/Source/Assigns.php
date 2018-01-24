<?php

namespace Continental\Documents\Model\Source;

class Assigns implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        $array = array();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $articleCollection = $objectManager->create('\Mirasvit\Kb\Model\ResourceModel\Article\Collection');
        $articleCollection->load();

        foreach ($articleCollection as $article) {
            $array[] = array('label' => 'Article: ' . $article->getName(), 'value' => 'a' . $article->getArticleId());
        }

        $categoryCollection = $objectManager->create('\Mirasvit\Kb\Model\ResourceModel\Category\Collection');
        $categoryCollection->load();

        foreach ($categoryCollection as $category) {
            if ($category->getLevel() == 0) {
                continue;
            }
            $array[] = array('label' => 'Category: ' . $category->getName(), 'value' => 'c' . $category->getCategoryId());
        }

        return $array;
    }
}

