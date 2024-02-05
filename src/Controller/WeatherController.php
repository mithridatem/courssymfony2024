<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private WeatherService $weatherService;
    public function __construct(WeatherService $weatherService){
        $this->weatherService = $weatherService;
    }
    //méthode qui retourne la météo courante
    #[Route('/weather/show', name: 'app_weather_show')]
    public function showWeather(): Response
    {   
        //récupération du tableau de données depuis WeatherService
        $weather = $this->weatherService->getWeather();
        return $this->render('weather/show_weather.html.twig', [
            'weather' => $weather,
        ]);
    }
}
