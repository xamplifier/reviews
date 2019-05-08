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

    public function getReviews(EndPoint $e) :?array
    {
        try {
            $fields = 'created_time,rating,recommendation_type,review_text,reviewer';
            $url = sprintf('/%s/ratings?fields=%s', $e->siteid, $fields);
            $response = $this->library->get(
                $url,
                $e->token
            );
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            $this->setErrorMsg('Graph returned an error: ' . $e->getMessage());

            return null;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            $this->setErrorMsg('Facebook SDK returned an error: ' . $e->getMessage());

            return null;
        }
        $reviews = $response->getGraphEdge();

        $totalReviews = [];

        if ($this->library->next($reviews)) {
            $reviewsArray = $reviews->asArray();
            $totalReviews = array_merge($totalReviews, $reviewsArray);
            while ($reviews = $this->library->next($reviews)) {
                $reviewsArray = $reviews->asArray();
                $totalReviews = array_merge($totalReviews, $reviewsArray);
            }
        } else {
            $reviewsArray = $reviews->asArray();
            $totalReviews = array_merge($totalReviews, $reviewsArray);
        }

        return $this->format($totalReviews);
    }

    /**
     * Formats data
     * - Data object to string
     * - Reviewe name
     *
     * @param  Collection $data
     * @return array
     */
    private function format($reviews) :array
    {
        foreach ($reviews as &$value) {
            $value['reviewer'] = $value['reviewer']['name'];
            $value['review_text'] = $value['review_text'] ?? null;
            $value['created_time'] = $value['created_time']->format(\DateTimeInterface::ISO8601);
        }

        return $reviews;
    }
}
