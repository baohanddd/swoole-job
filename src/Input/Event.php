<?php
namespace baohan\SwooleJob\Input;

use baohan\Collection\Collection;
use Respect\Validation\Validator as v;

class Event
{
    /**
     * @var string
     */
    private $event;

    /**
     * @var []
     */
    private $payload;

    public function __construct(Collection $c)
    {
        v::key('event',   v::notEmpty()->stringType())
         ->key('payload', v::notEmpty()->arrayType())
         ->check($c->toArray());

        $this->setEvent($c->event);
        $this->setPayload($c->payload);
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return []
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @param [] $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }
}