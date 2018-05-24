<?php
/**
 * Class definition for collections
 *
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * Collections of Manifests.
 *
 * Class modeling Collections of Manifests
 * @link http://iiif.io/api/presentation/2.0/#collections Collections in the IIIF Presentation API specification
 */
class Collection
{
    use WithMetaData;

    /**
     * @var string the namespaced type identifier for this resource.
     */
    const TYPE = 'sc:collection';

    /**
     * @var string URI for the Collection.
     */
    private $id;
    /**
     * @var Manifest[] Manifests contained within the Collection
     */
    private $manifests;
    /**
     * @var string label provided for the collection
     */
    private $label;
    /**
     * @var string description provided for the collection
     */
    private $description;
    /**
     * @var string parties attributed to the collection
     */
    private $attribution;
    /**
     * @var [] metadata values for the collection
     */
    private $metadata;

    /**
     * Determines whether or not an array of values serializes a collection
     * @param [] $data the values for the serialized resource
     * @return boolean
     */
    public static function isCollection(array $data)
    {
        return strtolower($data['@type']) === self::TYPE;
    }

    /**
     * Constructor
     * @param string $id the URI for the Collection
     * @param string $label provided for the collection
     * @param string $description description provided for the collection
     * @param string $attribution parties attributed to the collection
     * @param Manifest[] $manifests Manifests contained within the Collection
     * @param [] $metadata metadata values for the collection
     */
    public function __construct(
        string $id,
        string $label = null,
        string $description = null,
        string $attribution = null,
        array $manifests = [],
        array $metadata = null
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
        $this->attribution = $attribution;
        $this->manifests = $manifests;
        $this->metadata = $metadata;
    }

    /**
     * Accessor method for the label
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

   /**
    * Construct an object from a string of a JSON-serialized Collection
    * @param string $json string containing the JSON-serialized values
    * @return Collection
    */
    public static function fromJson(string $json)
    {
        return static::fromArray(json_decode($json, true));
    }

    /**
     * Given an array containing a serialized Collection, retrieve its label
     * @param [] $data serialized Collection values
     * @return string|null
     */
    private static function getLabelFromData($data)
    {
        if (is_string($data)) {
            return $data;
        }
        if (isset($data['@value'])) {
            return $data['@value'];
        }
        if (isset($data[0]['@value'])) {
            return $data[0]['@value'];
        }

        return null;
    }

    /**
     * Given an array containing a serialized Collection, retrieve its serialized Manifests
     * @param [] $data serialized Collection values
     * @return []
     */
    private static function getManifestsFromData($data)
    {
        if (isset($data['members'])) {
            return $data['members'];
        }
        if (isset($data['manifests'])) {
            return $data['manifests'];
        }

        return [];
    }

    /**
     * Accessor method for the Manifests in this Collection
     * @return Manifest[]
     */
    public function getManifests()
    {
        return $this->manifests;
    }

    /**
     * Access method for the URI of this Collection
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Construct an object from an array of values
     * @param [] $data array of values being used to construct a Collection
     * @return Collection
     */
    public static function fromArray(array $data)
    {
        return new static(
            $data['@id'],
            static::getLabelFromData($data['label'] ?? []),
            $data['description'] ?? null,
            $data['attribution'] ?? null,
            array_map(function ($manifest) {
                return LazyManifest::fromArray($manifest);
            }, static::getManifestsFromData($data))
        );
    }

    /**
     * Provide a callback used to deserialize all lazy-loaded Manifests within this Collection
     * @see LazyManifest::setLoader()
     * @param callable $loader the callback used to deserialize the LazyManifest
     */
    public function setManifestLoader(callable $loader)
    {
        $manifests = $this->getManifests();
        foreach ($manifests as $manifest) {
            /* @var LazyManifest $manifest */
            $manifest->setLoader($loader);
        }
    }
}
