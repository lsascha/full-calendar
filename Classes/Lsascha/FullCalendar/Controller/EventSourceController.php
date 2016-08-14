<?php
namespace Lsascha\FullCalendar\Controller;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use TYPO3\Flow\Annotations as Flow;

use Lsascha\FullCalendar\Domain\Model\EventSource;
use Lsascha\FullCalendar\Domain\Model\Event;

use Lsascha\FullCalendar\Domain\Repository\EventRepository;
use Lsascha\FullCalendar\Domain\Repository\EventSourceRepository;


class EventSourceController extends \TYPO3\Flow\Mvc\Controller\ActionController
{

    /**
     * @var string
     */
    protected $viewFormatToObjectNameMap = array(
        'html' => 'TYPO3\Fluid\View\TemplateView',
        'json' => '\TYPO3\Flow\Mvc\View\JsonView'
    );

    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = array('text/html', 'application/json');


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
     * @param EventSource $eventSource
     * @param string $start
     * @param string $end
     * @return void
     */
    public function indexAction(EventSource $eventSource, $start, $end)
    {

        /*
        if ( method_exists($this->view, 'setVariablesToRender') ) {
            $this->view->setVariablesToRender(array('eventSource' ));
        }

        if ( method_exists($this->view, 'setConfiguration') ) {
            $this->view->setConfiguration(
                array(
                    'eventSource' => array(
                        //'_only' => array('events', 'title', 'source', 'color', 'backgroundColor', 'borderColor', 'textColor'),
                        '_descend' => array(
                            'events' => array(
                                '_descendAll' => array(
                                    //'_only' => array('title'),
                                    '_descend' => array(
                                        'start' => array(
                                        ),
                                        'end' => array(
                                        ),
                                    )
                                )
                            )
                        )
                    )
                )
            );
        }


        $this->view->assign('eventSource', $this->eventSourceRepository->findAll()->getFirst() );
        */


        if ( method_exists($this->view, 'setVariablesToRender') ) {
            $this->view->setVariablesToRender(array('events' ));
        }

        if ( method_exists($this->view, 'setConfiguration') ) {
            $this->view->setConfiguration(
                array(
                    'events' => array(
                        '_descendAll' => array(
                            //'_only' => array('title'),
                            '_descend' => array(
                                'start' => array(
                                ),
                                'end' => array(
                                ),
                            )
                        )
                    )
                )
            );
        }

        //$startDateTime = strtotime($start);
        //$endDateTime = strtotime($end);
        $startDateTime = new \DateTime( $start );
        $endDateTime = new \DateTime( $end );

        //$this->view->assign('events', $this->eventSourceRepository->findByIdentifier($sourceid)->getEvents() );
        //$this->view->assign('events', $this->eventRepository->findByEventSource($sourceid) );
        $this->view->assign('events', $this->eventRepository->findBetween($eventSource, $startDateTime, $endDateTime) );

    }

    /**
     * @return void
     */
    public function eventsAction()
    {
        
    }

}
