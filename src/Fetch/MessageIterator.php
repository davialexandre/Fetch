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

    /**
     * Create a new MessageIterator.
     *
     * If size is bigger than the number of messages in the server, the later will be used.
     *
     * @param Server $server a Server instance which will be used to fetch the messages
     * @param int|null $size the max number of messages in the iterator. If null, the number of messages in the server will be used
     */
    public function __construct($server, $size = null) {
        $this->server = $server;
        $this->position = 1;
        $this->numberOfMessages = $this->server->numMessages();
        if($size && is_numeric($size) && $size <= $this->numberOfMessages) {
            $this->numberOfMessages = $size;
        }
    }

    /**
     * Returns the current Message for the iterator
     *
     * @return bool|Message|mixed
     */
    public function current()
    {
        return $this->server->getMessageBySequenceNumber($this->position);
    }

    /**
     * Moves the iterator to the next position
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Returns the current position of the iterator
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if the current position is valid.
     * Since message sequences starts with 1, it can't be 0 or less and it can' be higher than the number of messages
     *
     * @return bool
     */
    public function valid()
    {
        return $this->position > 0 && $this->position <= $this->numberOfMessages;
    }

    /**
     * Moves the iterator to the first position
     */
    public function rewind()
    {
        $this->position = 1;
    }

    /**
     * Returns the number of messages in the iterator
     *
     * @return int
     */
    public function count()
    {
        return $this->numberOfMessages;
    }
}
