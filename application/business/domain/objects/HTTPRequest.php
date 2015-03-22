<?php

namespace FrontController\application\business\domain\objects;

use FrontController\application\business\domain\IRequest;

class HTTPRequest implements IRequest {

    public function issetParam($name) {
        isset($_REQUEST[$name]);
    }

    public function getParam($name) {
        switch ($this->getMethod()) {
            case 'GET' :
                return filter_input(INPUT_GET, $name);
            case 'POST' :
                return filter_input(INPUT_POST, $name);
            default :
                return null;
        }
    }

    public function getParamList() {
        return $_REQUEST;
    }

    public function getHeader($name) {
        return $_SERVER[$name];
    }

    public function getPath() {
        return strtok($_SERVER["REQUEST_URI"], '?');
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

}
