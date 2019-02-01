<?php

namespace Phi\Event;


class Listener
{


    protected $callback;
    protected $eventName;


    public function __construct($eventName, $callback)
    {
        $this->eventName = $eventName;
        $this->callback = $callback;
    }


    public function handleEvent(Event $event)
    {

        if ($event->getName() == $this->eventName) {
            if($this->callback instanceof \Closure) {
                $bindedClosure = $this->callback->bindTo($event);
                return call_user_func_array(array($bindedClosure, '__invoke'), array($event));
            }
            else {
                return call_user_func_array($this->callback, array($event));
            }

        }
        else {
            return false;
        }
    }


}