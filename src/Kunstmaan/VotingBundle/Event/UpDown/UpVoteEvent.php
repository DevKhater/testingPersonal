<?php

namespace Kunstmaan\VotingBundle\Event\UpDown;

use Kunstmaan\VotingBundle\Event\EventInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event when an Up vote has been triggered
 */
class UpVoteEvent extends Event implements EventInterface
{

    private $request;

    private $reference;

    private $value;
    
    private $token;

    public function __construct(Request $request, $reference, $value, $token)
    {
        $this->request = $request;
        $this->reference = $reference;
        $this->value = $value;
        $this->token = $token;
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getToken()
    {
        return $this->token;
    }

}
