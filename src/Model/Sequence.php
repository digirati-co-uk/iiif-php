<?php

namespace IIIF\Model;

class Sequence
{
    private $label;
    private $canvases;

    public function __construct(string $label, array $canvases)
    {
        $this->canvases = $canvases;
        $this->label = $label;
    }

    public static function fromArray(array $sequence) {
        $canvases = array_map(function($canvas) {
            return Canvas::fromArray($canvas);
        }, $sequence['canvases']);

        return new static(
            $sequence['label'],
            $canvases
        );
    }

    public function get(int $num) : Canvas
    {
        return $this->canvases[$num] ?? null;
    }

    public function map(callable $fn)
    {
        return array_map($fn, $this->canvases);
    }

    public function find(callable $search)
    {
        foreach ($this->canvases as $canvas) {
            if ($search($canvas)) {
                return $canvas;
            }
        }
        return null;
    }

}