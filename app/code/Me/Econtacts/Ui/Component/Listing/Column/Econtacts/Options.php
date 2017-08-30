<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Ui\Component\Listing\Column\Econtacts;

use Magento\Store\Ui\Component\Listing\Column\Store\Options as StoreOptions;

/**
 * Store Options for Econtacts
 */
class Options extends StoreOptions
{
    /**
     * All Store Views value
     */
    const ALL_STORES = '0';

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->currentOptions['All Store Views']['label'] = __(
            'All Store Views'
        );
        $this->currentOptions['All Store Views']['value'] = self::ALL_STORES;

        $this->generateCurrentOptions();

        $this->options = array_values($this->currentOptions);

        return $this->options;
    }
}
