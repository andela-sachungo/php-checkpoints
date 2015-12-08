<?php
namespace Stacey\Emoji\Test;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Illuminate\Database\Capsule\Manager as Capsule;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    protected $client;

    protected function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../');
        $dotenv->load();

        $uri = getenv('DATABASE_SERVER');

        $this->client = new Client([
            'base_uri' => "http://".$uri
        ]);
    }

    public function testUserIsRegistered()
    {
        $response = $this->client->request('POST', '/register', [
            'json' => [
                'name'      => 'Ivy Langley',
                'username'  => 'ivy',
                'password'  => 'pompom'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertInternalType('array', $data);
    }

    /**
     * @depends testUserIsRegistered
     */
    public function testUserIsLoggedIn()
    {
        $response = $this->client->request('POST', '/auth/login', [
            'json' => [
                'username'  => 'ivy',
                'password'  => 'pompom'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $tokenString = $data->token;
        $this->assertInternalType('object', $data);

        return $tokenString;
    }

    /**
     * @depends testUserIsLoggedIn
     */
    public function testEmojiIsCreated($tokenString)
    {
        $response = $this->client->request('POST', '/emojis', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ],
            'json' => [
                'name'  => 'salsa',
                'symbol'  => '~$~$~',
                'category' => 'dance'
            ]
        ]);
        $response = $this->client->request('POST', '/emojis', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ],
            'json' => [
                'name'  => 'Zaria',
                'symbol'  => '^#^#^',
                'category' => 'peopel'
            ]
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertInternalType('object', $data);
    }

     /**
     * @depends testEmojiIsCreated
     */
    public function testEmojisAreRetrieved()
    {
        $response = $this->client->request('GET', '/emojis');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertInternalType('array', $data);
    }

    /**
     * @depends testEmojiIsCreated
     */
    public function testEmojiRetrievedById()
    {
        $response = $this->client->request('GET', '/emojis/1');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $this->assertInternalType('array', $data);
    }

    /**
     * @depends testUserIsLoggedIn
     */
    public function testKeywordIsAddedToEmoji($tokenString)
    {
        $response = $this->client->request('POST', '/emojis/1', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ],
            'json' => [
                'keyword'  => 'twisty'
            ]
        ]);
        $response = $this->client->request('POST', '/emojis/1', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ],
            'json' => [
                'keyword' => 'twirly'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertInternalType('object', $data);
    }

    /**
     * @depends testUserIsLoggedIn
     */
    public function testEmojiCanBeUpdated($tokenString)
    {
        $response = $this->client->request('PUT', '/emojis/1', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ],
            'json' => [
                'name'  => 'rhumba',
                'symbol'  => '~^~^'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertInternalType('object', $data);
    }

    /**
     * @depends testUserIsLoggedIn
     */
    public function testEmojiCanBePartiallyUpdated($tokenString)
    {
        $response = $this->client->request('PATCH', '/emojis/1', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ],
            'json' => [
                'name'  => 'lingala'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertInternalType('object', $data);
    }

    /**
     * @depends testUserIsLoggedIn
     */
    public function testEmojiCanBeDeleted($tokenString)
    {
        $response = $this->client->request('DELETE', '/emojis/1', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertInternalType('object', $data);
    }

    /**
     * @depends testUserIsLoggedIn
     */
    public function testUserIsLoggedOut($tokenString)
    {
        $response = $this->client->request('POST', '/auth/logout', [
            'headers' => [
                'Authorization' => 'Bearer '. $tokenString
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody());
        $this->assertInternalType('object', $data);
    }

    public static function tearDownAfterClass()
    {
        Capsule::schema()->dropIfExists('emoji_keywords');
        Capsule::schema()->dropIfExists('emojis');
        Capsule::schema()->dropIfExists('users');
    }
}
