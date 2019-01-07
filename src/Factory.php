<?php
declare('strict_types=1');

namespace Xamplifier\Reviews;

use App\Reviews\Api\Google;
use App\Reviews\Api\Facebook;

/**
 * Factory class for determining the type of object.
 * This class utilizes Static Factory Pattern.
 */
final class Factory
{
    /**
     * Determines which object to create based on the given type.
     *
     * @return ApiInterface
     */
    public static function factory(string $site)
    {
        switch (strtolower($site)) {
            case 'facebook':
                return new Facebook;
            // case 'google':
            //     return new Google;
            default:
                throw new \InvalidArgumentException('Unknown API site');
                break;
        }
    }
}
