<?php


namespace IIIF\Model;


class Tile
{
    private $width;
    private $height;
    private $scaleFactors;

    public function __construct(
        int $width,
        int $height,
        array $scaleFactors
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->scaleFactors = $scaleFactors;
    }

    public function getLargestDimension() : int
    {
        return $this->width >= $this->height ? $this->width : $this->height;
    }

    public static function fromArray($tile)
    {
        return new static(
            $tile['width'],
            $tile['height'],
            $tile['scaleFactors']
        );
    }
}