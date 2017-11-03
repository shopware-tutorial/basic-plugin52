<?php


namespace FirstPlugin\Subscriber\Frontend;


use Enlight\Event\SubscriberInterface;
use FirstPlugin\Service\CategoryCheck;
use FirstPlugin\Service\CategoryCheckInterface;

class DetailChangeTitle implements SubscriberInterface
{
    const snippetName = 'local_product';

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
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'changeTitle'
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function changeTitle(\Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();
        $sArticle = $view->getAssign('sArticle');

        if ($this->categoryCheck->isArticleInCategory((int)$sArticle['articleID'])) {
            $sArticle['articleName'] = $sArticle['articleName'] . '(' . $this->getTransletionByIdent(self::snippetName) . ')';
            $view->assign('sArticle', $sArticle);
        }
    }

    /**
     * @param string $ident
     * @return string
     */
    private function getTransletionByIdent(string $ident): string
    {
        /** @var \Enlight_Components_Snippet_Namespace $namespace */
        $namespace = Shopware()->Container()->get('snippets')->getNamespace(
            'frontend/detail/firstplugin'
        );
        return $namespace->get($ident, 'Regionalprodukt', true);
    }
}