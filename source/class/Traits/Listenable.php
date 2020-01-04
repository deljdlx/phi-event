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
        return self::$defaultListeners;
    }


    public static function addDefaultEventListener($name, $listener)
    {
        if (!isset(self::$defaultListeners[$name])) {
            self::$defaultListeners[$name] = array();
        }
        self::$defaultListeners[$name][] = $listener;
        return self::$defaultListeners;
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
     * @param bool $staticData
     * @return Event
     */
    public function fireEvent($event, $data = array(), $staticData = true)
    {

        if (!$event instanceof Event) {
            $eventName = $event;
            $event = new Event($this, $event, $data);
        }
        else {
            $eventName = $event->getName();
        }


        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {

                if($staticData) {
                    $event->setVariables($data);
                }

                $listener->handleEvent($event);
            }
        }

        if (!$event->isDefaultPrevented()) {
            if (isset(self::$defaultListeners[$eventName])) {
                foreach (self::$defaultListeners[$eventName] as $listener) {

                    if($staticData) {
                        $event->setVariables($data);
                    }

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

        if($staticData) {
            $event->setVariables($data);
        }

        return $event;
    }

}



