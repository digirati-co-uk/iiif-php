<?php

namespace IIIF\Model;

class LazyManifest extends Manifest
{
    private $isLoaded = false;
    private $loader;

    public function __construct($id, $label = null, array $sequences = null)
    {
        $this->loader = function ($url) {
            return json_decode(file_get_contents($url), true);
        };
        parent::__construct($id, $label, $sequences);
    }

    public function setLoader(callable $loader)
    {
        $this->loader = $loader;
    }

    public static function fromArray(array $data): Manifest
    {
        return new static(
            $data['@id'],
            $data['label'] ?? null,
            array_map(function ($sequence) {
                return Sequence::fromArray($sequence);
            }, $data['sequences'] ?? [])
        );
    }

    private function load()
    {
        if ($this->isLoaded) {
            return null;
        }
        $loader = $this->loader;
        $data = $loader($this->id);
        $this->id = $data['id'] ?? $this->id;
        $this->label = $data['label'] ?? null;
        $this->sequences = array_map(function ($sequence) {
            return Sequence::fromArray($sequence);
        }, $data['sequences'] ?? []);
        $this->isLoaded = true;
    }

    public function getId($force = true)
    {
        if ($force) {
            $this->load();
        }

        return parent::getId();
    }

    public function getLabel(): string
    {
        $this->load();

        return parent::getLabel();
    }

    public function getDefaultSequence(): Sequence
    {
        $this->load();

        return parent::getDefaultSequence();
    }

    /** @return Sequence */
    public function getSequence($num)
    {
        $this->load();

        return parent::getSequence($num);
    }
}
