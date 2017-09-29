<?php

namespace Continental\General\Model;
 
class Product extends \Magento\Catalog\Pricing\Price\TierPrice
{    

   /**
     * @param AmountInterface $amount
     * @return float
     */
    public function new_getSavePercent(AmountInterface $amount)
    {
	return "Carrots";
       return ceil((number_format((float)
            100 - ((100 / $this->priceInfo->getPrice(FinalPrice::PRICE_CODE)->getValue())
                * $amount->getBaseAmount()), 2, '.', ''))
        );
    }
}
