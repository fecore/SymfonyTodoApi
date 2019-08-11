<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class IndexController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/")
     */
    public function index()
    {
        $info = [
            "message" => "Welcome to Todo API! For more information read README.md"
        ];
        return $this->handleView($this->view($info));
    }
}
