<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CodebarreController extends AbstractController
{
    /**
     * @Route("/codebarre", name="codebarre", methods="GET|POST")
     */
    public function index(Request $request): Response
    {        
        return $this->render('codebarre/index.html.twig', [
            'controller_name' => 'CodebarreController',
        ]);
    }

    /**
     * @Route("/codebarre/lookup", name="codebarre_lookup", methods="GET|POST")
     */
    public function lookup(Request $request): JsonResponse
    {
        $ref = 'D914';
        $marque = 'Fluke';

        $url_parth1 = 'https://api.upcitemdb.com/prod/trial/search?s=';
        $url_parth2 = '&brand=';
        $url_parth3 = '&match_mode=0&type=product';

       // $endpoint = 'https://api.upcitemdb.com/prod/trial/search?s=d914&brand=fluke&match_mode=0&type=product';

        $ch = curl_init();
        /* if your client is old and doesn't have our CA certs
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);*/
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

        $endpoint = $url_parth1 . $ref . $url_parth2 . $marque . $url_parth3;
        
        // HTTP GET
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode != 200) {
            //echo "error status $httpcode...\n";
            return new JsonResponse(['success' => false, 'response' => []]);
        } else {
            return new JsonResponse(['ref' => $ref, 'marque' => $marque, 'response' => $response]);
        }

        /* if you need to run more queries, do them in the same connection.
        * use rawurlencode() instead of URLEncode(), if you set search string
        * as url query param
        */
        sleep(2);
        // proceed with other queries
        curl_close($ch);
    }
}
