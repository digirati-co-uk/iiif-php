<?php
/**
 * Class definition for canvases
 *
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Canvases for Images
 *
 * Class modeling canvases for image resources
 * @link http://iiif.io/api/presentation/2.1/#canvas Canvases in the IIIF Presentation API specification
 */
class Canvas
{
    use WithMetaData;

    /**
     * @var string URI for this Canvas
     */
    private $id;
    /**
     * @var string label provided for this Canvas
     */
    private $label;
    /**
     * @var int height for this Canvas
     */
    private $height;
    /**
     * @var int width for this Canvas
     */
    private $width;
    /**
     * @var Image[] images referenced within this Canvas
     */
    protected $images;
    /**
     * @var string URI for the thumbnail used on this canvas
     */
    protected $thumbnail;
    /**
     * @var array Original source of canvas
     */
    protected $source;

    /**
     * Constructor
     * @param string $id URI for this Canvas
     * @param string $label label provided for this Canvas
     * @param string $thumbnail URI for the thumbnail used on this canvas
     * @param int $height height for this Canvas
     * @param int $width width for this Canvas
     * @param Image[] $images
     */
    public function __construct(string $id, string $label, string $thumbnail = null, int $height, int $width, array $images)
    {
        $this->label = $label;
        $this->height = $height;
        $this->width = $width;
        $this->images = $images;
        $this->thumbnail = $thumbnail;
        $this->id = $id;
    }

    /**
     * Accessor method for the Canvas URI
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Accessor method for the label provided for this Canvas
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Accessor method for the height of this Canvas
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Accessor method for the width of this Canvas
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Accessor method for Images objects within this Canvas
     * @return Image[]
     */
    public function getImages() : array
    {
        return $this->images;
    }

    /**
     * Retrieve an Image object for this Canvas using its index
     * @param int $num the index
     * @return Image|null
     */
    public function getImage($num = 0)
    {
        return $this->images[$num];
    }

    /**
     * Given an index for an image and a region, generate the URI requesting image content within this region from a IIIF image service
     * @param Region $region region of the image requested
     * @param int $num index for the image
     * @return string
     */
    public function getRegion(Region $region, $num = 0)
    {
        $image = $this->getImage($num);

        return $image->getImageService()->getRegion($region);
    }

    /**
     * Generate the URI for the thumbnail for this Canvas
     * @return string
     */
    public function getThumbnail()
    {
        if ($this->thumbnail) {
            return $this->thumbnail;
        }

        return $this->getImage()->getThumbnail();
    }

    /**
     * Given a string or an array of values serializing a resource, retrieve the URI for the thumbnail
     * @param array|string $thumbnail the values from which the thumbnail URI is generated
     * @return string|null
     */
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

    /**
     * Construct an object from an array of values
     * @param array $canvas array of values being used to construct a Canvas
     * @return Canvas
     */
    public static function fromArray($canvas)
    {
        $images = array_map(function ($image) {
            return Image::fromArray($image);
        }, $canvas['images']);

        $canvasObj = new static(
            $canvas['@id'],
            $canvas['label'] ?? '',
            self::parseThumbnailService($canvas['thumbnail'] ?? null),
            $canvas['height'],
            $canvas['width'],
            $images
        );

        $canvasObj->setSource($canvas);

        return $canvasObj;
    }

    public function getAttribution()
    {
        return $this->source['attribution'] ?? null;
    }

    /**
     * @return array
     */
    public function getSource(): array
    {
        return $this->source;
    }

    /**
     * @param array $source
     */
    public function setSource(array $source): void
    {
        $this->source = $source;
    }
}
