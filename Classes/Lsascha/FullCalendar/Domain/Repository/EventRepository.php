<?php
namespace Lsascha\FullCalendar\Domain\Repository;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use Lsascha\FullCalendar\Domain\Model\EventSource;
use Lsascha\FullCalendar\Domain\Model\Event;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\QueryResultInterface;

/**
 * @Flow\Scope("singleton")
 */
class EventRepository extends Repository
{

    
    /**
     * Finds the events of a eventSource between 2 datetimes
     *
     * @param EventSource $eventSource The reference eventSource
     * @param \DateTime $start The start datetime
     * @param \DateTime $end The end datetime
     * @return Event
     */
    public function findBetween(EventSource $eventSource, \DateTime $start, \DateTime $end) {
            $query = $this->createQuery();
            return
                    $query->matching(
                            $query->logicalAnd([
                                    $query->equals('eventSource', $eventSource),
                                    $query->logicalOr([
                                    	// if start + end is between limits
                                    	$query->logicalAnd([
		                                    $query->greaterThanOrEqual('start', $start),
		                                    $query->lessThanOrEqual('end', $end),
	                                   	]),
                                    	// if start + end exceeds limits
                                    	$query->logicalAnd([
		                                    $query->lessThanOrEqual('start', $start),
		                                    $query->greaterThanOrEqual('end', $end),
	                                   	]),
	                                   	// if start is between limit
                                    	$query->logicalAnd([
		                                    $query->greaterThanOrEqual('end', $end),
		                                    $query->lessThanOrEqual('start', $end),
	                                   	]),
										// if end is between limit
                                    	$query->logicalAnd([
		                                    $query->lessThanOrEqual('start', $start),
		                                    $query->greaterThanOrEqual('end', $start),
	                                   	]),
                                    ])
                            ])
                    )
                    ->setOrderings(array('start' => QueryInterface::ORDER_ASCENDING))
                    ->execute();
    }


}
