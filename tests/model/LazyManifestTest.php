<?php

namespace IIIF\Tests\model;

use IIIF\Model\LazyManifest;
use PHPUnit\Framework\TestCase;

class LazyManifestTest extends TestCase
{

  protected static $lazyManifest;

  protected function setUp()
  {
    $id = __DIR__ . '/../fixtures/manifest-a.json';
    $label = 'Book 1';
    $sequences = [];

    self::$lazyManifest = new LazyManifest($id, $label, $sequences);
  }

  protected function tearDown()
  {
    self::$lazyManifest = null;
  }

  public function testLoad()
  {
    // self::$lazyManifest->load() is invoked here only once within self::$lazyManifest->getSequence(0)
    $sequence = self::$lazyManifest->getSequence(0);
    $this->assertInstanceOf('IIIF\Model\Sequence', $sequence);
  }

  public function testLoadWhenLoaded()
  {
    self::$lazyManifest->load();
    $this->assertNull(self::$lazyManifest->load());
  }

  public function testGetDefaultSequence()
  {
    $sequence = self::$lazyManifest->getDefaultSequence();
    $this->assertInstanceOf('IIIF\Model\Sequence', $sequence);
  }
}
