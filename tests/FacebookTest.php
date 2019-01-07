<?php
namespace Xamplifier\Reviews\Tests;

use PHPUnit\Framework\TestCase;
use Xamplifier\Reviews\EndPoint;
use Xamplifier\Reviews\Facebook as FacebookApi;

class FacebookTest extends TestCase
{

    public function testCanInstantiateFacebookObj()
    {
        $e = new EndPoint([
            'token' => 'EAAHXTZCLCmYsBABQQkYLCCrVXPoav6Y5ZBKERFhRFyEymyxPzW8xt09U9O65exxxcJTbQIOCrQYkLkWd9HEtuaGPnp14V2AllHhvZAvAJDpDSdVLiHeTdtzjcg9XoOSk85fdfbqzbqFZBGrAzzefbB9OT7pDHDJ2q4l0JYYb3q1NjnrXEyH3OdBpUCTAY4AZD',
            // 'name' => 'Vein Clinics of America - Baltimore',
            'siteid' => '184680898972315',
            // 'id' => '59c3a67c1682fa6cd5d507e1',
        ]);

        $fbApi = new FacebookApi();

        $result = $fbApi->getReviews($e);
        dd($result);
    }
}
