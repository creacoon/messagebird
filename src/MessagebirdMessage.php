<?php

namespace NotificationChannels\Messagebird;

use Illuminate\Database\Eloquent\Model;

class MessagebirdMessage
{
    public $body;
    public $originator;
    public $recipients;
    public $reference;
    public $smsable;

    public static function create($body = '')
    {
        return new static($body);
    }

    public function __construct($body = '')
    {
        if (! empty($body)) {
            $this->body = trim($body);
        }
    }

    public function setBody($body)
    {
        $this->body = trim($body);

        return $this;
    }

    public function setOriginator($originator)
    {
        $this->originator = $originator;

        return $this;
    }

    public function setRecipients($recipients)
    {
        if (is_array($recipients)) {
            $recipients = implode(',', $recipients);
        }

        $this->recipients = $recipients;

        return $this;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    public function setSmsable(Model $smsable)
    {
        // Strip non-relevant info so we don't post this to Messagebird
        $r = new \ReflectionClass(get_class($smsable));
        $stripped_smsable = $r->newInstanceArgs();
        $stripped_smsable->id = $smsable->id;

        $this->smsable = $stripped_smsable;

        return $this;
    }

    public function toJson()
    {
        return json_encode($this);
    }
}
