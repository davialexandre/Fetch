<?php
namespace Fetch;

class MessageIterator implements \Iterator, \Countable {

    /**
     * Server instance used to fetch messages
     *
     * @var Server
     */
    protected $server;

    /**
     * The current Iterator position
     *
     * @param int
     */
    protected $position;

    /**
     * The start position of the Iterator
     *
     * @param int
     */
    protected $start;

    /**
     * The number of itens in the Iterator
     *
     * @param int
     */
    protected $size;

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
     * @param int $start the start position/sequence number of the Interator
     */
    public function __construct($server, $size = null, $start = 1)
    {
        $this->server = $server;
        if(!is_numeric($start) || $start < 1) {
            $start = 1;
        }
        $this->start = $start;
        $this->position = $this->start;
        $this->numberOfMessages = $this->server->numMessages();
        if(!$size || !is_numeric($size) || (($size + ($this->start - 1)) > $this->numberOfMessages)) {
            $this->size = $this->numberOfMessages - ($this->start - 1);
        } else {
            $this->size = $size;
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
        return $this->position >= $this->start && $this->position < $this->start + $this->size;
    }

    /**
     * Moves the iterator to the first position
     */
    public function rewind()
    {
        $this->position = $this->start;
    }

    /**
     * Returns the number of messages in the iterator
     *
     * @return int
     */
    public function count()
    {
        return $this->size;
    }
}
