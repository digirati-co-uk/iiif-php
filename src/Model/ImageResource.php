<?php

namespace IIIF\Model;

class ImageResource
{
    private $service;
    private $id;
    private $type;
    private $format;
    private $height;
    private $width;

    public function __construct(
        string $id,
        string $type,
        string $format,
        int $height,
        int $width,
        ImageService $service
    )
    {
        $this->service = $service;
        $this->id = $id;
        $this->type = $type;
        $this->format = $format;
        $this->height = $height;
        $this->width = $width;
    }

    public static function fromArray($resource) : self
    {
        return new static(
            $resource['@id'],
            $resource['@type'],
            $resource['format'],
            $resource['height'],
            $resource['width'],
            ImageService::fromArray($resource['service'])
        );
    }

    public function getService()
    {
        return $this->service;
    }
}