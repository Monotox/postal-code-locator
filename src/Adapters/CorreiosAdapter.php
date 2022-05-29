<?php

namespace App\Adapters;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


class CorreiosAdapter extends AbstractController
{
    public function getPostalCode(String $code)
    {
        try {
            $client = new \SoapClient($this->parameterBag->get("CORREIOS_WSDL"));
            $cep = $client->consultaCEP(['cep' => $code]);
        } catch (\Exception $e) {
            return new JsonResponse(["found" => false, "content" => null], "cached");
        }

        return new JsonResponse([$cep->return]);
    }
}