<?php
namespace Poirot\ValueObjects\Amount;

use Poirot\Std\Struct\aValueObject;


class AmountObject
    extends aValueObject
{
    protected $value = 0;
    protected $currency = 'IRR';


    /**
     * Set Amount
     *
     * @param float|int $value
     *
     * @return $this
     */
    function setValue($value)
    {
        $this->value = (float) $value;
        return $this;
    }

    /**
     * Get Amount
     *
     * @return int|float
     */
    function getValue()
    {
        return $this->value;
    }

    /**
     * Set Currency
     * https://en.wikipedia.org/wiki/ISO_4217
     *
     * @param string $currency
     *
     * @return $this
     */
    function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get Payment Currency
     *
     * @return string
     */
    function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get Literal From String
     *
     * @param string $curr
     *
     * @return string
     */
    function getLiteralCurrency($curr)
    {
        $r = [
            'IRR' => '﷼',
        ];


        return ( isset($r['IRR']) ) ? $r['IRR'] : $curr;
    }


    // ..

    function __toString()
    {
        $curr = $this->getLiteralCurrency( $this->getCurrency() );

        return $curr.' '.$this->getValue();
    }
}
