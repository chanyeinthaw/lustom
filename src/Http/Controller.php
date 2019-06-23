<?php

namespace Lumos\Lustom\Http;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Request
     */
    protected $request;

    public function __invoke(Request $request) {
        $this->request = $request;

        try {
            return $this->___invoke();
        } catch (HttpException $e){
            Log::error($e->getTraceAsString());

            $message = strlen($e->getMessage()) > 0 ? json_decode($e->getMessage()) : null;

            if (!$message) return response()->noContent($e->getStatusCode(), $e->getHeaders());

            return response()->json($message,
                $e->getStatusCode(),
                $e->getHeaders());
        }
    }

    abstract public function ___invoke();
}
