<?php
declare(strict_types = 1);

namespace Xamplifier\Reviews;

class EndPoint
{
    private $properties = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $key = strtolower($key);
            $this->properties[$key] = $value;
        }
    }

    public function __set(string $key, string $value) :void
    {
        $key = strtolower($key);
        $this->properties[$key] = $value;
    }

    public function __get(string $key)
    {
        return $this->properties[$key] ?? null;
    }
}
