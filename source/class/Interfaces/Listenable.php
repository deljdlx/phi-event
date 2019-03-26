<?php

namespace Phi\Event\Interfaces;


interface Listenable
{



    public function addParentListenable(Listenable $object);


    public static function getDefaultListeners();


    public static function addDefaultEventListener($name, $listener);


    public function addEventListener($eventName, $callback, $listenerName = null);

    /**
     * @param $event
     * @param array $data
     * Warning Polymorphic function
     */
    public function fireEvent($event, $data = array());

}



