<?php

namespace IIIF\Model;

class Collection
{
    use WithMetaData;

    private $id;
    private $manifests;
    private $label;
    private $description;
    private $attribution;
    private $metadata;

    const TYPE = 'sc:collection';

    public static function isCollection(array $data)
    {
        return strtolower($data['@type']) === self::TYPE;
    }

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

    public function getLabel()
    {
        return $this->label;
    }

    public static function fromJson(string $json)
    {
        return static::fromArray(json_decode($json, true));
    }

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

    public function getManifests()
    {
        return $this->manifests;
    }

    public function getId()
    {
        return $this->id;
    }

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

    public function setManifestLoader(callable $loader)
    {
        $manifests = $this->getManifests();
        foreach ($manifests as $manifest) {
            /* @var LazyManifest $manifest */
            $manifest->setLoader($loader);
        }
    }
}
