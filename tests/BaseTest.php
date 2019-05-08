<?php
namespace Xamplifier\Reviews\Tests;

use Xamplifier\Reviews\Base;
use PHPUnit\Framework\TestCase;
use Xamplifier\Reviews\EndPoint;
use Xamplifier\Reviews\Facebook as FacebookApi;

class ReviewSite extends Base
{
    public function getReviews($works)
    {
        if ($works === false) {
            $this->setErrorMsg('Oops, service unavailable');

            return null;
        }

        return [];
    }
}

class ReviewSiteTest extends TestCase
{
    public function testGetReviews()
    {
        $reviewSiteObj = new ReviewSite([]);

        $apiCallWorked = true;
        $this->assertTrue(is_array($reviewSiteObj->getReviews($apiCallWorked)));

        $apiCallWorked = false;
        $this->assertTrue(is_null($reviewSiteObj->getReviews($apiCallWorked)));
    }

    public function testErrorMsgIsGivenWhenApiCallFailed()
    {
        $apiCallWorked = true;

        $reviewSiteObj = new ReviewSite([]);

        $apiCallWorked = true;
        $reviewSiteObj->getReviews($apiCallWorked);
        $this->assertNull($reviewSiteObj->getErrorMsg());

        $apiCallWorked = false;
        $reviewSiteObj->getReviews($apiCallWorked);
        $this->assertTrue(is_string($reviewSiteObj->getErrorMsg()));
    }
}
