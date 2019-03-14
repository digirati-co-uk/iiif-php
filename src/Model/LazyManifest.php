<?php
/**
 * Class definition for lazy-loaded manifests
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Lazy-Loaded Image Manifests
 *
 * Class modeling lazy-loaded Manifests for image resources
 * @link http://iiif.io/api/presentation/2.1/#manifest manifests in the IIIF Presentation API specification
 */
class LazyManifest extends Manifest
{
    /**
     * @var boolean whether or not properties this Manifest have been loaded
     */
    private $isLoaded = false;
    /**
     * @var callable callable callback used for loading the property values
     */
    private $loader;

    /**
     * Constructor
     * @param string $id URI for this Manifest
     * @param string $label label provided for this Manifest
     * @param Sequence[] $sequences Sequences for this Manifest
     */
    public function __construct($id, $label = null, array $sequences = null, $thumbnail = [])
    {
        $this->loader = function ($url) {
            return json_decode(file_get_contents($url), true);
        };
        parent::__construct($id, $label, $sequences, $thumbnail);
    }

    /**
     * Mutator method for the loader callback
     * @param callable $loader the callback (uses $url as a single parameter)
     * @see __construct()
     */
    public function setLoader(callable $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Construct an object from an array of values
     * @param [] $data array of values being used to construct a LazyManifest
     * @return LazyManifest
     */
    public static function fromArray(array $data): Manifest
    {
        $manifest = new static(
            $data['@id'],
            $data['label'] ?? null,
            array_map(function ($sequence) {
                return Sequence::fromArray($sequence);
            }, $data['sequences'] ?? []),
            $data['thumbnail'] ?? []
        );

        $manifest->setSource($data);

        return $manifest;
    }

    /**
     * Loads the property values for this Manifest
     */
    private function load()
    {
        if ($this->isLoaded) {
            return null;
        }
        $loader = $this->loader;
        $data = $loader($this->id, $this);
        $this->id = $data['id'] ?? $this->id;
        $this->label = $data['label'] ?? null;
        $this->sequences = array_map(function ($sequence) {
            return Sequence::fromArray($sequence);
        }, $data['sequences'] ?? []);
        $this->thumbnail = $data['thumbnail'] ?? [];
        $this->isLoaded = true;
    }

    /**
     * Accessor method for the Manifest URI
     * @param boolean $force whether or not to forcibly load the property values (defaults to true)
     * @return string|null
     */
    public function getId($force = true)
    {
        if ($force) {
            $this->load();
        }

        return parent::getId();
    }

    /**
     * Accessor method for the Manifest label
     * (Loads the property values)
     * @return string
     */
    public function getLabel(): string
    {
        if (!$this->label) {
            $this->load();
        }

        return parent::getLabel();
    }

    /**
     * Retrieves the default Sequence for this Manifest
     * (Loads the property values)
     * @return Sequence
     */
    public function getDefaultSequence(): Sequence
    {
        $this->load();

        return parent::getDefaultSequence();
    }

    /**
     * Given its index, retrieves a Sequence for this Manifest
     * (Loads the property values)
     * @param int $num index for the Sequence
     * @return Sequence
     */
    public function getSequence($num)
    {
        $this->load();

        return parent::getSequence($num);
    }
}
