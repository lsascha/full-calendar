<?php
namespace Lsascha\FullCalendar\Command;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use Lsascha\FullCalendar\Domain\Model\EventSource;
use Lsascha\FullCalendar\Domain\Model\Event;

use Lsascha\FullCalendar\Domain\Repository\EventRepository;
use Lsascha\FullCalendar\Domain\Repository\EventSourceRepository;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class CalendarCommandController extends \Neos\Flow\Cli\CommandController
{

    /**
     * @Flow\Inject
     * @var EventSourceRepository
     */
    protected $eventSourceRepository;

    /**
     * @Flow\Inject
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * remove all persons from database
     *
     * removes all person entries
     *
     * @return void
     */
    public function clearAllCommand()
    {
        $this->eventRepository->removeAll();
        $this->eventSourceRepository->removeAll();
    }

    
    /**
     * add source
     *
     * @param string $title title of calendar
     * @param string $source source of calendar (keep empty to use own database)
     * @param string $color set color of source
     * @param string $backgroundColor set backgroundColor of source
     * @param string $borderColor set borderColor of source
     * @param string $textColor set textColor of source
     * @return void
     */
    public function addSourceCommand($title, $source = NULL, $color = NULL, $backgroundColor = NULL, $borderColor = NULL, $textColor = NULL)
    {

        $eventSource = new EventSource($title);

        if ($source)
        {
            $eventSource->setSource($source);
        }
        if ($color)
        {
            $eventSource->setColor($color);
        }
        if ($backgroundColor)
        {
            $eventSource->setBackgroundColor($backgroundColor);
        }
        if ($borderColor)
        {
            $eventSource->setBorderColor($borderColor);
        }
        if ($textColor)
        {
            $eventSource->setTextColor($textColor);
        }

        $this->eventSourceRepository->add($eventSource);

    }
    
    /**
     * add event for source
     *
     * @param string $sourceTitle source title of calendar
     * @param string $id inique id of event
     * @param string $title title of event
     * @param boolean $allDay is event all day?
     * @param string $start start-time and date of event (if empty current datetime is used)
     * @param string $end end-time and date of event
     * @return void
     */
    public function addEventCommand($sourceTitle, $id, $title, $allDay, $start = NULL, $end = NULL)
    {

        $eventSource = $this->eventSourceRepository->findOneByTitle($sourceTitle);

        $event = new Event();
        $event->setEventSource($eventSource);
        $event->setId($id);
        $event->setTitle($title);
        $event->setAllDay($allDay);
        if ($start)
        {
            $startDateTimeObj = \DateTime::createFromFormat('d.m.Y H:i', $start );
            $event->setStart($startDateTimeObj);
        }
        if ($end)
        {
            $endDateTimeObj = \DateTime::createFromFormat('d.m.Y H:i', $end );
            $event->setEnd($endDateTimeObj);
        }
        else
        {
            $event->setEnd( $event->getStart() );
        }
        $this->eventRepository->add($event);

        //$eventSource->addEvent($event);

    }

}
