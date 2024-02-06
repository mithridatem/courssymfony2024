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
    //méthode qui retourne un tableau avec la météo de la ville en entrée
    public function getWeatherCity(string $city): array {
        //récupération des données météo en fonction de la ville
        try {
            //récupération de la réponse de l'api
            $response = $this->client->request(
                'GET',
                'https://api.openweathermap.org/data/2.5/weather?q=' .$city. '&appid=' .$this->apiKey
            );
            //converion en tableau de la réponse de l'api
            $data = $response->toArray();
        } 
        //gestion des erreurs si code autre que 200 et 300
        catch (\Throwable $th) {
            //test si le code est 404 ou 400
            if($th->getCode() == 404 OR $th->getCode() == 400){
                //retouner le tableau d'erreur
                $data = ['cod'=>$th->getCode(),
                'message'=>$th->getCode() == 404?$city.' n\'existe pas':'le champs est vide'];
            }
        }
        //return du tableau
        return $data;
    }
}
