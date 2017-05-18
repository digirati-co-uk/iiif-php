<?php

namespace IIIF\Model;

class Region
{
    use WithMetaData;

    private $unit;
    private $x;
    private $y;
    private $width;
    private $height;

    public function __construct(string $unit, int $x, int $y, int $width, int $height)
    {
        $this->unit = $unit;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    const W3C_REGEX = '/[#&\?]xywh\=(pixel\:|percent\:)?(\d+),(\d+),(\d+),(\d+)/';

    public static function fromUrlTarget($url)
    {
        $matches = [];
        preg_match(self::W3C_REGEX, $url, $matches);

        return new static(
            $matches[1] ? $matches[1] : 'pixel',
            $matches[2] ?? 0,
            $matches[3] ?? 0,
            $matches[4] ?? 0,
            $matches[5] ?? 0
        );
    }

    public static function create($x, $y, $h, $w)
    {
        return new static(
            'pixel',
            $x,
            $y,
            $h,
            $w
        );
    }
}
