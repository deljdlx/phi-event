<?php
namespace Phi\Event\Traits;


use Phi\Event\Event;
use Phi\Event\Listener;

Trait Listenable
{


    /**
     * @var Listener[]
     */
    protected $listeners=array();
    protected $defaultListeners=array();


    public function getDefaultListeners() {
        return $this->defaultListeners;
    }



    public function addDefaultEventListener($name, $listener) {
        if(!isset($this->defaultListeners[$name])) {
            $this->defaultListeners[$name]=array();
        }
        $this->defaultListeners[$name][]=$listener;
        return $this;
    }



    public function addEventListener($eventName, \Closure $callback, $listenerName=null) {

        $normalizedEventName=strtolower($eventName);

        if(!isset($this->listeners[$normalizedEventName])) {
            $this->listeners[$normalizedEventName]=array();
        }


        $listener=new Listener($eventName, $callback);

        if($listenerName) {
            $this->listeners[$normalizedEventName][$listenerName]=$listener;
        }
        else {
            $this->listeners[$normalizedEventName][]=$listener;
        }
        return $this;
    }




    public function fireEvent($eventName, $data=array()) {

        $normalizedEventName=strtolower($eventName);


        $event=new Event($this, $eventName, $data);


        if(isset($this->listeners[$normalizedEventName])) {
            foreach ($this->listeners[$normalizedEventName] as $listener) {
                $listener->handleEvent($event);
            }
        }

        if(!$event->isDefaultPrevented()) {
            if(isset($this->defaultListeners[$normalizedEventName])) {
                foreach ($this->defaultListeners[$normalizedEventName] as $listener) {
                    $listener->handleEvent($event);
                }
            }
        }

    }
}



