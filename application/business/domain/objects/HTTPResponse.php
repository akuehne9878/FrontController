<?php

namespace FrontController\application\business\domain\objects;


use FrontController\application\business\domain\IResponse;
use FrontController\application\business\domain\IResponseModel;
use FrontController\application\usecases\IViewElement;

use FrontController\application\view\View;

class HTTPResponse implements IResponse {



    function __construct() {
        header('Content-Type: text/html; charset=utf-8');
    }

    public function write($data) {
        
    }

    public function addHeader($name, $value) {
        header($name . ": " . $value);
    }

    public function setStatus($status) {
        
    }



}
