<?php

namespace App\Controller;

use App\Utils\CacheUtils;
use App\Adapters\CorreiosAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Snc\RedisBundle\Client\Phpredis\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostalCodeController extends AbstractController
{
    public function __construct(ParameterBagInterface $parameterBag, CacheUtils $cacheUtils, CorreiosAdapter $correiosAdapter)
    {
        $this->parameterBag = $parameterBag;
        $this->cacheUtils = $cacheUtils;
        $this->correiosAdapter = $correiosAdapter;
    }

    /**
     * @Route("/postalcode/{code}")
     */
    public function get(String $code): Response
    {
        $cacheKey = 'postalcode:' . $code;
        $item = $this->cacheUtils->get($cacheKey);

        if (!$item->isHit()) {

            $cep = $this->correiosAdapter->getPostalCode($code);
            $this->cacheUtils->save($cacheKey, $cep, "P10D");
        }

        return new JsonResponse(["found" => true, "content" => $item->get($cacheKey), "cached" => true]);
    }
}