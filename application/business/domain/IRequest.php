<?php

namespace FrontController\application\business\domain;

interface IRequest {

    public function issetParam($name);

    public function getParam($name);

    public function getParamList();

    public function getHeader($name);

    public function getPath();

    public function getMethod();
}
