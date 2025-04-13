<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Urls extends Model
{
    use HasFactory;

    protected $fillable = ['decodedUrl', 'encodedUrl'];

    /**
     * Create a new url record.
     *
     * @param string $decodedUrl
     * @param string $encodedUrl
     * @return Urls
     */
    public static function createUrl($decodedUrl, $encodedUrl)
    {
        return self::create([
            'decodedUrl' => $decodedUrl,
            'encodedUrl' => $encodedUrl,
        ]);
    }

    /**
     * Retrieve all url records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function retrieveUrls()
    {
        return self::all();
    }

    /**
     * Retrieve url by encoded url.
     *
     * @param string $encodedUrl
     * @return Urls|null
     */
    public static function retrieveByEncodedUrl($encodedUrl)
    {
        return self::where('encodedUrl', $encodedUrl)->first();
    }

    /**
     * Generate a unique random string, database check.
     *
     * @return string
     */
    public static function generateUniqueSlug($length)
    {
        do {
            $slug = Str::random($length);
        } while (self::where('slug', $slug)->exists());

        return $slug;
    }

}
