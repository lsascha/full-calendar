<?php
namespace Lsascha\FullCalendar\Controller\Module\Administration;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use Neos\Flow\Annotations as Flow;

use Neos\Flow\Error\Message;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Controller\Module\AbstractModuleController;

use Lsascha\FullCalendar\Domain\Model\EventSource;
use Lsascha\FullCalendar\Domain\Model\Event;

use Lsascha\FullCalendar\Domain\Repository\EventRepository;
use Lsascha\FullCalendar\Domain\Repository\EventSourceRepository;

use Neos\Eel\FlowQuery\FlowQuery;

class CalendarManagementController extends AbstractModuleController
{

	///**
	//* @var \Neos\Flow\Security\Context
	//* @Flow\Inject
	//*/
	//protected $securityContext;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

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
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = array(
        'html' => 'Neos\FluidAdaptor\View\TemplateView',
        'json' => 'Neos\Flow\Mvc\View\JsonView'
    );

    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     * @see http://www.iana.org/assignments/media-types/index.html
     */
    protected $supportedMediaTypes = array(
        'text/html',
        'application/json'
    );


    /**
     * @return void
     */
    public function indexAction()
    {

        $eventSources = $this->eventSourceRepository->findAll();


        /*$this->context = $this->contextFactory->create(array('workspaceName' => 'live'));
        $eventSourceCount = [];
        foreach ($eventSources as $eventSource) {
            $rootNode = $this->context->getNodeByIdentifier($eventSource->getPageSource());

            $q = new FlowQuery([$rootNode]);
            $nodeCount = $q->children('[instanceof Lsascha.FullCalendar:Event]')->count();

            $eventSourceCount[$eventSource->getIdentifier()]['pageCount'] = $nodeCount;

            unset($q);
            unset($nodeCount);
        }*/

        $this->view->assign('eventSources', $eventSources);

    }

	/**
	 * new calendar form
	 *
	 * @return void
	 */
	public function newAction() {
	}


	/**
	 * Creates new Calendar Event Source
	 * @param EventSource $newEventSource
	 * @return void
	 */
	public function createAction(EventSource $newEventSource) {

		$this->eventSourceRepository->add($newEventSource);

		$this->redirect('index');
	}

	/**
	 * edit Calendar Event Source
	 * @param EventSource $eventSource
	 * @return void
	 */
	public function editAction(EventSource $eventSource) {

       $this->view->assign( 'language', \Lsascha\FullCalendar\Controller\StandardController::getLanguage() );

		//$csrf = $this->securityContext->getCsrfProtectionToken();
		//$this->view->assign('csrf', $csrf);

		$this->view->assign('eventSource', $eventSource);
	}

	/**
	 * update Calendar Event Source with new data
	 * @param EventSource $eventSource
	 * @return void
	 */
	public function updateAction(EventSource $eventSource) {
		$this->eventSourceRepository->update($eventSource);

        $header = 'saved Calendar';
        $message = 'saved Calendar "'.$eventSource->getTitle().'"';
        $this->addFlashMessage($message, $header, \Neos\Error\Messages\Message::SEVERITY_OK);

		$this->redirect('index');
	}


    /**
     * @param EventSource $eventSource
     * @return void
     */
    public function deleteAction(EventSource $eventSource)
    {
        $this->eventSourceRepository->remove($eventSource);

        $header = 'deleted Calendar';
        $message = 'deleted Calendar';
        $this->addFlashMessage($message, $header, \Neos\Error\Messages\Message::SEVERITY_OK);

        $this->redirect('index');
    }




    /**
     * add event for source
     *
     * @param EventSource $eventSource
     * @param string $title title of event
     * @param boolean $allDay is event all day?
     * @param string $start start-time and date of event (if empty current datetime is used)
     * @param string $end end-time and date of event
     * @return void
     */
    public function addEventAction(EventSource $eventSource, $title, $allDay, $start, $end = NULL)
    {

        $this->view->setVariablesToRender(array('return'));

        $event = new Event();
        $event->setEventSource($eventSource);
        $event->setTitle($title);
        $event->setAllDay($allDay);
        if ($start)
        {
            $startDateTimeObj = new \DateTime($start);
            $event->setStart($startDateTimeObj);
        }
        if ($end)
        {
            $endDateTimeObj = new \DateTime($end);
            $event->setEnd($endDateTimeObj);
        }
        else
        {
        	if ($allDay) {
            	$event->setEnd( $event->getStart() );
        	} else {
        		$endDateTimeObj = clone $startDateTimeObj;
        		$endDateTimeObj->add(new \DateInterval('PT2H'));
        		$event->setEnd( $endDateTimeObj );
        	}
        }
        $this->eventRepository->add($event);


        $this->view->assign('return', 1 );

    }


    /**
     * update event
     *
     * @param EventSource $eventSource
     * @param Event $event
     * @param string $title title of event
     * @param boolean $allDay is event all day?
     * @param string $start start-time and date of event (if empty current datetime is used)
     * @param string $end end-time and date of event
     * @return void
     */
    public function updateEventAction(EventSource $eventSource, Event $event, $title, $allDay, $start, $end = NULL)
    {
        $this->view->setVariablesToRender(array('return'));

    	if ($event->getEventSource() == $eventSource) {

	        $event->setTitle($title);
	        $event->setAllDay($allDay);
	        if ($start)
	        {
	            $startDateTimeObj = new \DateTime($start);
	            $event->setStart($startDateTimeObj);
	        }
	        if ($end)
	        {
	            $endDateTimeObj = new \DateTime($end);
	            $event->setEnd($endDateTimeObj);
	        }
	        else
	        {
	        	if ($allDay) {
	            	$event->setEnd( $event->getStart() );
	        	} else {
	        		$endDateTimeObj = clone $startDateTimeObj;
	        		$endDateTimeObj->add(new \DateInterval('PT2H'));
	        		$event->setEnd( $endDateTimeObj );
	        	}
	        }
	        $this->eventRepository->update($event);
    	}

        $this->view->assign('return', 1 );

    }


    /**
     * edit Event
     * @param Event $event
     * @return void
     */
    public function editEventFormAction(Event $event = null) {
        if (($event === null) && $this->request->getParentRequest()->hasArgument("event")) {
            $event = $this->request->getParentRequest()->getArgument("event");
        }
        $this->view->assign('event', $event);
    }

	/**
	 * delete Event
	 * @param Event $event
	 * @return string
	 */
	public function deleteEventFormAction(Event $event): string {
		$this->eventRepository->remove($event);

        //$this->redirect('editEventForm', NULL, NULL, ['event'=>$event]);
        return '<script type="text/javascript">window.close();</script>';
	}

	/**
	 * update Calendar Event with new data
	 * @param Event $event
	 * @return void
	 */
	public function saveEventFormAction(Event $event) {
		$this->eventRepository->update($event);

        $header = 'saved Event';
        $message = 'saved Event';
        $this->addFlashMessage($message, $header, \Neos\Error\Messages\Message::SEVERITY_OK);

		$this->redirect('editEventForm', NULL, NULL, ['event'=>$event]);
	}

}
