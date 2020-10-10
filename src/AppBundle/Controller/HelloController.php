<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends Controller
{
    /**
     * @Route("/hello", name="hellopage")
     */
    public function indexAction(Request $request)
    {
          return new Response('<html><body>HELLO PAGE!</body></html>');
    }
}
