<?php
namespace Lsascha\FullCalendar\Domain\Model;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */
use Neos\Flow\Persistence\PersistenceManagerInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class EventSource
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    const EVENT_OBJECT_SOURCE = 0;
    const EVENT_GOOGLE_SOURCE = 1;
    const EVENT_PAGES_SOURCE = 2;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $googleCalendarId = NULL;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $color = NULL;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $backgroundColor = NULL;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $borderColor = NULL;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $textColor = NULL;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $rendering = NULL;

    /**
     * @var int
     */
    protected $type = self::EVENT_OBJECT_SOURCE;

    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $pageSource = NULL;

    /**
     * @ORM\OneToMany(mappedBy="eventSource", cascade={"all"})
     * @ORM\OrderBy({"start" = "ASC"})
     * @var Collection<Event>
     */
    protected $events;


    /**
     * @param string $title
     * Constructs this post
     */
    public function __construct($title) {
            $this->events = new ArrayCollection();
            $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->persistenceManager->getIdentifierByObject($this);
    }

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
     * @return string
     */
    public function getGoogleCalendarId()
    {
        return $this->googleCalendarId;
    }

    /**
     * @param string $googleCalendarId
     * @return void
     */
    public function setGoogleCalendarId($googleCalendarId)
    {
        $this->googleCalendarId = $googleCalendarId;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return void
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     * @return void
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * @param string $borderColor
     * @return void
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;
    }

    /**
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     * @return void
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
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

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {

        if ($this->events instanceof \Neos\Flow\ObjectManagement\DependencyInjection\DependencyProxy) {
            $this->events->_activateDependency();
        }

        return $this->events;
    }

    /**
     * add a event
     *
     * @param Event $event
     * @return void
     */
    public function addEvent(Event $event)
    {
        $this->events->add($event);
    }

    /**
     * remove a event
     *
     * @param Event $event
     * @return void
     */
    public function removeEvent(Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $events
     * @return void
     */
    public function setEvents(\Doctrine\Common\Collections\Collection $events)
    {
        $this->events = $events;
    }


    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPageSource()
    {
        return $this->pageSource;
    }

    /**
     * @param string $pageSource
     * @return void
     */
    public function setPageSource($pageSource)
    {
        $this->pageSource = $pageSource;
    }

}
