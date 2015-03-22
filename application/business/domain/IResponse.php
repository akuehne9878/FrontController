<?php

namespace FrontController\application\business\domain;

use FrontController\application\usecases\IViewElement;


interface IResponse {

    public function write($data);

    public function addHeader($name, $value);

    public function setStatus($status);    

}
