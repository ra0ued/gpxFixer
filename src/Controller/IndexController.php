<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
    * @Route("/")
    */
    public function indexAction()
    {
        return new Response(
            '<html><body>Hello world!</body></html>'
        );
    }

    /**
     * @Route("/get")
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function fileAction()
    {
        // send the file contents and force the browser to download it
        return $this->file('/path/to/some_file.pdf');
    }
}