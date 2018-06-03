<?php
/**
 * Class definition for resource factory
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF;

use IIIF\Model\Collection;
use IIIF\Model\LazyManifest;
use IIIF\Model\Manifest;
use LogicException;

/**
 * Factory for constructing the Models
 *
 * Factory Class for constructing objects from values for serialized resources
 */
class ResourceFactory
{
    /**
     * Constructs new objects using string-encoded or arrays of values for the serialization (and an optional loading callback)
     * (If the serialization is a string, then a loader must be provided)
     * @param []|string $data serialized resource being used to constructor
     * @param callable $loader callback used for loading resources serialized as strings
     * @return object
     * @throws LogicException throws an exception if a string but no loader callback is passed
     */
    public static function create($data, callable $loader = null)
    {
        if (is_string($data) && $loader === null) {
            throw new LogicException('You must pass in a data loader');
        }

        if (is_string($data)) {
            $data = $loader($data);
        }
        if (Collection::isCollection($data)) {
            return static::createCollection($data, $loader);
        }
        if (Manifest::isManifest($data)) {
            return static::createManifest($data, $loader);
        }

        throw new LogicException('Invalid data provided.');
    }

    /**
     * Constructs a new Collection using an arrays of values for the serialization (and an optional loading callback for Manifests)
     * @param []|string $data serialized resource being used to constructor
     * @param callable $loader callback used for loading Manifests
     * @return Collection
     */
    public static function createCollection(array $data, callable $loader = null)
    {
        $collection = Collection::fromArray($data);
        if ($loader) {
            $collection->setManifestLoader($loader);
        }

        return $collection;
    }

    /**
     * Constructs a new Manifests using an arrays of values for the serialization (and an optional loading callback for LazyManifests)
     * @param []|string $data serialized resource being used to constructor
     * @param callable $loader callback used for loading property values for LazyManifests
     * @return Manifest
     */
    public static function createManifest(array $data, callable $loader = null)
    {
        if ($loader) {
            $manifest = LazyManifest::fromArray($data);
            if ($manifest instanceof LazyManifest) {
                $manifest->setLoader($loader);
            }

            return $manifest;
        }

        return Manifest::fromArray($data);
    }
}
