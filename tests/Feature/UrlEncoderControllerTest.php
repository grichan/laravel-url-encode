<?php

namespace Tests\Feature;

use App\Models\Urls;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UrlEncoderControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Index method
     */
    public function testIndexEndpoint()
    {
        // Seed records
        $url1 = Urls::firstOrCreate([
            'decodedUrl' => 'https://short.est/indexTest1',
            'encodedUrl' => Urls::generateUniqueSlug(6),
        ]);

        $url2 = Urls::firstOrCreate([
            'decodedUrl' => 'https://short.est/indexTest2',
            'encodedUrl' => Urls::generateUniqueSlug(6),
        ]);

        $response = $this->get('/api/');

        $response->assertStatus(200);

        $responseJson = $response->json();

        // Assert response
        $this->assertArrayHasKey($url1->id, $responseJson);
        $this->assertArrayHasKey('decodedUrl', $responseJson[$url1->id]);
        $this->assertArrayHasKey('encodedUrl', $responseJson[$url1->id]);

        $this->assertArrayHasKey($url2->id, $responseJson);
        $this->assertArrayHasKey('decodedUrl', $responseJson[$url2->id]);
        $this->assertArrayHasKey('encodedUrl', $responseJson[$url2->id]);

        $this->assertEquals($url1->decodedUrl, $responseJson[$url1->id]['decodedUrl']);
        $this->assertEquals($url1->encodedUrl, $responseJson[$url1->id]['encodedUrl']);

        $this->assertEquals($url2->decodedUrl, $responseJson[$url2->id]['decodedUrl']);
        $this->assertEquals($url2->encodedUrl, $responseJson[$url2->id]['encodedUrl']);

    }

    /**
     * Test encode result
     */
    public function testEncodeEndpoint()
    {
        $urls = [ 'https://short.est/encodeTest1', 'https://short.est/encodeTest2'];

        foreach ($urls as $url) {
            $response = $this->get('/api/encode?url=' . $url);

            $response->assertStatus(200);

            $response->assertJson([
                'message' => 'Url encoded.',
                'url' => true
            ]);
        }

        $responseJson = $response->json();

        $this->assertStringStartsWith('https://short.est/', $responseJson['url']);

        $shortenedUrl = substr($responseJson['url'], strlen('https://short.est/'));
        $this->assertEquals(6, strlen($shortenedUrl));
    }

    /**
     * Test encode invalid url
     */
    public function testEncodeInvalidUrl()
    {
        $response = $this->get('/api/encode');

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'The url query parameter is required.'
        ]);

        $response = $this->get('/api/encode?url=invalid-url');

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'Invalid url provided.'
        ]);
    }

    /**
     * Test decode result
     */
    public function testDecodeEndpoint()
    {
        $url = Urls::firstOrCreate([
            'decodedUrl' => 'https://short.est/decodeTest1',
            'encodedUrl' => 'https://short.est/decodeTest2',
        ]);

        $response = $this->get('/api/decode?url=' . $url->encodedUrl);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Url retrieved.',
            'url' => $url->decodedUrl
        ]);
    }

    /**
     * Test decode invalid url
     */
    public function testDecodeInvalidUrl()
    {
        $response = $this->get('/api/decode');

        $response->assertStatus(400);

        $response->assertJsonFragment([
            'message' => 'The url query parameter is required.'
        ]);

        $response = $this->get('/api/decode?url=noturl');

        $response->assertJsonFragment([
            'errors' => [
                'url' => ['The url field must be a valid URL.']
            ]
        ]);

        $response = $this->get('/api/decode?url=https://short.est/nonexistentUrl');

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Could not decode url.'
        ]);
    }
}
