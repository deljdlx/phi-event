<?php

namespace Phi\Event;


class Event
{

    protected $name = 'event';
    protected $data;
    protected $source;

    protected $defaultPrevented = false;

    protected $bubbling = true;


    public function __construct($source, $name = null, $data = array())
    {
        if ($name === null) {
            $name = static::class;
        }
        $this->name = $name;
        $this->data = $data;
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

    public function getData()
    {
        return $this->data;
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