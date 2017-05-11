<?php


namespace IIIF\Model;


class Manifest
{
    private $label;
    private $sequences;
    private $id;

    public function __construct(
        string $id,
        string $label,
        array $sequences
    )
    {
        $this->label = $label;
        $this->sequences = $sequences;
        $this->id = $id;
    }

    public static function fromJson($json) : self
    {
        $data = json_decode($json, true);

        return new static(
            $data['@id'],
            $data['label'],
            array_map(function($sequence) {
                return Sequence::fromArray($sequence);
            }, $data['sequences'])
        );
    }

    public function getCanonicalUrl($uri)
    {
        $segments = explode('#',$uri);
        array_pop($segments);
        return implode('#', $segments);
    }

    public function getCanvasRegionFromUrl($uri)
    {
        $region = Region::fromUrlTarget($uri);
        $canonicalUri = $this->getCanonicalUrl($uri);
        if (!$this->containsCanvas($canonicalUri)) {
            return null;
        }
        $canvas = $this->getCanvas($canonicalUri);
        return $canvas->getRegion($region);
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getCanvasNumber($num = 0)
    {
        return $this->getDefaultSequence()->get($num);
    }

    public function getThumbnails($sequenceNum = 0)
    {
        $canvases = $this->getSequence($sequenceNum);

        return $canvases->map(function(Canvas $canvas) {
            return $canvas->getThumbnail();
        });
    }

    public function getDefaultSequence() : Sequence
    {
        return $this->sequences[0];
    }

    /** @return Sequence */
    public function getSequence($num)
    {
        return $this->sequences[$num] ?? null;
    }

    /** @return Canvas|null */
    public function getCanvas(string $id, int $sequence = 0)
    {
        return $this->getSequence($sequence)->find(function (Canvas $canvas) use ($id) {
            return $canvas->getId() === $id;
        });
    }

    public function containsCanvas(string $id, int $sequence = 0)
    {
        return !!$this->getCanvas($id, $sequence);
    }
}