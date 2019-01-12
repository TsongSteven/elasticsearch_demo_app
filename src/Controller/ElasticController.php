<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ElasticController extends Controller
{
    /**
     * @Route("/", name="elastic")
     */
    public function index(Request $request)
    {
		
		$searchQuery = $request->get('query');
		if(!empty($searchQuery)){
			
			$elasticaManager = $this->get('fos_elastica.manager');
			$query = $elasticaManager->getRepository('App\Entity\User')->find($searchQuery);
		}
		
		else{
			
			$em = $this->getDoctrine()->getManager();
			$dql = 'SELECT u FROM App:User u';
			$query = $em->createQuery($dql);			
		}
		
		
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), $request->query->getInt('limit', 10));		
		
		
        return $this->render('elastic/index.html.twig',[			
			'users' => $pagination,
		]
	  );
    }
}
