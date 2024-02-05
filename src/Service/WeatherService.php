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
        //récupération au format tableau
        $data = $response->toArray();
        //retour du tableau
        return $data;
    }
    //méthode qui retourne un tableau résultant de la requête API pour une ville donnée
    public function getWeatherCity(string $city): array
    {
        //récupération du tableau de données depuis WeatherService
        try {
            //stocker le résultat de la requête dans $response
            $response = $this->client->request(
                'GET',
                'https://api.openweathermap.org/data/2.5/weather?q='
                    . $city . '&appid=' . $this->apiKey
            );
            //récupération au format tableau
            $data = $response->toArray();
        } 
        //gestion des erreurs
        catch (\Throwable $th) {
            //si la ville n'est pas trouvée
            if ($th->getCode() == 404) {
                //retour d'un tableau avec un message d'erreur
                $data = ['error' => $city.' non trouvée'];
            }
        }
        //retour du tableau
        return $data;
    }
}
