<?php


namespace FirstPlugin\Test\Functional\Subscriber\Frontend;


use FirstPlugin\Test\UserHelper;

class DetailRedirectTest extends \Enlight_Components_Test_Controller_TestCase
{
    use UserHelper;

    protected function tearDown()
    {
        parent::tearDown();
        $this->reset();
    }

    public function testRedirectProductWithNotLoggedUser()
    {
        $response = $this->dispatch('/genusswelten/edelbraende/9/special-finish-lagerkorn-x.o.-32');
        $this->assertSame(302, $response->getHttpResponseCode());
        $this->assertTrue($response->isRedirect());
        $url = $response->getHeaders()[0]['value'];
        $this->assertNotFalse(strpos($url, '/register'));

        $constraint = new \PHPUnit_Framework_Constraint_StringEndsWith('/register');
        $this->assertTrue($constraint->evaluate($url, '', true));
    }

    public function testRedirectProductWithLoggedUser()
    {
        $this->loginUser();

        $response = $this->dispatch('/genusswelten/edelbraende/9/special-finish-lagerkorn-x.o.-32');
        $this->assertSame(200, $response->getHttpResponseCode());
        $this->assertFalse($response->isRedirect());

        $this->logoutUser();

    }

    public function testNoRedirectProductNotLoggedUser()
    {
        $response = $this->dispatch('/sommerwelten/accessoires/170/sonnenbrille-red?number=SW10170');
        $this->assertSame(200, $response->getHttpResponseCode());
        $this->assertFalse($response->isRedirect());
    }

    public function testNoRedirectProductLoggedUser()
    {
        $this->loginUser();
        $response = $this->dispatch('/sommerwelten/accessoires/170/sonnenbrille-red?number=SW10170');
        $this->assertSame(200, $response->getHttpResponseCode());
        $this->assertFalse($response->isRedirect());
        $this->logoutUser();
    }

}