<?php
/*
Copyright 2013 Urban Airship and Contributors
*/

namespace UrbanAirship\Push;

use UrbanAirship\UALog;

class PushRequest
{
    const PUSH_URL = "/api/push/";
    private $airship;
    private $audience;
    private $notification;
    private $options = null;
    private $in_app = null;
    private $deviceTypes;
    private $message;

    function __construct($airship)
    {
        $this->airship = $airship;
    }

    function setAudience($audience)
    {
        $this->audience = $audience;
        return $this;
    }

    function setNotification($notification)
    {
        $this->notification = $notification;
        return $this;
    }

    function setDeviceTypes($deviceTypes)
    {
        $this->deviceTypes = $deviceTypes;
        return $this;
    }

    function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    function setInApp($in_app)
    {
        $this->in_app = $in_app;
        return $this;
    }

    function getPayload()
    {
        $payload = array(
            'audience' => $this->audience,
            'notification' => $this->notification,
            'device_types' => $this->deviceTypes
        );
        if (!is_null($this->message)) {
            $payload['message'] = $this->message;
        }
        if (!is_null($this->options)) {
            $payload['options'] = $this->options;
        }
        if (!is_null($this->in_app)) {
            $payload['in_app'] = $this->in_app;
        }
        return $payload;
    }

    function send()
    {
        $uri = $this->airship->buildUrl(self::PUSH_URL);
        $logger = UALog::getLogger();

        $response = $this->airship->request("POST",
            json_encode($this->getPayload()), $uri, "application/json", 3);

        $payload = json_decode($response->raw_body, true);
        $logger->info("Push sent successfully.", array("push_ids" => $payload['push_ids']));
        return new PushResponse($response);
    }

}
