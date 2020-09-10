<?php

class ReactRequestException extends Exception
{
    protected $request = null;

    public function __construct(Exception $e, $request)
    {
        parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function hasRequest()
    {
        return !is_null($this->request);
    }
}