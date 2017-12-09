<?php
/**
 * Copyright © 2016 Magevolve Ltd. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Me\Econtacts\Model;

/**
 * Econtacts model
 *
 * @method \Me\Econtacts\Model\ResourceModel\Econtacts _getResource()
 * @method \Me\Econtacts\Model\ResourceModel\Econtacts getResource()
 * @method string getName()
 * @method string getEmail()
 * @method string getTelephone()
 * @method string getComment()
 * @method string getAnswer()
 * @method string getStoreId()
 */
class Econtacts extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Econtacts cache tag
     */
    const CACHE_TAG = 'me_econtacts';

    /**
     * @var string
     */
    protected $_cacheTag = 'me_econtacts';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'me_econtacts';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Me\Econtacts\Model\ResourceModel\Econtacts');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [
            self::CACHE_TAG . '_' . $this->getId(),
            self::CACHE_TAG . '_' . $this->getId()
        ];
    }
}
