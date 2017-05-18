<?php

namespace IIIF\Model;

trait WithMetaData
{
    protected $metaData = [];

    /**
     * This is required for twig templates since they do not support get.
     */
    public function __call($name, $arguments)
    {
        return $this->__get($name);
    }

    public function __get($name)
    {
        if (isset($this->metaData[$name])) {
            return $this->metaData[$name];
        }

        return null;
    }

    public function getMetaData() : array
    {
        return $this->metaData && is_array($this->metaData) ? $this->metaData : [];
    }

    public function addMetaData($metaData)
    {
        foreach ($metaData as $name => $value) {
            $this->metaData[$name] = $value;
        }

        return $this;
    }

    public function withMetaData($metaData, $merge = true)
    {
        $model = clone $this;
        $model->metaData = $merge ?
            array_merge($this->getMetaData(), $metaData) :
            $metaData;

        return $model;
    }
}
