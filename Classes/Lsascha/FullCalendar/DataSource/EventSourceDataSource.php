<?php
namespace Lsascha\FullCalendar\DataSource;

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Persistence\PersistenceManagerInterface;

use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

use Lsascha\FullCalendar\Domain\Repository\EventSourceRepository;

class EventSourceDataSource extends AbstractDataSource {

    /**
     * @var string
     */
    static protected $identifier = 'lsascha-fullcalendar-eventsources';

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
     * Get data
     *
     * @param NodeInterface $node The node that is currently edited (optional)
     * @param array $arguments Additional arguments (key / value)
     * @return array JSON serializable data
     */
    public function getData(NodeInterface $node = NULL, array $arguments)
    {


        $eventSources = $this->eventSourceRepository->findAll();

        $dataSource = array();
        foreach ($eventSources as $eventSource) {
            $dataSource[] = array('value' => $this->persistenceManager->getIdentifierByObject($eventSource), 'label' => $eventSource->getTitle(), 'group' => '', 'icon' => 'icon-calendar');
        }


        return $dataSource;
    }
}