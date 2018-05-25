<?php

namespace IIIF\Tests\model;

use IIIF\Model\Collection;
use IIIF\Model\LazyManifest;
use IIIF\Model\Manifest;
use PHPUnit\Framework\TestCase;

class WellcomeTest extends TestCase
{

    public function test_it_can_parse_wellcome_collection()
    {
        $json = file_get_contents(__DIR__ . '/../fixtures/wellcome-collection.json');
        $collection = Collection::fromJson($json);
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(54, sizeof($collection->getManifests()));
        $this->assertContainsOnlyInstancesOf(LazyManifest::class, $collection->getManifests());

        $this->assertEquals(' Genre: Caricatures', $collection->getLabel());
    }

    public function test_it_can_load_wellcome_manifest()
    {
        $json = file_get_contents(__DIR__ . '/../fixtures/wellcome-manifest.json');
        $manifest = Manifest::fromJson($json);
        $this->assertNotEmpty($manifest->getLabel());
        $this->assertEquals(1, sizeof($manifest->getThumbnails()));
        $this->assertEquals('https://dlcs.io/thumbs/wellcome/1/8fb54b80-80e6-427d-acd8-6a323f354249/full/71,100/0/default.jpg', $manifest->getThumbnails()[0]);
    }

    public function test_it_can_load_manifests_lazily()
    {
        $json = file_get_contents(__DIR__ . '/../fixtures/wellcome-collection.json');
        $collection = Collection::fromJson($json);
        $collection->setManifestLoader(function ($url) {
            $file = __DIR__ . '/../fixtures/http/' . md5($url);
            if (file_exists($file)) {
                return json_decode(file_get_contents($file), true);
            }
            throw new \Exception('Please comment out lines below to record test cases.');
        });
        foreach ($collection->getManifests() as $manifest) {
            $this->assertInstanceOf(Manifest::class, $manifest);
            $this->assertNotEmpty($manifest->getLabel());
        }
    }

}
