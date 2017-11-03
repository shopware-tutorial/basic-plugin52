<?php


namespace FirstPlugin\Test\Functional\Subscriber\Frontend;

use FirstPlugin\Subscriber\Frontend\DetailChangeTitle;
use FirstPlugin\Test\UserHelper;

class DetailChangeTitleTest extends \Enlight_Components_Test_Controller_TestCase
{
    use UserHelper;


    protected function tearDown()
    {
        parent::tearDown();
        $this->reset();
    }

    public function testChangeProductTitle()
    {
        $this->loginUser();

        $this->dispatch('/genusswelten/edelbraende/9/special-finish-lagerkorn-x.o.-32');
        $snippet = $this->getTransletionByIdent();
        $this->assertNotFalse(strpos($this->_view->getAssign('sArticle')['articleName'], $snippet));
        
        $this->logoutUser();
    }

    public function testNoChangeProductTitle()
    {
        $this->dispatch('/sommerwelten/accessoires/170/sonnenbrille-red?number=SW10170');
        $snippet = $this->getTransletionByIdent();
        $this->assertFalse(strpos($this->_view->getAssign('sArticle')['articleName'], $snippet));
    }


    /**
     * @return string
     */
    private function getTransletionByIdent(): string
    {
        /** @var \Enlight_Components_Snippet_Namespace $namespace */
        $namespace = Shopware()->Container()->get('snippets')->getNamespace(
            'frontend/detail/firstplugin'
        );
        return $namespace->get(DetailChangeTitle::snippetName);
    }
}