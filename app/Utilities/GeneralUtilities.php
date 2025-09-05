<?php

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;

if (!function_exists('CurrentTime')) {
    function CurrentTime(): string
    {
        return now()->format('Y-m-d H:i:s');
    }
}

if (!function_exists('CurrentDate')) {
    function CurrentDate(): string
    {
        return now()->format('Y-m-d');
    }
}

if (!function_exists('DayStartEndTimestamps')) {
    function DayStartEndTimestamps(DateTime $date): Collection
    {
        $start_timestamp = $date->format('Y-m-d') . ' 00:00:00';
        $end_timestamp = $date->format('Y-m-d') . ' 23:59:59';

        return collect(['start_timestamp' => $start_timestamp, 'end_timestamp' => $end_timestamp]);
    }
}

if (!function_exists('MonthStartEndDates')) {
    function MonthStartEndDates(DateTime $date): Collection
    {
        $month_end_dates = [
            "01" => "-31",
            "02" => "-28",
            "03" => "-31",
            "04" => "-30",
            "05" => "-31",
            "06" => "-30",
            "07" => "-31",
            "08" => "-31",
            "09" => "-30",
            "10" => "-31",
            "11" => "-30",
            "12" => "-31",
        ];
        $year = intval($date->format('Y'));
        if ((0 == $year % 4) & (0 != $year % 100) | (0 == $year % 400)) {
            $month_end_dates["02"] = "-29";
        }

        $month_start_date = $date->format('Y-m') . '-01';
        $month_end_date = $date->format('Y-m') . $month_end_dates[$date->format('m')];

        return collect(['start_date' => $month_start_date, 'end_date' => $month_end_date]);
    }
}

if (!function_exists('UploadFileToServer')) {
    function UploadFileToServer(Request $request, string $file_name, string $path): array
    {
        try {
            $file = $request->file($file_name);
            $file_path = $file->store($path, 'public');
            $file_url = Storage::url($file_path);

            return [
                "file_path" => $file_path,
                "file_url" => $file_url,
            ];
        } catch (Exception $e) {
            return [
                "file_path" => null,
                "file_url" => null,
                "error" => $e->getMessage(),
            ];
        }
    }
}

if (!function_exists('DeleteFileFromServer')) {
    function DeleteFileFromServer(?string $file_path): bool
    {
        if ($file_path != null) {
            if (Storage::disk('public')->exists($file_path)) {
                Storage::disk('public')->delete($file_path);

                return true;
            }
        }
        return false;
    }
}

if (!function_exists('SendSMSVerificationCode')) {
    function SendSMSVerificationCode(string $phone_number, string $verification_code): ResponseInterface
    {
        $app_name = Config::get('app.name');
        $message = 'Your+verification+code+for+' . $app_name . '+is+' . $verification_code;
        $SMSPohKey = Config::get('services.smspoh');
        $client = new Client();
        $url = 'https://smspoh.com/api/http/send?key=' . $SMSPohKey . '&message=' . $message . '&recipients=' . $phone_number . '&sender=' . 'SMSPoh';
        $response = $client->get($url);

        return $response;
    }
}

if (!function_exists('SendApprovalSMS')) {
    function SendApprovalSMS(string $phone_number, string $additional_message): ResponseInterface
    {
        $app_name = Config::get('app.name');
        $message = 'Your+registration+is+approved+by+' . $app_name . $additional_message;
        $SMSPohKey = Config::get('services.smspoh');
        $client = new Client();
        $url = 'https://smspoh.com/api/http/send?key=' . $SMSPohKey . '&message=' . $message . '&recipients=' . $phone_number . '&sender=' . $app_name;
        $response = $client->get($url);

        return $response;
    }
}

if (!function_exists('Pagination')) {
    function Pagination(Collection $data, Request $request, ?string $data_shell_name = null): array
    {
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // CreateOrUpdate a new Laravel collection from the array data
        $itemCollection = $data;
        // $itemCollection = collect($data);

        // Define how many items we want to be visible in each page
        isset($request->per_page) ? $perPage = $request->per_page : $perPage = 20;

        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->values();
        $currentPageItems = $currentPageItems->all();

        // CreateOrUpdate our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        $fullUrl = $request->fullUrl();
        $parsedUrl = parse_url($fullUrl);
        if (array_key_exists('query', $parsedUrl)) {
            parse_str($parsedUrl['query'], $query);
            unset($query['page']);
            $newQueryString = http_build_query($query);
            $newFullUrl = url($parsedUrl['path']) . ($newQueryString ? '?' . $newQueryString : '');
        } else {
            $newFullUrl = $fullUrl;
        }

        // set url path for generated links
        // $url_paths = $paginatedItems->setPath($request->url());
        $url_paths = $paginatedItems->setPath($newFullUrl);

        $last_page = $paginatedItems->lastPage();

        $data_shell_name = ($data_shell_name) ? $data_shell_name : 'data';

        return [
            'next_page_url' => $paginatedItems->nextPageUrl(),
            'previous_page_url' => $paginatedItems->previousPageUrl(),
            'first_page_url' => $paginatedItems->url(1),
            'last_page_url' => $paginatedItems->url($last_page),
            'total' => $paginatedItems->total(),
            'total_pages' => ceil($paginatedItems->total() / $paginatedItems->perPage()),
            'current_page' => $currentPage,
            'last_page' => $paginatedItems->lastPage(),
            'per_page' => $paginatedItems->perPage(),
            $data_shell_name => $paginatedItems->items(),
        ];
    }
}

if (!function_exists('MakePaginationData')) {
    function MakePaginationData(Request $request, ?int $totalCount, $data = null, ?string $data_shell_name = null)
    {
        $pageNumber = 1;
        $perPage = 20;
        if ($request->page) {
            $pageNumber = $request->page;
        }
        if ($request->per_page) {
            $perPage = $request->per_page;
        }

        $fullUrl = $request->fullUrl();
        $parsedUrl = parse_url($fullUrl);
        if (array_key_exists('query', $parsedUrl)) {
            parse_str($parsedUrl['query'], $query);
            unset($query['page']);
            $newQueryString = http_build_query($query);
            $newFullUrl = url($parsedUrl['path']) . ($newQueryString ? '?' . $newQueryString : '');
        } else {
            $newFullUrl = $fullUrl;
        }

        $totalPages = ceil($totalCount / $perPage);
        $previousPage = $pageNumber - 1;
        $currentPage = $pageNumber;
        $nextPage = $pageNumber + 1;
        $firstPage = 1;
        $lastPage = $totalPages;

        $nextPageUrl = "{$newFullUrl}&page={$nextPage}";
        if ($currentPage == $lastPage) {
            $nextPageUrl = null;
        }
        $previousPageUrl = null;
        if ($previousPage >= 1) {
            $previousPageUrl = "{$newFullUrl}&page={$previousPage}";
        }
        $firstPageUrl = "{$newFullUrl}&page={$firstPage}";
        $lastPageUrl = "{$newFullUrl}&page={$lastPage}";

        $data_shell_name = ($data_shell_name) ? $data_shell_name : 'data';

        return [
            "next_page_url" => $nextPageUrl,
            "previous_page_url" => $previousPageUrl,
            "first_page_url" => $firstPageUrl,
            "last_page_url" => $lastPageUrl,
            "total_pages" => (int) $totalPages,
            "current_page" => (int) $currentPage,
            "last_page" => (int) $lastPage,
            "per_page" => (int) $perPage,
            $data_shell_name => ($data) ? $data : [],
        ];
    }
}

if (!function_exists('RemoveNullValues')) {
    function RemoveNullValues(array $arrayWithNullValues): array
    {
        $cleanedArray = array_filter($arrayWithNullValues, fn($value) => !is_null($value) && $value !== '');

        return $cleanedArray;
    }
}

if (!function_exists('UserData')) {
    function UserData()
    {
        //return  auth('api')->user();
        return auth('sanctum')->user();
    }
}

if (!function_exists('Model')) {
    function Model($model)
    {
        return Relation::getMorphedModel($model);
    }
}

if (!function_exists('RelationMorphName')) {
    function RelationMorphName($model)
    {
        return array_search(get_class($model), Relation::morphMap());
    }
}


if (!function_exists('UnsetData')) {
    function UnsetData($data, $attributes)
    {
        foreach ($attributes as $attribute) {
            unset($data[$attribute]);
        }
        return $data;
    }
}

if (!function_exists('convertDateFormat')) {
    function convertDateFormat($data)
    {
        return Carbon::parse($data)->format('Y-m-d');
    }
}


if (!function_exists('toggleColumn')) {
    function toggleColumn($model, $id, $column)
    {
        $record = $model::find($id);
        if ($record) {
            $record->$column = !$record->$column;
            $record->save();
            return true;
        }
        return false;
    }
}
