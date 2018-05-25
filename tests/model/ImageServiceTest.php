<?php

namespace IIIF\Tests\model;

use IIIF\Model\ImageService;
use IIIF\Model\Tile;
use PHPUnit\Framework\TestCase;

class ImageServiceTest extends TestCase
{
  protected static $imageService;

  protected function setUp()
  {
    $id = 'http://example.org/images/book1-page2';
    $height = 8000;
    $width = 6000;
    $tile1 = new Tile(512, 256, array(1,2,4,8,16));
    $tile2 = new Tile(128, 64, array(1,2,4,8,16));
    $tiles = array($tile1, $tile2);

    self::$imageService = new ImageService($id, $height, $width, $tiles);
  }

  protected function tearDown()
  {
    self::$imageService = null;
  }

  public function testGetTile()
  {
    $tile1 = self::$imageService->getTile(0);
    $this->assertInstanceOf('IIIF\Model\Tile', $tile1);
    $this->assertEquals(512, $tile1->getLargestDimension());

    $tile2 = self::$imageService->getTile(1);
    $this->assertInstanceOf('IIIF\Model\Tile', $tile2);
    $this->assertEquals(128, $tile2->getLargestDimension());
  }
}
