<?php

namespace Tests\Unit;

use App\Models\Urls;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UrlsUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test generateUniqueSlug function
     */
    #[Test]
    public function itGeneratesAUniqueSlug()
    {
        $slug1 = Urls::generateUniqueSlug(6);
        $slug2 = Urls::generateUniqueSlug(10);

        $this->assertNotEquals($slug1, $slug2);
        $this->assertEquals(6, strlen($slug1));
        $this->assertEquals(10, strlen($slug2));
    }
}
