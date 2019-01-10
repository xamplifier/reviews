<?php
declare(strict_types = 1);

namespace Xamplifier\Reviews;

use \DateTimeInterface;
use Xamplifier\Reviews\EndPoint;
use Illuminate\Support\Arr;
use Facebook\Facebook as FacebookSdk;

class Facebook extends Base
{
    public function __construct($config)
    {
        parent::__construct();

        $this->setLibrary(new FacebookSdk([
          'app_id' => $config['appId'],
          'app_secret' => $config['appSecret']
        ]));
    }


    /**
     *  Gets facebook rating data
     *  GraphEdge object
     *
     * @param  Array $e the endpoints
     * @return Object $data the GraphEdge object
     */
    public function getReviews(EndPoint $e)
    {
        //get the graph edge rating data
        $fields = 'open_graph_story,created_time,has_rating,has_review,rating,recommendation_type,review_text,reviewer';

        try {
            $url = sprintf('/%s/ratings?fields=%s', $e->siteid, $fields);
            $response = $this->library->get(
                $url,
                $e->token
            );

            $data = $response->getGraphEdge();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        //get the page from all the pages if there are.
        $totalData = $this->paginate($data);

        //format the data to be processed by the etl package.
        return $this->format($totalData);
    }


    /**
     * Formats data
     * - Data object to string
     * - Reviewe name
     *
     * @param  Collection $data
     * @return array
     */
    private function format($data) :array
    {
        $formattedData = [];
        foreach ($data as $key => $value) {
            $formattedData[] = [
              'rating' => $value['rating'],
              'has_review' => $value['has_review'],
              'reviewer' => $value['reviewer']['name'],
              'recommendation_type' => $value['recommendation_type'],
              'review_text' => $value['review_text'],
              'language' => $value['open_graph_story']['language'],
              'reviewDate' => $value['created_time']->format(\DateTimeInterface::ISO8601)
          ];
        }

        return $formattedData;
    }


    /**
     * Paginates facebook data
     *
     * @param  Object facebook $data
     * @return array
     */
    public function paginate($data):array
    {
        $totalData = [];

        if ($this->library->next($data)) {
            $reviewsArray = $data->asArray();
            $totalReviews = array_merge($totalData, $reviewsArray);
            while ($data = $this->library->next($data)) {
                $reviewsArray = $data->asArray();
                $totalReviews = array_merge($totalData, $reviewsArray);
            }
        } else {
            $reviewsArray = $data->asArray();
            $totalData = array_merge($totalData, $reviewsArray);
        }

        return $totalData;
    }
}
