<?php
/**
 * Class definition for images
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */


namespace IIIF\Model;

/**
 * Image Content
 *
 * Class modeling image content for canvases
 * @link http://iiif.io/api/presentation/2.1/#canvas Canvases in the IIIF Presentation API specification
 */
class Image
{
    use WithMetaData;

    /**
     * @var ImageResource object modeling the image resource itself
     */
    protected $resource;
    /**
     * @var string URI for this image resource
     */
    private $id;
     /**
      * @var string encoded motivation (usually sc:painting for images, oa:commenting for annotations) for this image resource
      */
    private $motivation;
    /**
     * @var string URI for the Canvas referenced by this image (usually for annotion linking)
     */
    private $on;

    /**
     * Constructor
     * @param string $id URI for this image resource
     * @param string $motivation encoded motivation
     * @param string $on URI for the linked Canvas
     * @param ImageResource $imageService object modeling the image resource
     */
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

    /**
     * Construct an object from an array of values
     * @param [] $image array of values being used to construct an Image
     * @return Image
     */
    public static function fromArray($image)
    {
        return new static(
            $image['@id'],
            $image['motivation'],
            $image['on'],
            ImageResource::fromArray($image['resource'])
        );
    }

    /**
     * Accessor method for the Image URI
     * @return string
     */
    public function getId(): string
    {
        return $this->id ? $this->id : $this->getImageService()->getId();
    }

    /**
     * Accessor method for the encoded motivation
     * @return string
     */
    public function getMotivation()
    {
        return $this->motivation;
    }

    /**
     * Accessor method for the linked Canvas URI
     * @return string
     */
    public function getOn()
    {
        return $this->on;
    }

    /**
     * Accessor method for the image resource object
     * @return ImageResource
     */
    public function getImageService(): ImageService
    {
        return $this->resource->getService();
    }

    /**
     * Retrieves the URI for the thumbnail for the image resource
     * @return string
     */
    public function getThumbnail(): string
    {
        return $this->getImageService()->getThumbnail();
    }
}
