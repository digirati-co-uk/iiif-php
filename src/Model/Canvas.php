<?php

namespace IIIF\Model;

class Canvas
{
    private $id;
    private $label;
    private $height;
    private $width;
    protected $images;

    public function getId()
    {
        return $this->id;
    }

    public function __construct(string $id, string $label, string $thumbnail = null, int $height, int $width, array $images)
    {

        $this->label = $label;
        $this->height = $height;
        $this->width = $width;
        $this->images = $images;
        $this->thumbnail = $thumbnail;
        $this->id = $id;
    }

    public function getImages() : array
    {
        return $this->images;
    }

    /** @return Image|null */
    public function getImage($num = 0)
    {
        return $this->images[$num];
    }

    public function getRegion(Region $region, $num = 0)
    {
        $image = $this->getImage($num);
        return $image->getImageService()->getRegion($region);
    }

    public function getThumbnail()
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }
        return $this->getImage()->getThumbnail();
    }

    public static function parseThumbnailService($thumbnail)
    {
        if (!$thumbnail) {
            return null;
        }
        if (is_string($thumbnail)) {
            return $thumbnail;
        }
        if (isset($thumbnail['@id'])) {
            return $thumbnail['@id'];
        }
        return null;
    }

    public static function fromArray($canvas)
    {
        $images = array_map(function($image) {
            return Image::fromArray($image);
        }, $canvas['images']);

        return new static(
            $canvas['@id'],
            $canvas['label'] ?? '',
            self::parseThumbnailService($canvas['thumbnail'] ?? null),
            $canvas['height'],
            $canvas['width'],
            $images
        );
    }
}