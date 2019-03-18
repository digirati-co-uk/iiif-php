<?php
/**
 * Trait definition for IIIF metadata
 * @category   IIIF
 * @package    IIIF
 * @subpackage Model
 * @link       https://packagist.org/packages/dlcs/iiif-php
 * @author Stephen Fraser <stephen.fraser@digirati.com>
 */

namespace IIIF\Model;

/**
 * IIIF metadata
 *
 * Trait providing methods for Classes modeling
 * @link http://iiif.io/api/presentation/2.1/#descriptive-properties descriptive properties within the IIIF Presentation API specification
 */
trait WithMetaData
{
    /**
     * @var [] the metadata values mapped using labels for keys
     */
    protected $metaData = [];

    /**
     * This is required for twig templates since they do not support get.
     * @param string $name label of the metadata pair
     * @param mixed $arguments arguments passed to the method (unused)
     * @return string
     */
    public function __call($name, $arguments)
    {
        return $this->__get($name);
    }

    /**
     * Default to the metadata array before attempting to invoke an accessor method
     * @param string $name label of the metadata pair
     * @return string|null
     */
    public function __get($name)
    {
        if (isset($this->metaData[$name])) {
            return $this->metaData[$name];
        }

        return null;
    }

    /**
     * Accessor for the metadata array
     * @return []
     */
    public function getMetaData() : array
    {
        if (isset($this->source['metadata'])) {
            return $this->source['metadata'];
        }

        return $this->metaData && is_array($this->metaData) ? $this->metaData : [];
    }

    /**
     * Appends a pair of metadata values to the existing array
     * (Overrides any existing metadata pairs keyed to the same label)
     * @param [] $metaData pair of metadata values keyed to labels being added
     * @return object
     */
    public function addMetaData($metaData)
    {
        foreach ($metaData as $name => $value) {
            $this->metaData[$name] = $value;
        }

        return $this;
    }

    /**
     * Replaces or merges a pair of metadata values to the array of a cloned object
     * @param [] $metaData pair of metadata values keyed to labels replacing or being merged with the cloned metadata values
     * @param boolean $merge whether to merge or replace with the cloned object (defaults to true)
     * @return object
     */
    public function withMetaData($metaData, $merge = true)
    {
        $model = clone $this;
        $model->metaData = $merge ?
            array_merge($this->getMetaData(), $metaData) :
            $metaData;

        return $model;
    }
}
