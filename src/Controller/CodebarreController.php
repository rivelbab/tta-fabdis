<?php

namespace App\Controller;

use App\Entity\Codebarre;
use App\Entity\Fournisseur;
use App\Repository\CodebarreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/codebarre/show", name="codebarre_show", methods="GET|POST")
     */
    public function show(Request $request, CodebarreRepository $codebarreRepository): Response
    {
        $fournisseurs = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        $selectedFr = $codebarres = null;
        $form = $this->createFormBuilder()
            ->add('selectedFr', ChoiceType::class, array(
                'placeholder' => "<< Selectionner un fournisseur ici >>",
                'choices' => $fournisseurs,
                'choice_label' => function ($fournisseurs) {
                    /** @var fournisseur $fournisseurs */
                    return strtoupper($fournisseurs->getCode());
                },
                'choice_attr' => function ($fournisseurs) {
                    return ['class' => 'fournisseur_' . strtolower($fournisseurs->getCode())];
                },
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedFr = $form->get('selectedFr')->getData();
            $fournisseurId = $selectedFr->getId();
        }

        if (isset($fournisseurId)) {
            $codebarres = $codebarreRepository->findByFournisseurId($fournisseurId);
            if (!isset($selectedFr)) {
                $selectedFr = $this->getDoctrine()->getRepository(Fournisseur::class)->findOneById($fournisseurId);
            }
        }

        return $this->render('codebarre/show.html.twig', [
            'form' => $form->createView(),
            'articles' => $codebarres,
            'fournisseur' => $selectedFr,
            'controller_name' => 'CodebarreController',
        ]);
    }

    /**
     * @Route("/codebarre/lookup", name="codebarre_lookup", methods="GET|POST")
     */
    public function lookup(Request $request, CodebarreRepository $codebarreRepository): JsonResponse
    {
        // ==== Id du fournisseur google shopping dans la base ====
        $fournisseurId = 1;
        $result = array();
        $items = $codebarreRepository->findByFournisseurId($fournisseurId);

        $url_parth1 = 'https://api.upcitemdb.com/prod/trial/search?s=';
        $url_parth2 = '&brand=';
        $url_parth3 = '&match_mode=0&type=product';
        // $endpoint = 'https://api.upcitemdb.com/prod/trial/search?s=d914&brand=fluke&match_mode=0&type=product';

		//$items = ['D914', 'TI350'];

		$ch = curl_init();

        foreach ($items as $item) {
			
            $ref = $item->getItem();
			$marque = (null !== $item->getMarque()) ? $item->getMarque() : "";
			//$marque = 'fluke';

			$endpoint = $url_parth1 . $ref . $url_parth2 . $marque . $url_parth3;
			//$endpoint = 'https://api.upcitemdb.com/prod/trial/search?s=d914&brand=fluke&match_mode=0&type=product';

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

            // HTTP GET
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpcode != 200) {
                $result[] = $httpcode;
            } else {
                $result[] = $response;
            }

            /* if you need to run more queries, do them in the same connection.
             * use rawurlencode() instead of URLEncode(), if you set search string
             * as url query param
             *****/
            //sleep(2);
		}
		curl_close($ch);

        return new JsonResponse($result);

        /*

    $r1 = json_decode($rp1);
    $r2 = json_decode($rp2);
    $r3 = json_decode($rp3);

    $rep = array($r1, $r2, $r3);

    return new JsonResponse($rep);

    $rp1 = '{
    "code": "OK",
    "total": 1,
    "offset": 0,
    "items": [
    {
    "ean": "0095969075749",
    "title": "Fluke Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "description": "Product Information: Fluke C190 Meter Heavy Duty Hard Carrying Case with Accessory Storage compartments will fit a wide variety of meters and accessories. Features a one year warranty. Features:#149; Heavy duty case with accessory storage compartments#149; One year warranty Specifications:#149; Model Number: C190#149; External Dimensions: 16.1 in x 18.6 in x 5.3 in",
    "upc": "095969075749",
    "brand": "Fluke",
    "model": "C190",
    "color": "Black",
    "size": "",
    "dimension": "2.6 X 3.9X 1.6 inches",
    "weight": "150 pounds",
    "currency": "",
    "lowest_recorded_price": 49,
    "highest_recorded_price": 255.55,
    "images": [
    "http://images.prosperentcdn.com/images/250x250/static.grainger.com/rp/s/is/image/Grainger/4YV71_AS01",
    "https://i5.walmartimages.com/asr/16330187-98bb-4763-9969-231021035173_1.7bd29efafccfda5d70170af5149d25dc.jpeg?odnHeight=450&odnWidth=450&odnBg=ffffff",
    "http://img1.r10.io/PIC/69762494/0/1/250/69762494.jpg",
    "https://images10.newegg.com/ProductImageCompressAll200/A5SJ_1_2017051188001542.jpg",
    "http://images.prosperentcdn.com/images/250x250/westsidewholesale.com/media/import/singlefeed/Fluke/C190.jpg"
    ],
    "offers": [
    {
    "merchant": "Westside Wholesale",
    "domain": "westsidewholesale.com",
    "title": "Fluke Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "currency": "",
    "list_price": "",
    "price": 179.95,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=x2q243t21353d4&tid=1&seq=1557146695&plt=899ed8c68785bf756fec36e3c639bf4f",
    "updated_t": 1453453433
    },
    {
    "merchant": "Newegg.com",
    "domain": "newegg.com",
    "title": "FLUKE C190 Hard Carrying Case, Polyprophylene, Yellow",
    "currency": "",
    "list_price": "",
    "price": 171.9,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=v2p243z2z2437484q2&tid=1&seq=1557146695&plt=868d58f717c625c0ecc00d35574af265",
    "updated_t": 1541680479
    },
    {
    "merchant": "Rakuten(Buy.com)",
    "domain": "rakuten.com",
    "title": "Fluke C190 Hard Case SM 190 Series",
    "currency": "",
    "list_price": "",
    "price": 199.99,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "Out of Stock",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2v25303z2238484&tid=1&seq=1557146695&plt=e24714e40ce6551844e5c6cf9aa19cd0",
    "updated_t": 1464286509
    },
    {
    "merchant": "Walmart Marketplace",
    "domain": "walmart.com",
    "title": "Fluke C190 Hard Carrying Case with Accessory Storage Compartments",
    "currency": "",
    "list_price": 199.99,
    "price": 185.95,
    "shipping": "0",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2o23323433364b4z2&tid=1&seq=1557146695&plt=16db535ea340f0d504ac0a26fd96c94e",
    "updated_t": 1543244299
    },
    {
    "merchant": "Grainger",
    "domain": "grainger.com",
    "title": "Fluke Hard Carrying Case,Polyprophylene,Yellow Model: C190",
    "currency": "",
    "list_price": "",
    "price": 230,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=u2v233x2y2639464t2&tid=1&seq=1557146695&plt=6ae1fc924f3b793dcb0344af4e776938",
    "updated_t": 1473825506
    }
    ],
    "asin": "B000LEF9QU",
    "elid": "202640403521"
    },
    {
    "ean": "0095969075111",
    "title": "Sony Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "description": "Product Information: Fluke C190 Meter Heavy Duty Hard Carrying Case with Accessory Storage compartments will fit a wide variety of meters and accessories. Features a one year warranty. Features:#149; Heavy duty case with accessory storage compartments#149; One year warranty Specifications:#149; Model Number: C190#149; External Dimensions: 16.1 in x 18.6 in x 5.3 in",
    "upc": "095969075749",
    "brand": "Sony",
    "model": "C190",
    "color": "Black",
    "size": "",
    "dimension": "2.6 X 3.9X 1.6 inches",
    "weight": "150 pounds",
    "currency": "",
    "lowest_recorded_price": 49,
    "highest_recorded_price": 255.55,
    "images": [
    "http://images.prosperentcdn.com/images/250x250/static.grainger.com/rp/s/is/image/Grainger/4YV71_AS01",
    "https://i5.walmartimages.com/asr/16330187-98bb-4763-9969-231021035173_1.7bd29efafccfda5d70170af5149d25dc.jpeg?odnHeight=450&odnWidth=450&odnBg=ffffff",
    "http://img1.r10.io/PIC/69762494/0/1/250/69762494.jpg",
    "https://images10.newegg.com/ProductImageCompressAll200/A5SJ_1_2017051188001542.jpg",
    "http://images.prosperentcdn.com/images/250x250/westsidewholesale.com/media/import/singlefeed/Fluke/C190.jpg"
    ],
    "offers": [
    {
    "merchant": "Westside Wholesale",
    "domain": "westsidewholesale.com",
    "title": "Fluke Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "currency": "",
    "list_price": "",
    "price": 179.95,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=x2q243t21353d4&tid=1&seq=1557146695&plt=899ed8c68785bf756fec36e3c639bf4f",
    "updated_t": 1453453433
    },
    {
    "merchant": "Newegg.com",
    "domain": "newegg.com",
    "title": "FLUKE C190 Hard Carrying Case, Polyprophylene, Yellow",
    "currency": "",
    "list_price": "",
    "price": 171.9,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=v2p243z2z2437484q2&tid=1&seq=1557146695&plt=868d58f717c625c0ecc00d35574af265",
    "updated_t": 1541680479
    },
    {
    "merchant": "Rakuten(Buy.com)",
    "domain": "rakuten.com",
    "title": "Fluke C190 Hard Case SM 190 Series",
    "currency": "",
    "list_price": "",
    "price": 199.99,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "Out of Stock",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2v25303z2238484&tid=1&seq=1557146695&plt=e24714e40ce6551844e5c6cf9aa19cd0",
    "updated_t": 1464286509
    },
    {
    "merchant": "Walmart Marketplace",
    "domain": "walmart.com",
    "title": "Fluke C190 Hard Carrying Case with Accessory Storage Compartments",
    "currency": "",
    "list_price": 199.99,
    "price": 185.95,
    "shipping": "0",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2o23323433364b4z2&tid=1&seq=1557146695&plt=16db535ea340f0d504ac0a26fd96c94e",
    "updated_t": 1543244299
    },
    {
    "merchant": "Grainger",
    "domain": "grainger.com",
    "title": "Fluke Hard Carrying Case,Polyprophylene,Yellow Model: C190",
    "currency": "",
    "list_price": "",
    "price": 230,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=u2v233x2y2639464t2&tid=1&seq=1557146695&plt=6ae1fc924f3b793dcb0344af4e776938",
    "updated_t": 1473825506
    }
    ],
    "asin": "B000LEF9QU",
    "elid": "202640403521"
    }
    ]
    }';

    $rp2 = '{
    "code": "OK",
    "total": 1,
    "offset": 0,
    "items": [
    {
    "ean": "0795969075749",
    "title": "Leica Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "description": "Product Information: Fluke C190 Meter Heavy Duty Hard Carrying Case with Accessory Storage compartments will fit a wide variety of meters and accessories. Features a one year warranty. Features:#149; Heavy duty case with accessory storage compartments#149; One year warranty Specifications:#149; Model Number: C190#149; External Dimensions: 16.1 in x 18.6 in x 5.3 in",
    "upc": "095969075749",
    "brand": "Leica",
    "model": "D519",
    "color": "Black",
    "size": "",
    "dimension": "2.6 X 3.9X 1.6 inches",
    "weight": "150 pounds",
    "currency": "",
    "lowest_recorded_price": 49,
    "highest_recorded_price": 255.55,
    "images": [
    "http://images.prosperentcdn.com/images/250x250/static.grainger.com/rp/s/is/image/Grainger/4YV71_AS01",
    "https://i5.walmartimages.com/asr/16330187-98bb-4763-9969-231021035173_1.7bd29efafccfda5d70170af5149d25dc.jpeg?odnHeight=450&odnWidth=450&odnBg=ffffff",
    "http://img1.r10.io/PIC/69762494/0/1/250/69762494.jpg",
    "https://images10.newegg.com/ProductImageCompressAll200/A5SJ_1_2017051188001542.jpg",
    "http://images.prosperentcdn.com/images/250x250/westsidewholesale.com/media/import/singlefeed/Fluke/C190.jpg"
    ],
    "offers": [
    {
    "merchant": "Westside Wholesale",
    "domain": "westsidewholesale.com",
    "title": "Fluke Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "currency": "",
    "list_price": "",
    "price": 179.95,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=x2q243t21353d4&tid=1&seq=1557146695&plt=899ed8c68785bf756fec36e3c639bf4f",
    "updated_t": 1453453433
    },
    {
    "merchant": "Newegg.com",
    "domain": "newegg.com",
    "title": "FLUKE C190 Hard Carrying Case, Polyprophylene, Yellow",
    "currency": "",
    "list_price": "",
    "price": 171.9,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=v2p243z2z2437484q2&tid=1&seq=1557146695&plt=868d58f717c625c0ecc00d35574af265",
    "updated_t": 1541680479
    },
    {
    "merchant": "Rakuten(Buy.com)",
    "domain": "rakuten.com",
    "title": "Fluke C190 Hard Case SM 190 Series",
    "currency": "",
    "list_price": "",
    "price": 199.99,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "Out of Stock",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2v25303z2238484&tid=1&seq=1557146695&plt=e24714e40ce6551844e5c6cf9aa19cd0",
    "updated_t": 1464286509
    },
    {
    "merchant": "Walmart Marketplace",
    "domain": "walmart.com",
    "title": "Fluke C190 Hard Carrying Case with Accessory Storage Compartments",
    "currency": "",
    "list_price": 199.99,
    "price": 185.95,
    "shipping": "0",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2o23323433364b4z2&tid=1&seq=1557146695&plt=16db535ea340f0d504ac0a26fd96c94e",
    "updated_t": 1543244299
    },
    {
    "merchant": "Grainger",
    "domain": "grainger.com",
    "title": "Fluke Hard Carrying Case,Polyprophylene,Yellow Model: C190",
    "currency": "",
    "list_price": "",
    "price": 230,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=u2v233x2y2639464t2&tid=1&seq=1557146695&plt=6ae1fc924f3b793dcb0344af4e776938",
    "updated_t": 1473825506
    }
    ],
    "asin": "B000LEF9QU",
    "elid": "202640403521"
    }
    ]
    }';
    $rp3 = '{
    "code": "OK",
    "total": 1,
    "offset": 0,
    "items": [
    {
    "ean": "2295969075749",
    "title": "Megger Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "description": "Product Information: Fluke C190 Meter Heavy Duty Hard Carrying Case with Accessory Storage compartments will fit a wide variety of meters and accessories. Features a one year warranty. Features:#149; Heavy duty case with accessory storage compartments#149; One year warranty Specifications:#149; Model Number: C190#149; External Dimensions: 16.1 in x 18.6 in x 5.3 in",
    "upc": "095969075749",
    "brand": "Megger",
    "model": "M215",
    "color": "Black",
    "size": "",
    "dimension": "2.6 X 3.9X 1.6 inches",
    "weight": "150 pounds",
    "currency": "",
    "lowest_recorded_price": 49,
    "highest_recorded_price": 255.55,
    "images": [
    "http://images.prosperentcdn.com/images/250x250/static.grainger.com/rp/s/is/image/Grainger/4YV71_AS01",
    "https://i5.walmartimages.com/asr/16330187-98bb-4763-9969-231021035173_1.7bd29efafccfda5d70170af5149d25dc.jpeg?odnHeight=450&odnWidth=450&odnBg=ffffff",
    "http://img1.r10.io/PIC/69762494/0/1/250/69762494.jpg",
    "https://images10.newegg.com/ProductImageCompressAll200/A5SJ_1_2017051188001542.jpg",
    "http://images.prosperentcdn.com/images/250x250/westsidewholesale.com/media/import/singlefeed/Fluke/C190.jpg"
    ],
    "offers": [
    {
    "merchant": "Westside Wholesale",
    "domain": "westsidewholesale.com",
    "title": "Fluke Meter Heavy Duty Hard Carrying Case with Accessory Storage Black, 16.1\" x 18.6\" x 5.3\"",
    "currency": "",
    "list_price": "",
    "price": 179.95,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=x2q243t21353d4&tid=1&seq=1557146695&plt=899ed8c68785bf756fec36e3c639bf4f",
    "updated_t": 1453453433
    },
    {
    "merchant": "Newegg.com",
    "domain": "newegg.com",
    "title": "FLUKE C190 Hard Carrying Case, Polyprophylene, Yellow",
    "currency": "",
    "list_price": "",
    "price": 171.9,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=v2p243z2z2437484q2&tid=1&seq=1557146695&plt=868d58f717c625c0ecc00d35574af265",
    "updated_t": 1541680479
    },
    {
    "merchant": "Rakuten(Buy.com)",
    "domain": "rakuten.com",
    "title": "Fluke C190 Hard Case SM 190 Series",
    "currency": "",
    "list_price": "",
    "price": 199.99,
    "shipping": "Free Shipping",
    "condition": "New",
    "availability": "Out of Stock",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2v25303z2238484&tid=1&seq=1557146695&plt=e24714e40ce6551844e5c6cf9aa19cd0",
    "updated_t": 1464286509
    },
    {
    "merchant": "Walmart Marketplace",
    "domain": "walmart.com",
    "title": "Fluke C190 Hard Carrying Case with Accessory Storage Compartments",
    "currency": "",
    "list_price": 199.99,
    "price": 185.95,
    "shipping": "0",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=w2o23323433364b4z2&tid=1&seq=1557146695&plt=16db535ea340f0d504ac0a26fd96c94e",
    "updated_t": 1543244299
    },
    {
    "merchant": "Grainger",
    "domain": "grainger.com",
    "title": "Fluke Hard Carrying Case,Polyprophylene,Yellow Model: C190",
    "currency": "",
    "list_price": "",
    "price": 230,
    "shipping": "",
    "condition": "New",
    "availability": "",
    "link": "http://www.upcitemdb.com/norob/alink/?id=u2v233x2y2639464t2&tid=1&seq=1557146695&plt=6ae1fc924f3b793dcb0344af4e776938",
    "updated_t": 1473825506
    }
    ],
    "asin": "B000LEF9QU",
    "elid": "202640403521"
    }
    ]
    }';

    $r1 = json_decode($rp1);
    $r2 = json_decode($rp2);
    $r3 = json_decode($rp3);

    $rep = array($r1, $r2, $r3);

    return new JsonResponse($rep);
     */
    }

    /**
     * @Route("/saveproduct", name="saveproduct", methods="GET|POST")
     */
    public function save(Request $request): JsonResponse
    {
        //$data = $_POST['produits'];
        $data = $request->request->get('produits');

        $p = json_encode($data);
        print_r($p);

        $file = './data/produits.txt';
        file_put_contents($file, $p);

        return new JsonResponse($p);
    }
}
