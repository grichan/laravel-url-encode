<?php

namespace Tests\Unit;

use App\Models\Urls;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UrlsModelTest extends TestCase
{
    use DatabaseTransactions;

     /**
     * Test createUrl function
     */
    #[Test]
    public function itCanCreateAUrlMapping()
    {
        $url = Urls::createUrl('https://short.est/testAll1', 'https://short.est/testAll2');

        $this->assertDatabaseHas('urls', [
            'decodedUrl' => 'https://short.est/testAll1',
            'encodedUrl' => 'https://short.est/testAll2',
        ]);
    }

    /**
     * Test retrieveUrls function
     */
    #[Test]
    public function itCanRetrieveAllUrlMappings()
    {
        if (!Urls::where('decodedUrl', 'https://short.est/testAllDecoded1')->exists()) {
            Urls::createUrl('https://short.est/testAllDecoded1', 'https://short.est/testAllEncoded2');
        }
        if (!Urls::where('decodedUrl', 'https://short.est/testAllDecoded2')->exists()) {
            Urls::createUrl('https://short.est/testAllDecoded2', 'https://short.est/testAllEmcoded2');
        }

        $urls = Urls::retrieveUrls();

        $this->assertGreaterThanOrEqual(2, $urls->count());
    }

    /**
     * Test retrieveByEncodedUrl function
     */
    #[Test]
    public function itCanRetrieveAUrlMappingByEncodedUrl()
    {
        if (!Urls::where('encodedUrl', 'https://short.est/retrieveByEncodedUrl1')->exists()) {
            Urls::createUrl('https://short.est/retrieveByEncodedUrl1', 'https://short.est/retrieveByEncodedUrl2');
        }

        $url = Urls::retrieveByEncodedUrl('https://short.est/retrieveByEncodedUrl2');

        $this->assertNotNull($url);
        $this->assertEquals('https://short.est/retrieveByEncodedUrl1', $url->decodedUrl);
    }
}
