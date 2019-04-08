<?php

namespace Phi\Event;


use Phi\Traits\Collection;

class ImmutableEvent extends Event
{
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

}