<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\WeatherType;

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
    //méthode qui affiche la météo de la ville saisie
    #[Route('/weather/show/city', name:'app_weather_show_city')]
    public function showWeatherCity(Request $request) : Response {
        //construction du formulaire
        $form = $this->createForm(WeatherType::class);
        //récupération des données du fomulaire
        $form->handleRequest($request);
        //test si le formulaire est soumis et validé
        if($form->isSubmitted() AND $form->isValid()){
            //récupération des données de l'api
            $weather =  $this->weatherService->getWeatherCity($form->getData()['city']);
            //vider le formulaire
            $form = $this->createForm(WeatherType::class);
        }
        //retour de la vue
        return $this->render('weather/show_weather_city.html.twig',[
            'form' => $form->createView(),
            'weather' => $weather??null
        ]);
    }
}
