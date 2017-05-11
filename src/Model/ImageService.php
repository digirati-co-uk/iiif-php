<?php


namespace IIIF\Model;


class ImageService
{
    /** @var array<Tile> */
    protected $tiles;
    private $id;
    private $height;
    private $width;

    public function __construct(
        string $id,
        int $height,
        int $width,
        array $tiles
    )
    {
        $this->tiles = $tiles;
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
            array_map(function ($tile) : Tile {
                return Tile::fromArray($tile);
            }, $service['tiles'])
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
        return $largest;
    }

    public function getThumbnail()
    {
        $largestTile = $this->getLargestTile();
        return $this->id . '/full/' . $largestTile .',' . $largestTile . '/0/default.jpg';
    }

    public function getRegion(Region $region)
    {
        $largestTile = $this->getLargestTile();
        return $this->id . '/' . $region->getX() .',' . $region->getY() . ',' . $region->getWidth() . ',' . $region->getHeight(). '/' . $largestTile .',' . $largestTile . '/0/default.jpg';
    }
}