<?php

namespace IIIF\Tests\model;

use LogicException;
use IIIF\ResourceFactory;
use PHPUnit\Framework\TestCase;

class ResourceFactoryTest extends TestCase
{
    /**
     * @expectedException LogicException
     * @expectedExceptionMessage You must pass in a data loader
     */
    public function testCreateWithoutLoader()
    {
      $file = __DIR__ . '/../fixtures/manifest-a.json';
      $data = file_get_contents($file);
      ResourceFactory::create($data, null);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Invalid data provided.
     */
    public function testCreateWithInvalidData()
    {
      $data = array('@type' => 'foo');
      ResourceFactory::create($data, null);
    }
}
