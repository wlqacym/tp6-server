<?php


namespace app\logic;


use app\BaseLogic;

class LoginLogic extends BaseLogic
{
    public function login($username, $password)
    {
        $data = $this->helper->admin->login($username, $password);
        return $data;
    }
}