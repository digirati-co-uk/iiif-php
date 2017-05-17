<?php

namespace IIIF\Model;

trait WithMetaData
{
    protected $metaData = [];

    public function __get($name)
    {
        if (isset($this->metaData[$name])) {
            return $this->metaData[$name];
        }

        return null;
    }

    public function addMetaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

    public function withMetaData($metaData)
    {
        $model = clone $this;
        $model->metaData = $metaData;
        return $model;
    }
}
