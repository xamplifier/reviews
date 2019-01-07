<?php
declare(strict_types = 1);

namespace Xamplifier\Reviews;

use \DateTimeInterface;
use Xamplifier\Reviews\EndPoint;
use Facebook\Facebook as FacebookSdk;

class Facebook extends Base
{
    public function __construct(array $config)
    {
        parent::__construct();

        $this->setLibrary(new FacebookSdk([
          'app_id' => $config['appId'],
          'app_secret' => $config['appSecret']
        ]));
    }

    public function getReviews(EndPoint $e)
    {
        try {
            $url = sprintf('/%s/?fields=ratings', $e->siteid);
            $response = $this->library->get(
                $url,
                $e->token
            );
            $data = $response->getGraphNode();

        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $this->format($data);
    }

    /**
     * Formats data
     * - Data object to string
     *
     * @param  Collection $data
     * @return array
     */
    private function format($data) :array
    {
        $reviews = $data->asArray()['ratings'] ?? [];
        foreach ($reviews as &$r) {
            $r['created_time'] = $r['created_time']->format(DateTimeInterface::ISO8601);
        }

        return $reviews;
    }
}
