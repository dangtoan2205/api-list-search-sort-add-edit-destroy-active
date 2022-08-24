<?php

namespace App\Providers;

use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    const ENV_PRODUCTION = 'production';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory)
    {
        try {
            $env = config('app.env') === 'production' ? 'production' : 'local';
            $factory->macro('success', function ($data = null) use ($factory) {
                $format = [
                    'status' => true,
                    'code' => 200,
                    'message' => 'The request was successful',
                    'timestamp' => time(),
                    'timezone' => config('app.timezone'),
                ];

                if ($data instanceof AnonymousResourceCollection) {
                    $data = $data->response()->getData(true);
                } else if ($data instanceof LengthAwarePaginator) {
                    $data['data'] = $data->items();
                } else if ($data instanceof ResourceCollection) {
                    $data['data'] = $data->items();
                }

                if (isset($data['data'])) {
                    $format['result'] = $data;
                } else {
                    $format['result']['data'] = $data;
                }
                return $factory->make($format, 200);
            });

            $factory->macro('failure', function (string $message = '', $code = '', $status = 400) use ($factory, $env) {
                if ($env == 'production') {
                    $format = [
                        'status' => false,
                        'code' => $code,
                        'message' => 'There is an error occurred. Please contact support for further assistance.',
                        'timestamp' => time(),
                        'timezone' => config('app.timezone'),
                        'result' => null,
                    ];
                } else {
                    $format = [
                        'status' => false,
                        'code' => $code,
                        'message' => $message,
                        'timestamp' => time(),
                        'timezone' => config('app.timezone'),
                        'result' => null,
                    ];
                }

                return $factory->make($format, $status);
            });
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            $status = 500;
            $format = [
                'status' => false,
                'code' => $status,
                'message' => 'There is an error occurred. Please contact support for further assistance.',
                'timestamp' => time(),
                'timezone' => config('app.timezone'),
                'result' => null,
            ];

            return $factory->make($format, $status);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
