<?php
/**
 * Class definition for image tiles
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Image Tiles
 *
 * Class modeling tiles for image content requested
 * @link http://iiif.io/api/image/2.1/#technical-properties technical properties within the IIIF Image API specification
 */
class Tile
{
    use WithMetaData; /**< Mixes in the WithMetaData Trait */

    /**
     * @var int width of the tiles in pixels
     */
    private $width;
    /**
     * @var int height of the tiles in pixels
     */
    private $height;
    /**
     * @var int[] available resolution scaling factors for the image tiles generated by the service
     */
    private $scaleFactors;

    /**
     * Constructor
     * @param int $width width of the tiles in pixels
     * @param int $height height of the tiles in pixels
     * @param int[] $scaleFactors
     */
    public function __construct(
        int $width,
        int $height,
        array $scaleFactors
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->scaleFactors = $scaleFactors;
    }

    /**
     * Retrieve the largest dimension (either height or width) for this tile
     * @return int
     */
    public function getLargestDimension() : int
    {
        return $this->width >= $this->height ? $this->width : $this->height;
    }

    /**
     * Construct an object from an array of values
     * @param [] $tile array of values being used to construct an Tile
     * @return Tile
     */
    public static function fromArray($tile)
    {
        return new static(
            $tile['width'],
            $tile['height'] ?? $tile['width'],
            $tile['scaleFactors']
        );
    }
}
