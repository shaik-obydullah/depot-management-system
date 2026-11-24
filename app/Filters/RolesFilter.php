<?php

namespace App\Filters;

use App\Libraries\RolePolicy;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Protects routes that change master data. Allowed roles are supplied as
 * filter arguments, e.g. ['filter' => 'roles:superadmin'].
 */
class RolesFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $allowed = is_array($arguments) && $arguments !== []
            ? $arguments
            : [RolePolicy::MASTER_DATA_ROLE];

        $userType = session()->get('user_type');

        foreach ($allowed as $role) {
            if ($userType === $role) {
                return null;
            }
        }

        if ($request->isAJAX()) {
            return service('response')
                ->setStatusCode(403)
                ->setContentType('text/plain')
                ->setBody('forbidden');
        }

        return redirect()->to('/')->with('error', 'You do not have permission to perform that action.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
