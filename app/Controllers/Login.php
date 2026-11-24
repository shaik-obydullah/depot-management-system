<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index(): string
    {
        return view('login');
    }

    public function loginProcess()
    {
        $userName = (string) $this->request->getPost('user_name');
        $password = (string) $this->request->getPost('user_password');

        $user = $this->appModel()->userLoginCheck($userName, $password);

        if ($user === null) {
            $this->logActivity(
                'warning',
                'Failed login attempt from ' . $this->request->getIPAddress(),
                null
            );

            return $this->plain('invalid');
        }

        $userId = (int) $user->pk_user_id;
        $session = session();
        $session->set([
            'user_id'    => $userId,
            'user_name'  => $user->user_name,
            'user_type'  => $user->user_type,
            'login_time' => time(),
        ]);
        $session->regenerate(true);

        $this->logActivity('success', $user->user_name . ' login success', $userId);

        return $this->plain('success');
    }
}
