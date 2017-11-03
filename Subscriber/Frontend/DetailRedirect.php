<?php


namespace FirstPlugin\Subscriber\Frontend;


use Enlight\Event\SubscriberInterface;
use FirstPlugin\Service\CategoryCheck;
use FirstPlugin\Service\CategoryCheckInterface;

class DetailRedirect implements SubscriberInterface
{
    /**
     * @var CategoryCheck
     */
    private $categoryCheck;

    /**
     * @param CategoryCheckInterface $categoryCheck
     */
    public function __construct(CategoryCheckInterface $categoryCheck)
    {
        $this->categoryCheck = $categoryCheck;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend_Detail' => 'redirectToLoginWhenNot18',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function redirectToLoginWhenNot18(\Enlight_Controller_ActionEventArgs $args)
    {
        $articleId = (int)$args->getRequest()->getParam('sArticle');
        if ($this->categoryCheck->isArticleInCategory($articleId) && !Shopware()->Modules()->Admin()->sCheckUser()) {
            $args->getSubject()->redirect('/register');
        }
    }
}