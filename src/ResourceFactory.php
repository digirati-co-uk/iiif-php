<?php

namespace IIIF;

use IIIF\Model\Collection;
use IIIF\Model\LazyManifest;
use IIIF\Model\Manifest;
use LogicException;

class ResourceFactory
{
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

    public static function createCollection(array $data, callable $loader = null)
    {
        $collection = Collection::fromArray($data);
        if ($loader) {
            $collection->setManifestLoader($loader);
        }

        return $collection;
    }

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
