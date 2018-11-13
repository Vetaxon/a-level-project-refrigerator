<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 13.11.18
 * Time: 18:52
 */

namespace App\Services;

use App\Events\ClientEvent;
use App\Services\Contracts\MessageLogEvent;

class LogEvent implements MessageLogEvent
{
    /**
     * @param string $message
     */
    public function send(string $message):void
    {
        activity()->withProperties($message)->log('messages');

        ClientEvent::dispatch($message);
    }
}