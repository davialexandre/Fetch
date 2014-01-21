<?php
namespace Fetch;

class MessageIterator implements \Iterator, \Countable {

    /**
     * @var Server
     */
    protected $server;

    /**
     * @param int
     */
    protected $position;

    /**
     * The number of messages in the mailbox in the moment of the interator instantiation
     * @param int
     */
    protected $numberOfMessages;

    public function __construct($server, $size = null) {
        $this->server = $server;
        $this->position = 1;
        $this->numberOfMessages = $this->server->numMessages();
        if($size && is_numeric($size) && $size <= $this->numberOfMessages) {
            $this->numberOfMessages = $size;
        }
    }

    public function current()
    {
        return $this->server->getMessageBySequenceNumber($this->position);
    }

    public function next()
    {
        $this->position++;
        return $this->server->getMessageBySequenceNumber($this->position);
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->position > 0 && $this->position <= $this->numberOfMessages;
    }

    public function rewind()
    {
        $this->position = 1;
        return $this->server->getMessageBySequenceNumber($this->position);
    }

    public function count()
    {
        return $this->numberOfMessages;
    }
}
