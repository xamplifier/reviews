<?php

namespace Xamplifier\Reviews;

use Xamplifier\Reviews\EndPoint;

abstract class Base
{
    protected $name;

    protected $library;

    protected $errorMsg;

    public function __construct()
    {
        $this->setName();
    }

    public function getLibrary()
    {
        return $this->library;
    }

    public function setLibrary($library)
    {
        $this->library = $library;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name = null)
    {
        $fqcn = get_class($this);
        $pos = strrpos($fqcn, "\\") + 1;
        $className = substr($fqcn, $pos, strlen($fqcn));
        $this->name = $name ?: $className;
    }

    public function setErrorMsg(string $msg) : void
    {
        $this->errorMsg = $msg;
    }

    public function getErrorMsg() : ?string
    {
        return $this->errorMsg;
    }

    public function hasErrors() : boolean
    {
        return $this->errorMsg !== null;
    }

    abstract public function getReviews(EndPoint $e);
}
