<?php

namespace IIIF\tests\model;

use IIIF\Model\WithMetaData;
use PHPUnit\Framework\TestCase;

/**
 * Class defined for the purposes of testing the Trait
 */
class MyResource {
  use WithMetaData;

  private $id;

  public function __construct($id, $metaData)
  {
    $this->id = $id;
    $this->metaData = $metaData;
  }
}

class WithMetaDataTest extends TestCase
{
  protected static $myResource;

  protected function setUp()
  {
    $id = 'http://example.org/iiif/my-resource';
    $metaData = array('testLabel1' => 'test value 1');

    self::$myResource = new MyResource($id, $metaData);
  }

  protected function tearDown()
  {
    self::$myResource = null;
  }

  public function testWithMetaData()
  {
    $data = array('testLabel2' => 'test value 2');
    $updated = self::$myResource->withMetaData($data);
    $this->assertInstanceOf('IIIF\tests\model\MyResource', $updated);
    $this->assertEquals('test value 2', $updated->testLabel2);
    $this->assertEquals('test value 1', $updated->testLabel1);
  }

  public function testWithMetaDataWithoutMerge()
  {
    $data = array('testLabel2' => 'test value 2');
    $updated = self::$myResource->withMetaData($data, false);
    $this->assertInstanceOf('IIIF\tests\model\MyResource', $updated);
    $this->assertEquals('test value 2', $updated->testLabel2);
    $this->assertNull($updated->testLabel1);
  }
}
