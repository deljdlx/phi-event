<?php

namespace Phi\Event\Traits;


use Phi\Event\Event;
use Phi\Event\Listener;
use Phi\Event\Interfaces\Listenable as IListenable;

Trait Listenable
{


    /**
     * @var Listener[]
     */
    private $listeners = array();
    private static $defaultListeners = array();

    /**
     * @var IListenable[]
     */
    private $parentListenables = array();


    public function addParentListenable(IListenable $object)
    {
        $this->parentListenables[] = $object;
        return $this;
    }


    public static function getDefaultListeners()
    {
        return static::$defaultListeners;
    }


    public static function addDefaultEventListener($name, $listener)
    {
        if (!isset(static::$defaultListeners[$name])) {
            static::$defaultListeners[$name] = array();
        }
        static::$defaultListeners[$name][] = $listener;
        return static::$defaultListeners;
    }


    public function addEventListener($eventName, $callback, $listenerName = null)
    {

        $normalizedEventName = strtolower($eventName);

        if (!isset($this->listeners[$normalizedEventName])) {
            $this->listeners[$normalizedEventName] = array();
        }


        if(!($callback instanceof  Listener)) {
            $listener = new Listener($eventName, $callback);
        }
        else {
            $listener = $callback;
        }


        if ($listenerName) {
            $this->listeners[$normalizedEventName][$listenerName] = $listener;
        }
        else {
            $this->listeners[$normalizedEventName][] = $listener;
        }
        return $this;
    }


    /**
     * @param $event
     * @param array $data
     * Warning Polymorphic function
     */
    public function fireEvent($event, $data = array())
    {

        if (!$event instanceof Event) {
            $normalizedEventName = strtolower($event);
            $event = new Event($this, $event, $data);
        }
        else {
            $normalizedEventName = strtolower($event->getName());
        }


        if (isset($this->listeners[$normalizedEventName])) {
            foreach ($this->listeners[$normalizedEventName] as $listener) {
                $listener->handleEvent($event);
            }
        }

        if (!$event->isDefaultPrevented()) {
            if (isset(static::$defaultListeners[$normalizedEventName])) {
                foreach (static::$defaultListeners[$normalizedEventName] as $listener) {
                    if($listener instanceof Listener) {
                        $listener->handleEvent($event);
                    }
                    else if(is_callable($listener)) {
                        call_user_func_array($listener, array($event));
                    }
                }
            }
        }


        foreach ($this->parentListenables as $parent) {
            if ($event->bubbling()) {
                $parent->fireEvent($event);
            }
        }
    }

}



