<?php
namespace Lsascha\FullCalendar\Domain\Repository;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class EventSourceRepository extends Repository
{
    
    /**
     * Finds the eventSources from array
     *
     * @param array $eventSourceIds array of event source ids to fetch
     * @return Event
     */
    public function findAllByIds(array $eventSourceIds = NULL) {
            $query = $this->createQuery();

            if ($eventSourceIds !== NULL) {

        		$queryLimit = array();
	            foreach ($eventSourceIds as $eventSourceId) {
	            	$queryLimit[] = $query->equals('Persistence_Object_Identifier', $eventSourceId);
	            }

	            $query = $query->matching(
		                            $query->logicalOr(
		                            	$queryLimit
		                            )
			                    );
            }

            return $query->execute();
    }

}
