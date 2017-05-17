<?php

namespace IIIF\Model;

class Image
{
    use WithMetaData;

    protected $resource;
    private $id;
    private $motivation;
    private $on;

    public function __construct(
        string $id,
        string $motivation,
        string $on,
        ImageResource $imageService
    ) {
        $this->resource = $imageService;
        $this->id = $id;
        $this->motivation = $motivation;
        $this->on = $on;
    }

    public static function fromArray($image)
    {
        return new static(
            $image['@id'],
            $image['motivation'],
            $image['on'],
            ImageResource::fromArray($image['resource'])
        );
    }

    public function getImageService(): ImageService
    {
        return $this->resource->getService();
    }

    public function getThumbnail(): string
    {
        return $this->getImageService()->getThumbnail();
    }
}
