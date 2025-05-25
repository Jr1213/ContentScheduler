<?php

namespace App\Http\Controllers;

use App\Actions\User\SyncePlatformAction;
use App\Http\Requests\Platfomr\SyncPlatformRequest;
use App\Models\Platform;
use App\Service\PlatformService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlatformController extends Controller
{
    public function __construct(private readonly PlatformService $platformService, private readonly SyncePlatformAction $syncePlatformAction) {}
    public function index()
    {
        try {

            $user = request()->get('active', false);

            $platforms = $this->platformService->list(
                user_id: $user ? request()->user()->id : null
            );

            return $this->response(data: [
                'platforms' => $platforms
            ], status: Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }

    public function store(SyncPlatformRequest $request)
    {
        try {
            $this->syncePlatformAction->handle($request->platforms);

            return $this->response(data: [
                'platforms' => $this->platformService->list(user_id: request()->user()->id)
            ], message: 'Platform synced successfully', status: Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->error($e);
        }
    }
}
