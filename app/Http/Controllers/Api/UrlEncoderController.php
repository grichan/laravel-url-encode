<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Urls;

class UrlEncoderController extends Controller
{

    private $shortUrl = 'https://short.est/';

    /**
     * Retrieve all stored urls
     *
     * @return array
     */
    public function index()
    {
        $allUrls = [];
        $urls = Urls::all();

        foreach ($urls as $url) {
            $allUrls[$url->id] = ['decodedUrl' => $url->decodedUrl, 'encodedUrl' => $url->encodedUrl];
        }

        return response()->json($allUrls);
    }

    /**
     * Encodes url to a shortened form
     *
     * @queryParam url string required
     *
     * @return array
     */
    public function encode(Request $request)
    {
        $code = 400;
        $returnArray = ['message' => 'Colud not encode url.'];
        $queryParams = $request->query();
        $decodedUrl = $request->query('url');

        if (empty($decodedUrl)) {
            $code = 400;
            $returnArray = [
                'message' => 'The url query parameter is required.'
            ];
        }

        if (!empty($decodedUrl)) {
            // Validate url
            $validator = Validator::make($request->all(), [
                'url' => 'required|url',
            ]);

            if ($validator->fails()) {
                $code = 422;
                $returnArray = [
                    'message' => 'Invalid url provided.',
                    'errors' => $validator->errors(),
                ];
            } else {
                // Generate url
                $encodedUrl = $this->shortUrl . Urls::generateUniqueSlug(6);

                $url = Urls::createUrl($decodedUrl, $encodedUrl);

                $code = 200;
                $returnArray = [
                    'url' => $url->encodedUrl,
                    'message' => 'Url encoded.'
                ];
            }
        }

        return response()->json($returnArray, $code);
    }

    /**
     * Decodes url shortened url to longer form
     *
     * @queryParam url string required
     *
     * @return array
     */
    public function decode(Request $request)
    {
        $code = 400;
        $returnArray = ['message' => 'Could not decode url.'];
        $queryParams = $request->query();
        $encodedUrl = $request->query('url');

        if (empty($encodedUrl)) {
            $code = 400;
            $returnArray = [
                'message' => 'The url query parameter is required.'
            ];
        }

        if (!empty($encodedUrl)) {
            // Validate url
            $validator = Validator::make($request->all(), [
                'url' => 'required|url',
            ]);

            if ($validator->fails()) {
                $code = 422;
                $returnArray = [
                    'errors' => $validator->errors(),
                    'message' => 'The url field is required.'
                ];
            } else {
                // Generate new url
                $url = Urls::retrieveByEncodedUrl($encodedUrl);

                if ($url) {
                    $code = 200;
                    $returnArray = [
                        'url' => $url->decodedUrl,
                        'message' => 'Url retrieved.'
                    ];
                }
            }

        }

        return response()->json($returnArray, $code);
    }

}
