<?php

namespace IIIF\Tests\model;

use IIIF\Model\Image;
use IIIF\Model\ImageResource;
use IIIF\Model\ImageService;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
  protected static $image;

  protected function setUp()
  {
    $id = 'http://www.example.org/image-service/abcd1234/1E34750D-38DB-4825-A38A-B60A345E591C';
    $motivation = 'sc:painting';
    $on = 'http://example.org/iiif/book1/canvas/p1';
    $imageService = new ImageResource('', null, null, 0, 0, null);

    self::$image = new Image($id, $motivation, $on, $imageService);
  }

  protected function tearDown()
  {
    self::$image = null;
  }

  public function testGetId()
  {
    $this->assertEquals('http://www.example.org/image-service/abcd1234/1E34750D-38DB-4825-A38A-B60A345E591C', self::$image->getId());
  }

  public function testGetIdWithService()
  {
    $id = 'http://www.example.org/image-service/abcd1234/1E34750D-38DB-4825-A38A-B60A345E591C';
    $imageService = new ImageService($id, 8, 16, array());
    $imageResource = new ImageResource(
      'http://example.org/iiif/book1/res/page1.jpg',
      'dctypes:Image',
      'image/jpeg',
      2000,
      1500,
      $imageService
    );
    $motivation = 'sc:painting';
    $on = 'http://example.org/iiif/book1/canvas/p1';

    $image = new Image('', $motivation, $on, $imageResource);

    $this->assertEquals($id, $image->getId());
  }

  public function testGetMotivation()
  {
    $this->assertEquals('sc:painting', self::$image->getMotivation());
  }

  public function testGetOn()
  {
    $this->assertEquals('http://example.org/iiif/book1/canvas/p1', self::$image->getOn());
  }
}
