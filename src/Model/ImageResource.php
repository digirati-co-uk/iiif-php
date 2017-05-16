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
        string $type = null,
        string $format = null,
        int $height,
        int $width,
        ImageService $service = null
    ) {
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
            $resource['@type'] ?? null,
            $resource['format'] ?? null,
            $resource['height'] ?? 0,
            $resource['width'] ?? 0,
            isset($resource['service']) ? ImageService::fromArray($resource['service']) : null
        );
    }

    public function getService()
    {
        return $this->service;
    }
}
