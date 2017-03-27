<?php

namespace ResumeBundle\Controller;

use ResumeBundle\Form\SearchForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $form = $this->createForm(SearchForm::class);
        return $this->render('ResumeBundle:static:welcome.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/actionSearch", name="actionSearch")
     */
    public function testAction(Request $request)
    {
        $form = $this->createForm(SearchForm::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('Githubname')->getData();

            $client = new \Github\Client();
            $users = $client->api('user')->find($name);
            if(sizeof($users['users'])==0){
                return $this->render('ResumeBundle:static:result.html.twig', [
                    'error' => "Dieser Name ". $name ." exestiert nicht.",
                    'form' => $form->createView(),
                ]);
            }

            $reposRoh =  $client->api('user')->repositories($name);
            $repo = array();
            for($i = 0; $i < sizeof($reposRoh) ; $i++){
                $repo[$i] = $reposRoh[$i]['language'];
            }
            $repo = array_count_values($repo);

            $countAll=0;
            foreach($repo as $value){
                $countAll += $value;
            }

            return $this->render('ResumeBundle:static:result.html.twig', [
                'countAll' => $countAll,
                'form' => $form->createView(),
                'name' => $name,
                'repo' => $repo,
            ]);
        }

        return $this->render('ResumeBundle:static:welcome.html.twig', [
            'form' => $form->createView(),
            'error' => 'Es ist ein Fehler aufgetretten.'
        ]);
    }
}
