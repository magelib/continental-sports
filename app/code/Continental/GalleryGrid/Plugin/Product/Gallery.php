<?php

namespace Continental\GalleryGrid\Plugin\Product;

class Gallery
{
    public function afterCreateBatchBaseSelect(
        \Magento\Catalog\Model\ResourceModel\Product\Gallery $subject,
        \Magento\Framework\DB\Select $select
    ) {
        $select->columns('gridable');

        return $select;
    }
}