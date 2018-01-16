<?php
namespace Poirot\ValueObjects\Mobile
{
    /**
     * Is Valid Mobile Number?
     *
     * @param string $mobileNumber
     *
     * @return bool
     */
    function isValidMobileNum($mobileNumber)
    {
        $matches = [];
        $pattern = '/^[- .\(\)]?((?P<country_code>(98)|(\+98)|(0098)|0){1}[- .\(\)]{0,3})(?P<number>((90)|(91)|(92)|(93)|(99)){1}[0-9]{8})$/';
        return preg_match($pattern, (string) $mobileNumber, $matches);
    }

    /**
     * Parse Mobile Number Into Matches
     *
     * @param string $mobileNumber
     *
     * @return array
     */
    function parseMobileNum($mobileNumber)
    {
        $matches = [];
        $pattern = '/^[- .\(\)]?((?P<country_code>(98)|(\+98)|(0098)|0){1}[- .\(\)]{0,3})(?P<number>((90)|(91)|(92)|(93)|(99)){1}[0-9]{8})$/';
        if (! preg_match($pattern, (string) $mobileNumber, $matches) )
            return [];

        return [
            'country_code' => $matches['country_code'],
            'number'       => $matches['number'],
        ];
    }
}
