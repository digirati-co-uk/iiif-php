<?php
/**
 * Class definition for manifests
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Manifested Image Resources
 *
 * Class modeling manifests for image resources
 * @link http://iiif.io/api/presentation/2.1/#manifest manifests within the IIIF Presentation API specification
 */
class Manifest
{
    use WithMetaData; /**< Mixes in the WithMetaData Trait */

    /**
     * @var string the namespaced type identifier for this resource
     */
    const TYPE = 'sc:manifest';

    /**
     * @var string label provided for the manifested image resource
     */
    protected $label;
    /**
     * @var Sequence[] array of Sequence objects defining the order in which these are structured
     */
    protected $sequences;
    /**
     * @var string string the URI for the manifest
     */
    protected $id;
    /**
     * @var array
     */
    protected $thumbnail;
    /**
     * @var array
     */
    protected $source;
    /**
     * @var string
     */
    private $description;

    /**
     * Constructor
     * @param string $id URI for the manifest
     * @param string $label label provided for the manifest
     * @param Sequence[] $sequences in which this manifest is structured
     * @param array $thumbnail Thumbnail for manifest
     */
    public function __construct(
        string $id,
        string $label = null,
        array $sequences = [],
        array $thumbnail = []
    ) {
        $this->label = $label;
        $this->sequences = $sequences;
        $this->id = $id;
        $this->thumbnail = $thumbnail;
    }

    protected function setSource(array $source = null)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    /**
     * Accessor method for the manifest URI
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Static method for determining whether or an array of values has serialized a manifest
     * @param [] $data the serialized manifest
     * @return boolean
     */
    public static function isManifest(array $data)
    {
        return strtolower($data['@type']) === self::TYPE;
    }

    /**
     * Construct an object from an array of values
     * @param [] $data array of values being used to construct a Manifest
     * @return Manifest
     */
    public static function fromArray(array $data): self
    {
        $manifest = new static(
            $data['@id'],
            $data['label'] ?? '',
            array_map(function ($sequence) {
                return Sequence::fromArray($sequence);
            }, $data['sequences'] ?? []),
            $data['thumbnail'] ?? []
        );

        $manifest->setSource($data);

        return $manifest;
    }

    /**
     * Construct an object from a string of a JSON-serialized Manifest
     * @param string $json string containing the JSON-serialized values
     * @return Manifest
     */
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        return static::fromArray($data);
    }

    /**
     * Generate the canonical URL for a given URI
     * @see getCanvasRegionFromUrl()
     * @param string $uri the URI
     */
    public function getCanonicalUrl($uri)
    {
        $segments = explode('#', $uri);
        array_pop($segments);

        return implode('#', $segments);
    }

    /**
     * Retrieve the region on a canvas for a given URI
     * @param string $uri the URI
     * @return Region
     */
    public function getCanvasRegionFromUrl($uri)
    {
        $region = Region::fromUrlTarget($uri);
        $canonicalUri = $this->getCanonicalUrl($uri);
        if (!$this->containsCanvas($canonicalUri)) {
            return null;
        }
        $canvas = $this->getCanvas($canonicalUri);

        return $canvas->getRegion($region);
    }

    /**
     * Accessor method for the label
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label ?? '';
    }

    public function getDescription(): string
    {
        return $this->source['description'] ?? '';
    }

    /**
     * Retrieve a canvas using its index from the default sequence for this manifest
     * @param int $num the number
     * @return Canvas
     */
    public function getCanvasNumber($num = 0)
    {
        return $this->getDefaultSequence()->get($num);
    }

    /**
     * Given a the number of a sequence for this manifest, retrieve all of its canvases
     * @param int $fromSequence number of the sequence
     * @return Canvas[]
     */
    public function getCanvases($fromSequence = 0)
    {
        return $this->getSequence($fromSequence)->getCanvases();
    }

    /**
     * Given the number of a sequence for this manifest, retrieve all thumbnail URLs for its canvases
     * @param int $sequenceNum number of the sequence
     * @return string[]
     */
    public function getThumbnails($sequenceNum = 0)
    {
        $canvases = $this->getSequence($sequenceNum);

        if (!$canvases) {
            return [];
        }

        return $canvases->map(function (Canvas $canvas) {
            return $canvas->getThumbnail();
        });
    }

    /**
     * Retrieve the default (i. e. first) sequence for this manifest
     * @return Sequence
     */
    public function getDefaultSequence(): Sequence
    {
        return $this->sequences[0];
    }

    /**
     * Retrieve a sequence using its index for this manifest
     * @param int $num the number
     * @return Sequence|null
     */
    public function getSequence($num)
    {
        return $this->sequences[$num] ?? null;
    }

    /**
     * Given a URI for a canvas and the index for a sequence, retrieve the Canvas object
     * @param string $id URI for the canvas
     * @param int $sequence the index for the sequence
     * @return Canvas
     */
    public function getCanvas(string $id, int $sequence = 0)
    {
        return $this->getSequence($sequence)->find(function (Canvas $canvas) use ($id) {
            return $canvas->getId() === $id;
        });
    }

    /**
     * Given a URI for a canvas and the index for a sequence, determine whether or not this Manifest contains the Canvas
     * @param string $id URI for the canvas
     * @param int $sequence index for the sequence
     * @return boolean
     */
    public function containsCanvas(string $id, int $sequence = 0)
    {
        return (bool) $this->getCanvas($id, $sequence);
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        if (is_string($this->thumbnail)) {
            return $this->thumbnail;
        }
        if (isset($this->thumbnail['@id'])) {
            return $this->thumbnail['@id'];
        }

        $thumbnails = $this->getThumbnails(0);

        return $thumbnails[0] ?? '';
    }

    public function getAttribution()
    {
        return $this->source['attribution'] ?? null;
    }
}
