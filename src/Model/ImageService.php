<?php

namespace IIIF\Model;

class ImageService
{
    /** @var array<Tile> */
    private $tiles;
    private $id;
    private $height;
    private $width;

    public function __construct(
        string $id,
        int $height,
        int $width,
        array $tiles = null
    ) {
        $this->tiles = $tiles ? $tiles : [];
        $this->id = $id;
        $this->height = $height;
        $this->width = $width;
    }

    public static function fromArray($service)
    {
        return new static(
            $service['@id'],
            $service['height'],
            $service['width'],
            isset($service['tiles']) ? array_map(function ($tile) : Tile {
                return Tile::fromArray($tile);
            }, $service['tiles']) : null
        );
    }

    public function getTile(int $num)
    {
        return $this->tiles[$num];
    }

    public function getLargestTile()
    {
        $largest = 0;
        foreach ($this->tiles as $tile) {
            /** @var $tile Tile */
            if ($tile->getLargestDimension() > $largest) {
                $largest = $tile->getLargestDimension();
            }
        }

        return $largest === 0 ? 256 : $largest;
    }

    public function getThumbnail()
    {
        $largestTile = $this->getLargestTile();

        return $this->id.'/full/'.$largestTile.','.$largestTile.'/0/default.jpg';
    }

    public function getRegion(Region $region)
    {
        $largestTile = $this->getLargestTile();

        return $this->id.'/'.$region->getX().','.$region->getY().','.$region->getWidth().','.$region->getHeight().'/'.$largestTile.','.$largestTile.'/0/default.jpg';
    }
}
