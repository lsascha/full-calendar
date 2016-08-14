<?php
namespace Lsascha\FullCalendar\Domain\Model;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */
use TYPO3\Flow\Persistence\PersistenceManagerInterface;

use Lsascha\FullCalendar\Domain\Model\EventSource;
use Lsascha\FullCalendar\Domain\Model\Event;

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Event
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @ORM\ManyToOne(inversedBy="events")
     * @var \Lsascha\FullCalendar\Domain\Model\EventSource
     */
    protected $eventSource;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    protected $title;

    /**
     * @var boolean
     */
    protected $allDay;

    /**
     * @var \DateTime
     */
    protected $start;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $rendering = NULL;

    /**
     * Constructs this post
     */
    public function __construct() {
            $this->start = new \DateTime();
    }

    /**
     * @return \Lsascha\FullCalendar\Domain\Model\EventSource
     */
    public function getEventSource()
    {
        return $this->eventSource;
    }

    /**
     * @param \Lsascha\FullCalendar\Domain\Model\EventSource $eventSource
     * @return void
     */
    public function setEventSource(EventSource $eventSource)
    {
        $this->eventSource = $eventSource;
        $this->eventSource->addEvent($this);
    }

    /**
     * @return string
     */
    public function getId()
    {
        //return $this->id;
        return $this->persistenceManager->getIdentifierByObject($this);
    }

    ///**
    // * @param string $id
    // * @return void
    // */
    //public function setId($id)
    //{
    //    $this->id = $id;
    //}

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return boolean
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * @param boolean $allDay
     * @return void
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     * @return void
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     * @return void
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getRendering()
    {
        return $this->rendering;
    }

    /**
     * @param string $rendering
     * @return void
     */
    public function setRendering($rendering)
    {
        $this->rendering = $rendering;
    }

}
