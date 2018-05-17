<?php
namespace Poirot\ValueObjects;

use Poirot\Std\Struct\aValueObject;
use Poirot\ValueObjects\Geo\Geohash;


/*
 * - As a String:
 *   "location": "37.339724,-121.873848"
 *
 * - As a Geo Hash:
 *   "location": "9q9k6tqptt03"
 *
 * - As an Array:
 *   "location": [37.339724,-121.873848]
 */
class GeoObject
    extends aValueObject
{
    const FORMAT_DEFAULT = 1;
    const FORMAT_GEOHASH = 2;


    /** @var array [lon, lat] */
    protected $geo;
    /** @var string Geo Lookup Caption  */
    protected $caption;

    private $_format;


    /**
     * Set GeoLocation
     *
     * the first field should contain the longitude value and the second
     * field should contain the latitude value.
     *
     * [ "lon": 28.5122077,
     *   "lat": 53.5818702 ]
     * or
     * [ 28.5122077, 53.5818702 ]
     *
     * @param array [lon, lat] $location
     *
     * @return $this
     */
    function setGeo(array $location)
    {
        if ( empty($location) )
            return $this;


        if ( isset($location['lon']) )
            $this->geo = array($location['lon'], $location['lat']);
        else
            $this->geo = array($location[0], $location[1]);

        return $this;
    }

    /**
     * Get Geo Location
     *
     * @param string|null $lonLat 'lat'|'lon'
     *
     * @return array|null
     * @throws \Exception
     */
    function getGeo($lonLat = null)
    {
        if ($lonLat === null)
            return $this->geo;

        if (!$this->geo)
            // No Geo Data Provided
            return null;

        # Geo Property (lon, lat)
        $lonLat = strtolower( (string) $lonLat );
        switch ($lonLat) {
            case 'lon':
            case 'long':
            case 'longitude':
                return $this->geo[0];
            case 'lat':
            case 'latitude':
                return $this->geo[1];
            default:
                throw new \Exception(sprintf('Unknown Geo Property (%s).', $lonLat));
        }
    }

    /**
     * Set Geo Lookup Caption
     *
     * @param string $entitle
     *
     * @return $this
     */
    function setCaption($entitle)
    {
        $this->caption = (string) $entitle;
        return $this;
    }

    /**
     * Get Geo Lookup Caption
     *
     * @return string
     */
    function getCaption()
    {
        return $this->caption;
    }


    /**
     * Set Str Format Presentation
     *
     * @param int $format
     * @return $this
     */
    function withFormat($format)
    {
        $this->_format = $format;
        return $this;
    }


    function __toString()
    {
        switch ($this->_format) {
            case 1:
            default:
                return $this->getGeo('lat').','.$this->getGeo('lon');
                break;
            case 2:
                return (new Geohash())->encode(
                    $this->getGeo('lat')
                    , $this->getGeo('lon')
                );
        }
    }

    // ..

    /**
     * @inheritdoc
     *
     * Also Parse Geo From String
     */
    static function parseWith($optionsResource, array $_ = null)
    {
        if ( is_string($optionsResource) ) {
            if ( strpbrk($optionsResource, '.,') ) {
                // "37.339724,-121.873848"
                //
                $geo = explode(',', $optionsResource);
                $optionsResource = [
                    'geo' => [
                        trim($geo[0]),
                        trim($geo[1])
                    ]
                ];
            } else {
                // "9q9k6tqptt03"
                //
                $geoHash = new Geohash();
                $optionsResource = [
                    'geo' => $geoHash->decode($optionsResource),
                ];
            }
        }


        return parent::parseWith($optionsResource);
    }

    /**
     * @inheritdoc
     * @ignore
     */
    static function isConfigurableWith($optionsResource)
    {
        return
            ( is_string($optionsResource) && \Poirot\ValueObjects\Geo\isValidGeoStr($optionsResource) )
            || parent::isConfigurableWith($optionsResource);
    }
}
