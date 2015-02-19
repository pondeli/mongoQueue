<?php
/**
 * Created by PhpStorm.
 * User: we
 * Date: 19.2.15
 * Time: 9:58
 */

namespace Runner;


class SoapExampleRunner{

    public function run()
    {
        $this->setStateItem('$QUEUE_STATE_RUNNING');//@TODO


        $requestParams = array(
            'CityName' => 'Berlin',
            'CountryName' => 'Germany'
        );
        $client = new \SoapClient('http://www.webservicex.net/globalweather.asmx?WSDL');
        $response = $client->GetWeather($requestParams);



        $this->setStateItem('QUEUE_STATE_TERMINATED');//@TODO

        return $response;
    }


    /**
     * @param $state
     */
    private function setStateItem($state)
    {
        //@TODO nastavovani state itemu v db mongo

        //
    }

}