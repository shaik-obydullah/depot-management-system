<?php

namespace App\Filters;

use App\Models\AppModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Keeps the legacy 15-minute inactivity timeout in one place instead of
 * repeating the same session check in every controller.
 */
class AuthFilter implements FilterInterface
{
    private const TIMEOUT_SECONDS = 900;

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId  = $session->get('user_id');

        if ($userId === null) {
            return redirect()->to('/login');
        }

        $loginTime = (int) $session->get('login_time');
        if ($loginTime === 0 || time() - $loginTime > self::TIMEOUT_SECONDS) {
            $userName = (string) $session->get('user_name');

            model(AppModel::class)->saveActivity([
                'activity_type' => 'error',
                'fk_user_id'    => $userId,
                'activity_name' => $userName . ' logout due to inactivity',
                'created_by'    => $userId,
            ]);

            $session->destroy();

            return redirect()->to('/login');
        }

        $session->set('login_time', time());

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
