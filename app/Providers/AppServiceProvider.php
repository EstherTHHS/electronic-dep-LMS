<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function (Request $request, $data = [], $message, $code = 200, $startTime, $total = 0) {

            $meta = [
                'method' => $request->getMethod(),
                'endpoint' => $request->path(),
                'limit' => $request->limit ?? 0,
                'offset' => $request->offset ?? 0,
                'total' =>  $total,
            ];

            $responseData = [
                'success' => 1,
                'code' => $code,
                'meta' => $meta,
                'data' => $data,
                'message' => $message,
                'duration' => (float)sprintf("%.3f", (microtime(true) - $startTime)),
            ];

            return Response::json($responseData, $code);
        });

        Response::macro('error', function (Request $request, $data = [], $message, $code = 400, $startTime) {

            $meta = [
                'method' => $request->getMethod(),
                'endpoint' => $request->path(),
                'limit' => $request->limit ?? 0,
                'offset' => $request->offset ?? 0,
            ];

            $responseData = [
                'success' => 0,
                'code' => $code,
                'meta' => $meta,
                'data' => $data,
                'message' => $message,
                'duration' => (float)sprintf("%.3f", (microtime(true) - $startTime)),
            ];

            return Response::json($responseData, $code);
        });

        Response::macro('paginate', function (Request $request, $data = null, $message = null, $code = 200, $startTime) {

            $isPaginated = $request->has('page') ? $request->input('page') : 1;

            if ($isPaginated) {

                $paginator = $data;

                $responseData['data'] = $paginator->items();

                $meta = [
                    'method' => $request->getMethod(),
                    'endpoint' => $request->path(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'limit' => $paginator->perPage(),
                    'total' =>  $paginator->total(),
                ];

                $responseData = [
                    'success' => 1,
                    'code' => $code,
                    'meta' => $meta,
                    'data' => $responseData['data'],
                    'message' => $message,
                    'duration' => (float)sprintf("%.3f", (microtime(true) - $startTime)),
                ];

                return Response::json($responseData, $code);
            }
        });
    }
}
