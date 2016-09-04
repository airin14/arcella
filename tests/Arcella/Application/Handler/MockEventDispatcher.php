<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MockEventDispatcher implements EventDispatcherInterface
{
    private $event = array();

    public function dispatch($eventName, Event $event = null)
    {
        $this->event[$eventName] = $event;
    }

    public function getEvent($event)
    {
        return $this->event[$event];
    }

    public function addListener($eventName, $listener, $priority = 0)
    {
        return null;
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        return null;
    }

    public function removeListener($eventName, $listener)
    {
        return null;
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        return null;
    }

    public function getListeners($eventName = null)
    {
        return null;
    }

    public function getListenerPriority($eventName, $listener)
    {
        return null;
    }

    public function hasListeners($eventName = null)
    {
        return null;
    }
}