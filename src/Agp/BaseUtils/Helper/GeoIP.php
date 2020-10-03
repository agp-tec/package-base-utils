<?php

namespace App\Utils;

/**
 * Class GeoIP
 * @package App\Utils
 *
 *
 * See https://www.geoplugin.com/webservices/php
 * 'geoplugin_request' => '187.102.36.25',
 * 'geoplugin_status' => 200,
 * 'geoplugin_delay' => '1ms',
 * 'geoplugin_credit' => 'Some of the returned data',
 * 'geoplugin_city' => 'Tubarao',
 * 'geoplugin_region' => 'Santa Catarina',
 * 'geoplugin_regionCode' => 'SC',
 * 'geoplugin_regionName' => 'Santa Catarina',
 * 'geoplugin_areaCode' => '',
 * 'geoplugin_dmaCode' => '',
 * 'geoplugin_countryCode' => 'BR',
 * 'geoplugin_countryName' => 'Brazil',
 * 'geoplugin_inEU' => 0,
 * 'geoplugin_euVATrate' => false,
 * 'geoplugin_continentCode' => 'SA',
 * 'geoplugin_continentName' => 'South America',
 * 'geoplugin_latitude' => '-28.494',
 * 'geoplugin_longitude' => '-49.0356',
 * 'geoplugin_locationAccuracyRadius' => '20',
 * 'geoplugin_timezone' => 'America/Sao_Paulo',
 * 'geoplugin_currencyCode' => 'BRL',
 * 'geoplugin_currencySymbol' => 'R$',
 * 'geoplugin_currencySymbol_UTF8' => 'R$',
 * 'geoplugin_currencyConverter' => '5.1186',
 *
 */
class GeoIP
{
    private $ip;
    private $response = array();

    public function __construct($ip)
    {
        $this->ip = $ip;
    }

    public function load()
    {
        if (strlen($this->ip < 9))
            exit;
        $res = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $this->ip));
        if (is_array($res)) {
            $this->response = $res;
            return $this->response['geoplugin_status'] == 200;
        }
        return false;
    }

    public function getCity()
    {
        return $this->response['geoplugin_city'];
    }

    public function getRegion()
    {
        return $this->response['geoplugin_regionName'];
    }

    public function getCountry()
    {
        return $this->response['geoplugin_countryName'];
    }

    public function getLatitude()
    {
        return $this->response['geoplugin_latitude'];
    }

    public function getLongitude()
    {
        return $this->response['geoplugin_longitude'];
    }

    public function getLatency()
    {
        return $this->response['geoplugin_delay'];
    }

    public function getRadius()
    {
        return $this->response['geoplugin_locationAccuracyRadius'];
    }
}
