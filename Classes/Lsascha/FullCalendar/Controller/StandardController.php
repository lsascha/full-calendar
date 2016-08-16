<?php
namespace Lsascha\FullCalendar\Controller;

/*
 * This file is part of the Lsascha.FullCalendar package.
 */

use TYPO3\Flow\Annotations as Flow;

use Lsascha\FullCalendar\Domain\Repository\EventRepository;
use Lsascha\FullCalendar\Domain\Repository\EventSourceRepository;

use TYPO3\Flow\Utility\Files;

class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController
{

    /**
     * @Flow\Inject
     * @var EventSourceRepository
     */
    protected $eventSourceRepository;

    /**
     * returns best fitting language for user
     * @return string locale
     */
    public static function getLanguage() {

        /**
         * list of languages from fullCalendar javascript 'Resources/Public/fullcalendar/lang/'
         * @var array
         */
        $calendarLanguages = array(
            'ar',
            'ar-ma',
            'ar-sa',
            'ar-tn',
            'bg',
            'ca',
            'cs',
            'da',
            'de',
            'de-at',
            'el',
            'en-au',
            'en-ca',
            'en-gb',
            'en-ie',
            'en-nz',
            'es',
            'eu',
            'fa',
            'fi',
            'fr',
            'fr-ca',
            'fr-ch',
            'gl',
            'he',
            'hi',
            'hr',
            'hu',
            'id',
            'is',
            'it',
            'ja',
            'ko',
            'lb',
            'lt',
            'lv',
            'nb',
            'nl',
            'nn',
            'pl',
            'pt',
            'pt-br',
            'ro',
            'ru',
            'sk',
            'sl',
            'sr',
            'sr-cyrl',
            'sv',
            'th',
            'tr',
            'uk',
            'vi',
            'zh-cn',
            'zh-tw'
        );


        $userLanguages = \TYPO3\Flow\I18n\Utility::parseAcceptLanguageHeader($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        foreach ($userLanguages as $userLang)
        {
            $fittingLang = array_search(strtolower($userLang), $calendarLanguages);

            if ( $fittingLang !== FALSE )
            {
                break;
            }
        }

        if ( $fittingLang === FALSE )
        {
            return 'en-gb';
        }
        else
        {
            return $calendarLanguages[$fittingLang];
        }

    }


    /**
     * @param array $sources
     * @return void
     */
    public function indexAction(array $sources = NULL)
    {
        $this->view->assign( 'language', self::getLanguage() );


        $defaultSources = $this->settings['standaloneCalendar']['eventSources'];

        if (!$defaultSources)
        {
            $loadSources = $sources;
        }
        else
        {
            $loadSources = $defaultSources;
        }

        $this->view->assign('eventSources', $this->eventSourceRepository->findAllByIds($loadSources) );

    }

    /**
     * @return void
     */
    public function pluginIndexAction()
    {
        $this->view->assign( 'language', self::getLanguage() );

        $node = $this->request->getInternalArgument('__node');
        $this->view->assign('node', $node );

        $this->view->assign('identifier', $node->getIdentifier() );


        $sources = $this->request->getInternalArgument('__sources');
        $defaultview = $this->request->getInternalArgument('__defaultview');
        $nowindicator = $this->request->getInternalArgument('__nowindicator');
        $headerLeft = $this->request->getInternalArgument('__headerLeft');
        $headerCenter = $this->request->getInternalArgument('__headerCenter');
        $headerRight = $this->request->getInternalArgument('__headerRight');

        $this->view->assign('eventSources', $this->eventSourceRepository->findAllByIds($sources) );
        $this->view->assign('defaultview', $defaultview );
        $this->view->assign('nowIndicator', $nowindicator );
        $this->view->assign('headerLeft', $headerLeft );
        $this->view->assign('headerCenter', $headerCenter );
        $this->view->assign('headerRight', $headerRight );

    }

}
