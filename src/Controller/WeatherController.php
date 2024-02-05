<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\WeatherType;
use Symfony\Component\HttpFoundation\Request;

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
        //retour de la vue
        return $this->render('weather/show_weather.html.twig', [
            'weather' => $weather,
        ]);
    }
    //méthode qui retourne la météo courante pour une ville donnée
    #[Route('/weather/show/city', name: 'app_weather_show_city')]
    public function showWeatherCity(Request $request): Response
    {   
        //création du formulaire
        $form = $this->createForm(WeatherType::class);
        //récupération des données du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //récupération de la ville
            $city = $form->getData()['city'];
            //récupération du tableau de données depuis WeatherService
            $weather = $this->weatherService->getWeatherCity($city);
            //reconstruction du formulaire => (vider le champ ville)
            $form = $this->createForm(WeatherType::class);
        }
        //retour de la vue
        return $this->render('weather/show_weather_city.html.twig', [
            'form' => $form->createView(),
            'weather' => $weather??null,
        ]);
    }
}
