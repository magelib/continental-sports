<?php

namespace Continental\Spares\Model;

/**
 * Pay In Store payment method model
 */
class Poa extends \Magento\Payment\Model\Method\AbstractMethod
{

/**
* Payment code
*
* @var string
*/
protected $_code = 'poapayment';

/**
* Availability option
*
* @var bool
*/
protected $_isOffline = true;
}
