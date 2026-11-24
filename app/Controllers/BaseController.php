<?php

namespace App\Controllers;

use App\Models\AppModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Shared helpers for all depot controllers.
 */
abstract class BaseController extends Controller
{
    protected $helpers = ['url'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
    }

    protected function appModel(): AppModel
    {
        return model(AppModel::class);
    }

    /**
     * Write a row to the activity log using the current session user.
     */
    protected function logActivity(string $type, string $name, ?int $userId = null): void
    {
        $this->appModel()->saveActivity([
            'activity_type' => $type,
            'fk_user_id'    => $userId,
            'activity_name' => $name,
            'created_by'    => $userId,
        ]);
    }

    /**
     * Render a page inside the shared master layout.
     */
    protected function renderPage(string $title, string $view, array $data = []): string
    {
        $data['title'] = $title;
        $data['home']  = view($view, $data);

        return view('app/master', $data);
    }
}
