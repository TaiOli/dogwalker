<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Controllers\Controller;
use App\Repositories\Services\Contracts\LogServiceInterface;
use App\Support\PaginationDefaults;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LogViewerController extends Controller
{
   public function __construct(
        protected LogServiceInterface $logService
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Log::class);
 
        $perPage = min(
            (int) $request->input('per_page', PaginationDefaults::DEFAULT_PER_PAGE),
            PaginationDefaults::MAX_PER_PAGE,
        );

          $filters = [
            'action' => $request->input('action'),
            'user' => $request->input('user'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'per_page' => $perPage,
        ];

        return Inertia::render('logs/Index',[
            'logs' => $this->logService->getPaginatedLogs($request->user(), $filters, $perPage),
            'filters' => $filters
        ]);
    }
}
