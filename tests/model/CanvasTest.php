<?php

namespace IIIF\Tests\model;

use IIIF\Model\Canvas;
use PHPUnit\Framework\TestCase;

class CanvasTest extends TestCase
{

  protected static $canvas;

  public static function setUpBeforeClass()
  {
    $id = 'http://example.org/images/book1-page1/full/80,100/0/default.jpg';
    $label = 'Book 1';
    $thumbnail = 'http://example.org/images/book1-page1/full/80,100/0/default.jpg';
    $height = 8;
    $width = 16;
    $images = [];
    self::$canvas = new Canvas($id, $label, $thumbnail, $height, $width, $images);
  }

  public static function tearDownAfterClass()
  {
    self::$canvas = null;
  }

  public function testGetLabel()
  {
    $this->assertEquals(self::$canvas->getLabel(), 'Book 1');
  }

  public function testGetHeight()
  {
    $this->assertEquals(self::$canvas->getHeight(), 8);
  }

  public function testGetWidth()
  {
    $this->assertEquals(self::$canvas->getWidth(), 16);
  }

  public function testParseThumbnailService()
  {
    $data = array('@id' => 'http://example.org/images/book1-page1/full/80,100/0/default.jpg');
    $this->assertEquals(Canvas::parseThumbnailService($data), 'http://example.org/images/book1-page1/full/80,100/0/default.jpg');
  }

  public function testParseThumbnailServiceWithInvalid()
  {
    $this->assertNull(Canvas::parseThumbnailService(true));
  }

  public function testParseThumbnailServiceWithString()
  {
    $this->assertEquals(Canvas::parseThumbnailService('http://example.org/images/book1-page1/full/80,100/0/default.jpg'), 'http://example.org/images/book1-page1/full/80,100/0/default.jpg');
  }

  public function testParseThumbnailServiceWithNull()
  {
    $this->assertNull(Canvas::parseThumbnailService(null));
  }
}
