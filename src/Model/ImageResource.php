<?php
/**
 * Class definition for image resources
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Image Resources
 *
 * Class modeling image resources
 * @link http://iiif.io/api/presentation/2.1/#resource-type-overview resource types in the IIIF Presentation API specification
 */
class ImageResource
{

    use WithMetaData;
    /**
     * @var ImageService object modeling the IIIF image service providing image content
     */
    private $service;
    /**
     * @var string URI for the resource
     */
    private $id;
    /**
     * @var string the resource type
     */
    private $type;
    /**
     * @var string the image format
     */
    private $format;
    /**
     * @var int the image height
     */
    private $height;
    /**
     * @var int the image width
     */
    private $width;

    /**
     * Constructor
     * @param string $id URI for the resource
     * @param string $type resource type
     * @param string $format image format
     * @param int $height image height
     * @param int $width image width
     * @param ImageService $service object modeling the IIIF image service providing image content
     */
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

    /**
     * Construct an object from an array of values
     * @param [] $resource array of values being used to construct an ImageResource
     * @return ImageResource
     */
    public static function fromArray($resource) : self
    {
        $service = $resource['service'];
        $service['width'] = $service['width'] ?? $resource['width'];
        $service['height'] = $service['height'] ?? $resource['height'];

        return new static(
            $resource['@id'],
            $resource['@type'] ?? null,
            $resource['format'] ?? null,
            $resource['height'] ?? 0,
            $resource['width'] ?? 0,
            isset($resource['service']) ? ImageService::fromArray($service) : null
        );
    }

    /**
     * Accessor method for the IIIF image service object
     * @return ImageResource
     */
    public function getService()
    {
        return $this->service;
    }
}
