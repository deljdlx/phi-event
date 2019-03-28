<?php

namespace Phi\Event;


use Phi\Traits\Collection;

class Event
{

    use Collection;

    protected $name = 'event';
    //protected $data;
    protected $source;

    protected $defaultPrevented = false;

    protected $bubbling = true;


    public function __construct($source, $name = null, $data = array())
    {
        if(is_array($name)) {
            $data = $name;
            $name = null;
        }

        if ($name === null) {
            $name = static::class;
        }
        $this->name = $name;
        $this->setVariables($data);
        $this->source = $source;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getData($key = null)
    {
        if($key === null) {
            return $this->getVariables();
        }
        else {
            return $this->getVariable($key);
        }

    }


    public function preventDefault()
    {
        $this->defaultPrevented = true;
    }

    public function isDefaultPrevented()
    {
        return $this->defaultPrevented;
    }

    public function stopBubbling()
    {
        $this->bubbling = false;
        return $this;
    }

    public function bubbling()
    {
        return $this->bubbling;
    }


}