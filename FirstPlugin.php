<?php

namespace FirstPlugin;

use FirstPlugin\Service\CategoryCheck;
use FirstPlugin\Subscriber\Frontend\Detail;
use FirstPlugin\Subscriber\Frontend\DetailChangeTitle;
use FirstPlugin\Subscriber\Frontend\DetailRedirect;
use Shopware\Components\Plugin;

/**
 * Class FirstPlugin
 * @package FirstPlugin
 */
class FirstPlugin extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Front_StartDispatch' => 'onStartDispatch'
        ];
    }

    public function onStartDispatch()
    {
        $events = $this->container->get('events');
        $categoryCheck = new CategoryCheck();
        $subscribers = [
            new DetailChangeTitle($categoryCheck),
            new DetailRedirect($categoryCheck)
        ];

        foreach ($subscribers as $subscriber) {
            $events->addSubscriber($subscriber);
        }
    }

}