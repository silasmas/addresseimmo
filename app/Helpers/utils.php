<?php

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

// Get web URL
if (!function_exists('getWebURL')) {
    function getWebURL()
    {
        return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
        // return 'http://192.168.29.238/boongo/public';
    }
}

// Get APIs URL
if (!function_exists('getApiURL')) {
    function getApiURL()
    {
        return (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/api';
        // return 'http://192.168.29.238/boongo/public/api';
    }
}

// Get API toke
if (!function_exists('getApiToken')) {
    function getApiToken()
    {
        return '';
    }
}

// Check if file is photo
if (!function_exists('isPhotoFile')) {
    function isPhotoFile($url)
    {
        // Extract file extension
        $pathInfo = pathinfo($url);
        $extension = strtolower($pathInfo['extension'] ?? '');

        // List of recognized photo extensions
        $photoExtensions = ['jpg', 'jpeg', 'png', 'webp', 'heic', 'bmp', 'tiff', 'tif', 'raw', 'cr2', 'nef', 'arw'];

        // Check if the extension is in the list
        return in_array($extension, $photoExtensions);
    }
}

// Check if file is video
if (!function_exists('isVideoFile')) {
    function isVideoFile($url)
    {
        // Extract file extension
        $pathInfo = pathinfo($url);
        $extension = strtolower($pathInfo['extension'] ?? '');

        // List of recognized video extensions
        $videoExtensions = ['mp4', 'mkv', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mpeg'];

        // Check if the extension is in the list
        return in_array($extension, $videoExtensions);
    }
}

// Check if a value exists into an multidimensional array
if (!function_exists('inArrayR')) {
    function inArrayR($needle, $haystack, $key)
    {
        return in_array($needle, collect($haystack)->pluck($key)->toArray());
    }
}

// Get array of columns from a keys/values object
if (!function_exists('getArrayKeys')) {
    function getArrayKeys($haystack, $ref)
    {
        return collect($haystack)->pluck($ref)->toArray();
    }
}

// Month fully readable
if (!function_exists('explicitMonth')) {
    function explicitMonth($month)
    {
        setlocale(LC_ALL, app()->getLocale());

        return utf8_encode(strftime("%B", strtotime(date('F', mktime(0, 0, 0, $month, 10)))));
    }
}

// Day/Month readable
if (!function_exists('explicitDayMonth')) {
    function explicitDayMonth($date)
    {
        setlocale(LC_ALL, app()->getLocale());

        return utf8_encode(Carbon::parse($date)->formatLocalized('%A %d %B'));
    }
}

// Date fully readable
if (!function_exists('explicitDate')) {
    function explicitDate($date)
    {
        setlocale(LC_ALL, app()->getLocale());

        return utf8_encode(Carbon::parse($date)->formatLocalized('%A %d %B %Y'));
    }
}

// Delete item from exploded array
if (!function_exists('deleteExplodedArrayItem')) {
    function deleteExplodedArrayItem($separator, $subject, $item)
    {
        $explodes = explode($separator, $subject);
        $clean_inventory = array();

        foreach ($explodes as $explode) {
            if (!isset($clean_inventory[$explode])) {
                $clean_inventory[$explode] = 0;
            }

            $clean_inventory[$explode]++;
        }

        // Item can be deleted
        unset($clean_inventory[$item]);

        $saved = array();

        foreach ($clean_inventory as $key => $quantity) {
            $saved = array_merge($saved, array_fill(0, $quantity, $key));
        }

        return implode($separator, $saved);
    }
}

// Add an item to exploded array
if (!function_exists('addItemsToExplodedArray')) {
    function addItemsToExplodedArray($separator, $subject, $items)
    {
        $explodes = explode($separator, $subject);
        $saved = array_merge($explodes, $items);

        return implode($separator, $saved);
    }
}

// Add an item to exploded array
if (!function_exists('getExchangeRate')) {
    function getExchangeRate($baseCurrency, $targetCurrency)
    {
        $apiKey = config('services.exchangerate.key');
        // ExchangeRate API URL
        // $url = 'https://v6.exchangerate-api.com/v6/' . $apiKey . '/pair/'. $baseCurrency . '/' . $targetCurrency;
        $url = "https://v6.exchangerate-api.com/v6/{$apiKey}/pair/{$baseCurrency}/{$targetCurrency}";

        // Create a Guzzle client
        $client = new Client();

        // Perform the GET request
        $response = $client->get($url);

        // Decode the JSON response
        $data = json_decode($response->getBody()->getContents(), true);

        // Check if the answer is valid
        if ($data['result'] === 'success') {
            return $data['conversion_rate'];
        }

        // If the answer is invalid or there is an error
        throw new \Exception('Erreur lors de la récupération du taux de change');
    }
}

// Add an item to exploded array
if (!function_exists('showCountries')) {
    function showCountries()
    {
        $response = Http::get('https://restcountries.com/v3.1/all?fields=cca2,idd,flags,name');

        if (!$response->successful()) {
            return [];
        }

        $countriesRaw = $response->json();
        $phoneCodes = [];

        return collect($countriesRaw)
                ->map(function ($country) use (&$phoneCodes) {
                    $root = $country['idd']['root'] ?? '';
                    $suffix = $country['idd']['suffixes'][0] ?? '';
                    $fullPhoneCode = $root . $suffix;

                    if (empty($fullPhoneCode) || in_array($fullPhoneCode, $phoneCodes)) {
                        return null;
                    }

                    $phoneCodes[] = $fullPhoneCode;

                    return [
                        'value' => $fullPhoneCode,
                        'name' => $country['name']['common'] ?? '',
                        'code' => $country['cca2'] ?? '',
                        'phone' => $fullPhoneCode,
                        'flag' => $country['flags']['png'] ?? '',
                        'label' => ($country['name']['common'] ?? '') . ' (' . ($country['cca2'] ?? '') . ') (' . $fullPhoneCode . ')',
                    ];
                })
                ->filter()
                ->sortBy('label')
                ->values();

        return abort(500, 'Erreur lors du chargement des pays');
    }
}
