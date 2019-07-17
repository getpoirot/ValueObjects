<?php
namespace Poirot\ValueObjects\Amount;

use Poirot\Std\Struct\aValueObject;


class AmountObject
    extends aValueObject
{
    protected $value = 0;
    protected $currency;


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
     * Add Value By Amount Object
     *
     * @param AmountObject $amount
     * @param int $power
     *
     * @return $this
     */
    function add(AmountObject $amount, $power = 1)
    {
        $value = $this->getValue();
        if ($this->currency === null )
            $this->setCurrency($amount->getCurrency());

        // TODO when currency is not match
        $this->setValue( $value + ($amount->getValue()*$power) );
        return $this;
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
            'EUR' => '€',
        ];

        return $r[strtoupper($curr)] ?? $curr;
    }


    // ..

    function __toString()
    {
        $curr = $this->getLiteralCurrency( $this->getCurrency() );
        return $curr.' '.$this->getValue();
    }
}
