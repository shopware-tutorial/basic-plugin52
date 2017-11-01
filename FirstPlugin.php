<?php

namespace FirstPlugin;

use Shopware\Components\Plugin;

/**
 * Class FirstPlugin
 * @package FirstPlugin
 */
class FirstPlugin extends Plugin
{
    const snippetName = 'local_product';
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Frontend_Detail' => 'redirectToLoginWhenNot18',
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'changeTitle'
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function redirectToLoginWhenNot18(\Enlight_Controller_ActionEventArgs $args)
    {
        $articleId = (int)$args->getRequest()->getParam('sArticle');
        $catId = $this->getCatId();
        $articleInCat = (bool)Shopware()->Db()->fetchOne(
            'SELECT id FROM s_articles_categories WHERE articleID = ? AND categoryID = ?',
            [
                $articleId, $catId
            ]
        );
        if ($articleInCat && !Shopware()->Modules()->Admin()->sCheckUser()) {
            return $args->getSubject()->redirect('/register');
        }
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function changeTitle(\Enlight_Controller_ActionEventArgs $args)
    {
        $view = $args->getSubject()->View();
        $sArticle = $view->getAssign('sArticle');
        if ($sArticle['categoryID'] === $this->getCatId()) {
            $sArticle['articleName'] = $sArticle['articleName'] . '(' . $this->getTransletionByIdent(self::snippetName) . ')';
            $view->assign('sArticle', $sArticle);
        }
    }

    /**
     * @return int
     */
    private function getCatId(): int
    {
        return (int)Shopware()->Config()->getByNamespace('FirstPlugin', 'catId');
    }

    /**
     * @param string $ident
     * @return string
     */
    private function getTransletionByIdent(string $ident): string
    {
        /** @var \Enlight_Components_Snippet_Namespace $namespace */
        $namespace = $this->container->get('snippets')->getNamespace(
            'frontend/detail/firstplugin'
        );
        return $namespace->get($ident, 'Regionalprodukt', true);
    }

}