<?php
/**
 * Created by PhpStorm.
 * User: vitalii
 * Date: 13.11.18
 * Time: 18:47
 */

namespace App\Services\Contracts;


interface MessageLogEvent
{
    public function send(string $message):void;

}