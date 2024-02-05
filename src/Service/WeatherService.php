<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private HttpClientInterface $client;
    private string $apiKey;
    public function __construct(string $apiKey, HttpClientInterface $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }
    //méthode pour tester la clé API 
    public function testKey(): string
    {
        return $this->apiKey;
    }
    //méthode qui retourne un tableau résultant de la requête API
    public function getWeather(): array
    {
        //stocker le résultat de la requête dans $response
        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather?lon=1.44&lat=43.6&appid='
                . $this->apiKey
        );
        //récupération du json
        //$data =  $response->getContent();
        //récupération au format tableau
        $data = $response->toArray();
        //retour du tableau
        return $data;
    }
}
