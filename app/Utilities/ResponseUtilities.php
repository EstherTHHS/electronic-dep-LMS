<?php

use Illuminate\Support\Facades\Config;

if (!function_exists('MakeCommonHeaders')) {
    function MakeCommonHeaders(): array
    {
        return [
            "Cache-Control" => "public," . "max-age=" . Config::get('cache.duration_cacheable_response'),
            "Access-Control-Allow-Origin" => "*",
            "Date" => now()->format('D, d M Y H:m:s') . ' ' . Config::get('app.timezone_code')
        ];
    }
}

if (!function_exists('ResponseView')) {
    function ResponseView(string $view, array $context_data = [], int $status_code = 200, ?array $extra_headers = null)
    {
        $common_headers = MakeCommonHeaders();
        if (isset($extra_headers)) {
            $common_headers += $extra_headers;
        }

        return response()->view($view, $context_data, $status_code, $common_headers)->send() && exit(1);
    }
}

if (!function_exists('ResponseCreatedData')) {
    function ResponseCreatedData(mixed $data, ?array $extra_headers = null)
    {
        $common_headers = MakeCommonHeaders();
        if (isset($extra_headers)) {
            $common_headers += $extra_headers;
        }

        return response()->json([
            "success" => true,
            "data" => $data,
        ], 201, $common_headers)->send() && exit(1);
    }
}

if (!function_exists('ResponseData')) {
    function ResponseData(mixed $data, int $status_code = 200, bool $success = true, ?string $extra_message = null, string $name = "data", ?array $extra_headers = null)
    {
        if ($status_code >= 400) {
            $success = false;
        }
        $common_headers = MakeCommonHeaders();
        if (isset($extra_headers)) {
            $common_headers += $extra_headers;
        }

        if (isset($extra_message)) {
            return response()->json([
                "success" => $success,
                "message" => $extra_message,
                $name => $data
            ], $status_code, $common_headers)->send() && exit(1);
        } else {
            return response()->json([
                "success" => $success,
                $name => $data
            ], $status_code, $common_headers)->send() && exit(1);
        }
    }
}

if (!function_exists('ResponseMessage')) {
    function ResponseMessage(mixed $message, int $status_code = 200, bool $success = true, string $name = "message", ?array $extra_headers = null)
    {
        ResponseData($message, $status_code, $success, null, $name, $extra_headers);
    }
}

if (!function_exists('ResponseEmpty')) {
    function ResponseEmpty(int $status_code = 204)
    {
        $common_headers = MakeCommonHeaders();
        return response()->noContent($status_code, $common_headers);
    }
}


// if(!function_exists('ResponseStatus')){
//     function Respons($message,$status){
//         return responseData('message',$message,$status);
//     }
// }
