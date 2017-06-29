<?php

namespace Phi\Event\Interfaces;


interface Listenable
{



    public function addParentListenable(Listenable $object);


    public function getDefaultListeners();


    public function addDefaultEventListener($name, $listener);


    public function addEventListener($eventName, \Closure $callback, $listenerName = null);

    /**
     * @param $event
     * @param array $data
     * Warning Polymorphic function
     */
    public function fireEvent($event, $data = array());

}



