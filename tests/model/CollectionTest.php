<?php

namespace IIIF\tests\model;

use IIIF\Model\Collection;
use IIIF\Model\LazyManifest;
use IIIF\ResourceFactory;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

  protected static $collection;

  protected function setUp()
  {

    $id = 'http://example.org/iiif/collection/top';
    $manifests = [];
    $label = 'Top Level Collection for Example Organization';
    $description = 'Description of Collection';
    $attribution = 'Provided by Example Organization';
    $metadata = null;

    self::$collection = new Collection(
      $id,
      $label,
      $description,
      $attribution,
      $manifests,
      $metadata
    );
  }

  protected function tearDown()
  {
    self::$collection = null;
  }

  public function test_can_load_collection_with_manifest_field()
  {
      $collectionData = file_get_contents(__DIR__.'/../fixtures/collection-manifest-field.json');
      $collection = Collection::fromJson($collectionData);
      $this->assertInstanceOf(Collection::class, $collection);
      $this->assertEquals(5, sizeof($collection->getManifests()));
      $this->assertContainsOnlyInstancesOf(LazyManifest::class, $collection->getManifests());

      $this->assertEquals('[Trawsfynydd Carnival, Images of O\'Brien and John Douglas]', $collection->getLabel());
  }

  public function test_can_load_collection_with_member_field()
  {
      $collectionData = file_get_contents(__DIR__.'/../fixtures/collection-member-field.json');
      $collection = Collection::fromJson($collectionData);
      $this->assertInstanceOf(Collection::class, $collection);
      $this->assertEquals(12, sizeof($collection->getManifests()));
      $this->assertContainsOnlyInstancesOf(LazyManifest::class, $collection->getManifests());

      $this->assertEquals('', $collection->getLabel());
  }

  public function test_is_collection()
  {
      $collection1 = json_decode(file_get_contents(__DIR__.'/../fixtures/collection-member-field.json'), true);
      $this->assertTrue(Collection::isCollection($collection1));

      $collection2 = json_decode(file_get_contents(__DIR__.'/../fixtures/collection-manifest-field.json'), true);
      $this->assertTrue(Collection::isCollection($collection2));

      $manifest1 = json_decode(file_get_contents(__DIR__.'/../fixtures/manifest-a.json'), true);
      $this->assertFalse(Collection::isCollection($manifest1));

      $manifest2 = json_decode(file_get_contents(__DIR__.'/../fixtures/manifest-b.json'), true);
      $this->assertFalse(Collection::isCollection($manifest2));
  }

  public function test_creation_using_factory()
  {
      $collection1 = json_decode(file_get_contents(__DIR__.'/../fixtures/collection-member-field.json'), true);
      $collection = ResourceFactory::createCollection($collection1);
      $this->assertInstanceOf(Collection::class, $collection);

      $collection1 = json_decode(file_get_contents(__DIR__.'/../fixtures/collection-member-field.json'), true);
      $collection = ResourceFactory::create($collection1);
      $this->assertInstanceOf(Collection::class, $collection);

      $collection = ResourceFactory::create(__DIR__.'/../fixtures/collection-member-field.json', function ($file) {
          return json_decode(file_get_contents($file), true);
      });
      $this->assertInstanceOf(Collection::class, $collection);
  }

  public function testGetManifestsFromData()
  {
    $manifest1 = array(
      '@id' => 'http://example.org/iiif/book1/manifest',
      'label' => 'Book 1',
      'sequences' => []
    );
    $manifest2 = array(
      '@id' => 'http://example.org/iiif/book2/manifest',
      'label' => 'Book 2',
      'sequences' => []
    );
    $data = array(
      '@id' => 'http://example.org/iiif/collection/top',
      'label' => 'Top Level Collection for Example Organization',
      'description' => 'Description of Collection',
      'attribution' => 'Provided by Example Organization',
      'manifests' => [$manifest1, $manifest2]
    );
    $collection = Collection::fromArray($data);
    $this->assertCount(2, $collection->getManifests());
  }

  public function testGetManifestsFromDataWithMembers()
  {
    $manifest1 = array(
      '@id' => 'http://example.org/iiif/book1/manifest',
      'label' => 'Book 1',
      'sequences' => []
    );
    $manifest2 = array(
      '@id' => 'http://example.org/iiif/book2/manifest',
      'label' => 'Book 2',
      'sequences' => []
    );
    $data = array(
      '@id' => 'http://example.org/iiif/collection/top',
      'label' => 'Top Level Collection for Example Organization',
      'description' => 'Description of Collection',
      'attribution' => 'Provided by Example Organization',
      'members' => [$manifest1, $manifest2]
    );
    $collection = Collection::fromArray($data);
    $this->assertCount(2, $collection->getManifests());
  }

  public function testGetManifestsFromDataWhenEmpty()
  {
    $data = array(
      '@id' => 'http://example.org/iiif/collection/top',
      'label' => 'Top Level Collection for Example Organization',
      'description' => 'Description of Collection',
      'attribution' => 'Provided by Example Organization'
    );
    $collection = Collection::fromArray($data);
    $this->assertEmpty($collection->getManifests());
  }

  public function testGetLabelFromData()
  {
    $data = array(
      '@id' => 'http://example.org/iiif/collection/top',
      'label' => 'Top Level Collection for Example Organization',
      'description' => 'Description of Collection',
      'attribution' => 'Provided by Example Organization',
      'manifests' => []
    );
    $collection = Collection::fromArray($data);
    $this->assertEquals('Top Level Collection for Example Organization', $collection->getLabel());
  }

  public function testGetLabelFromDataWithArray()
  {
    $label = array('@value' => 'Top Level Collection for Example Organization');
    $data = array(
      '@id' => 'http://example.org/iiif/collection/top',
      'label' => $label,
      'description' => 'Description of Collection',
      'attribution' => 'Provided by Example Organization',
      'manifests' => []
    );
    $collection = Collection::fromArray($data);
    $this->assertEquals('Top Level Collection for Example Organization', $collection->getLabel());
  }

  public function testGetLabelFromDataWithNestedArray()
  {
    $label = array(array('@value' => 'Top Level Collection for Example Organization'));
    $data = array(
      '@id' => 'http://example.org/iiif/collection/top',
      'label' => $label,
      'description' => 'Description of Collection',
      'attribution' => 'Provided by Example Organization',
      'manifests' => []
    );
    $collection = Collection::fromArray($data);
    $this->assertEquals('Top Level Collection for Example Organization', $collection->getLabel());
  }

  public function testGetId()
  {
    $this->assertEquals('http://example.org/iiif/collection/top', self::$collection->getId());
  }
}
