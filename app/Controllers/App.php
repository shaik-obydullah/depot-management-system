<?php

namespace App\Controllers;

class App extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('Home', 'app/home', [
            'statics' => $this->appModel()->getStatics(),
        ]);
    }

    public function logout()
    {
        $userId   = (int) session()->get('user_id');
        $userName = (string) session()->get('user_name');

        $this->logActivity('success', $userName . ' logout success', $userId);

        session()->destroy();

        return redirect()->to('/login');
    }
}
