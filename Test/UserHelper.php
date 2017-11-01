<?php


namespace FirstPlugin\Test;


trait UserHelper
{

    private function loginUser()
    {
        $userInfo = Shopware()->Db()->fetchRow(
            'SELECT id, password, email FROM s_user WHERE active = 1 LIMIT 1'
        );

        $session = Shopware()->Session();

        $this->assertNull($session->get('sUserId'));
        $request = $this->Request();
        $request->setPost([
            'email' => $userInfo['email'],
            'passwordMD5' => $userInfo['password']
        ]);
        $this->Front()->setRequest($request);

        Shopware()->Modules()->Admin()->sLogin(true);
        $this->resetRequest();

        $this->assertSame((int)$userInfo['id'], (int)$session->get('sUserId'));
    }

    private function logoutUser()
    {
        Shopware()->Modules()->Admin()->logout();
        $this->assertNull(Shopware()->Session()->get('sUserId'));
    }
}