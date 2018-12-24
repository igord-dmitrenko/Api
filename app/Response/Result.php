<?php

namespace App\Response;

class Result
{
    /**
     * @var null|bool
     */
    private $status = null;

    /**
     * @var null|array
     */
    private $data = null;

    /**
     * @var array
     */
    private $messages = [];

    /**
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasStatus()
    {
        if (is_null($this->status)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isStatusSuccess()
    {
        if (!is_null($this->status) && $this->status === true) {
            return true;
        }

        return false;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        if (is_null($this->data)) {
            return false;
        }

        return true;
    }

    /**
     * @param array $messages
     *
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function addMessageRecord($message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {
        if (empty($this->messages)) {
            return false;
        }

        return true;
    }

    /**
     * @param null|int $code
     *
     * @return array
     */
    public function toArray($code = null)
    {
        $body = [];

        if ($this->hasStatus()) {
            if ($this->isStatusSuccess()) {
                $body['success'] = true;
            } else {
                $body['success'] = false;
            }
        }

        if (!is_null($code)) {
            $body['code'] = $code;
        }

        if ($this->hasData()) {
            $body['data'] = $this->getData();
        }

        if ($this->hasMessages()) {
            $body['messages'] = $this->getMessages();
        }

        return $body;
    }
}
