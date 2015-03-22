<?php

namespace FrontController\application\usecases;

use FrontController\application\business\domain\IRequest;
use FrontController\application\business\domain\IResponse;

use FrontController\application\usecases\IViewElement;

class AbstractUseCase implements IViewElement {

    protected $userContext;

    public function __construct(array $userContext) {
        $this->userContext = $userContext;
    }

    /**
     * Defines the URL which will trigger the use case
     */
    public function getURL() {
        
    }

    /**
     * Defines the HTTP method that comes with the incoming request
     */
    public function getMethod() {
        return "GET";   // default
    }

    /**
     * Defines the actors/roles which are allowed to execute the use case
     */
    public function getRoles() {
        
    }

    /**
     * Maps the data from the UI to entities
     */
    public function mapDataFromRequest(IRequest &$request, array &$inputMap) {
        
    }

    /**
     * Defines the pre conditions of the use case
     */
    public function preExecute(array &$inputMap) {
        
    }

    /**
     * Event scenario with all possible exceptions
     */
    public function execute(array &$inputMap, array &$outputMap) {
        
    }

    /**
     * Defines the post conditions of the use case
     */
    public function postExecute(array &$outputMap) {
        
    }

    /**
     * Renders the view
     */
    public function renderView(array &$outputMap, IResponse &$response) {
        
    }
    
    
    public function getBaseTemplatePath() {
        return null;
    }
    
    public function getViewParameters() {
    	return null;
    }
    

    /**
     * Returns the userContext
     */
    public function getUserContext() {
        return $this->userContext;
    }

    /**
     * Returns class name of the concrete class
     */
    public function getClassname() {
        return get_called_class();
    }

}
