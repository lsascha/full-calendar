<?php
namespace Lsascha\FullCalendar\Controller;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use Neos\Flow\Annotations as Flow;

use Lsascha\FullCalendar\Domain\Model\EventSource;
use Lsascha\FullCalendar\Domain\Model\Event;

use Lsascha\FullCalendar\Domain\Repository\EventRepository;
use Lsascha\FullCalendar\Domain\Repository\EventSourceRepository;

use Neos\Eel\FlowQuery\FlowQuery;


class EventSourceController extends \Neos\Flow\Mvc\Controller\ActionController
{

    /**
     * @var string
     */
    protected $viewFormatToObjectNameMap = array(
        'json' => '\Neos\Flow\Mvc\View\JsonView'
    );

    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = array('application/json');


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
     * @param EventSource $eventSource
     * @param string $start
     * @param string $end
     * @return void
     */
    public function indexAction(EventSource $eventSource, $start = null, $end = null)
    {
        if ($this->request->getParentRequest() !== null) {
            if (($start === null) && $this->request->getParentRequest()->hasArgument("start")) {
                $start = $this->request->getParentRequest()->getArgument("start");
            }
            if (($end === null) && $this->request->getParentRequest()->hasArgument("start")) {
                $end = $this->request->getParentRequest()->getArgument("end");
            }
        }
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

        $startDateTime = new \DateTime( $start );
        $endDateTime = new \DateTime( $end );

        $this->view->assign('events', $this->eventRepository->findBetween($eventSource, $startDateTime, $endDateTime) );

    }


    /**
     * @param string $path
     * @return void
     */
    public function pageEventsAction($path)
    {

        $this->context = $this->contextFactory->create(array('workspaceName' => 'live'));

        //$rootNode = $this->context->getNode($path);
        $rootNode = $this->context->getNodeByIdentifier($path);

        $q = new FlowQuery([$rootNode]);
        $nodes = $q->children('[instanceof Lsascha.FullCalendar:Event]')->get();

        $nodeArray = [];
        foreach ($nodes as $node) {
            $title = $node->getProperty('title');
            $start = $node->getProperty('start');
            $end = $node->getProperty('end');
            $allDay = $node->getProperty('allDay');
            $rendering = $node->getProperty('rendering');

            $url = $this->uriBuilder
                        ->reset()
                        ->uriFor('show', array('node' => $node->getPath()), 'Frontend\Node', 'Neos.Neos');

            $nodeArray[] = [
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'allDay' => $allDay,
                'rendering' => $rendering,
                'url' => $url,
            ];
        }

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

        $this->view->assign('events', $nodeArray );
    }

    /**
     * @return void
     */
    public function eventsAction()
    {

    }

}
