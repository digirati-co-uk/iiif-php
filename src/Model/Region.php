<?php
/**
 * Class definition for image regions
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Image Regions
 *
 * Class modeling rectangular regions within image resources
 * @link http://iiif.io/api/image/2.1/#region regions within the IIIF Image API specification
 */
class Region
{
    use WithMetaData;

    /**
     * @var string units for the specified region
     */
    private $unit;
    /**
     * @var int horizontal offset (units from 0 on the horizontal axis) for the specified region
     */
    private $x;
    /**
     * @var int vertical offset (units from 0 on the vertical axis) for the specified region
     */
    private $y;
    /**
     * @var int width of the region
     */
    private $width;
    /**
     * @var int height of the region
     */
    private $height;

    /**
     * Constructor
     * @param string $unit units for the specified region
     * @param int $x horizontal offset for the specified region
     * @param int $y vertical offset for the specified region
     * @param int $width width of the region
     * @param int $height height of the region
     */
    public function __construct(string $unit, int $x, int $y, int $width, int $height)
    {
        $this->unit = $unit;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Accessor method for the horizontal offset
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Accessor method for the vertical offset
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Accessor method for the height
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Accessor method for the width
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    const W3C_REGEX = '/[#&\?]xywh\=(pixel\:|percent\:)?(\d+),(\d+),(\d+),(\d+)/'; /**< @var regular expressing for parsing the bounding box from a URI */

    /**
     * Constructs a Region object from a URI containing the bounding box
     * @param string $url URI containing the bounding box
     * @return Region
     */
    public static function fromUrlTarget($url)
    {
        $matches = [];
        preg_match(self::W3C_REGEX, $url, $matches);

        return new static(
            $matches[1] ? $matches[1] : 'pixel',
            $matches[2] ?? 0,
            $matches[3] ?? 0,
            $matches[4] ?? 0,
            $matches[5] ?? 0
        );
    }

    /**
     * Constructs a Region object using the horizontal and vertical offsets, height, and width
     * (Defaults to using pixels as units)
     * @param int $x horizontal offset for the specified region
     * @param int $y vertical offset for the specified region
     * @param int $h height of the region
     * @param int $w width of the region
     * @return Region
     */
    public static function create($x, $y, $h, $w)
    {
        return new static(
            'pixel',
            $x,
            $y,
            $h,
            $w
        );
    }
}
