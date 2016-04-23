<?php

namespace Saxulum\Tests\ElasticSearchQueryBuilder\Node;

use Saxulum\ElasticSearchQueryBuilder\Node\ArrayNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ObjectNode;
use Saxulum\ElasticSearchQueryBuilder\Node\ScalarNode;

class ArrayNodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testGetName()
    {
        $node = new ArrayNode('name');

        self::assertSame('name', $node->getName());
    }

    /**
     * @return void
     */
    public function testSerializeWithoutChildren()
    {
        $node = new ArrayNode('name');

        self::assertInstanceOf(\stdClass::class, $node->serialize());

        $serialized = new \stdClass();
        $serialized->name = [];

        self::assertEquals($serialized, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithScalarChild()
    {
        $node = (new ArrayNode('name1'))
            ->addChild(new ScalarNode('name11', 'value11'))
            ->addChild(new ScalarNode('name12', 'value12'))
        ;

        $serialized = new \stdClass();
        $serialized->name1 = [];
        $serialized->name1[0] = new \stdClass();
        $serialized->name1[0]->name11 = 'value11';
        $serialized->name1[1] = new \stdClass();
        $serialized->name1[1]->name12 = 'value12';

        self::assertEquals($serialized, $node->serialize());
    }

    /**
     * @return void
     */
    public function testSerializeWithObjectChild()
    {
        $node = (new ArrayNode('name1'))
            ->addChild(
                (new ObjectNode('name11'))
                    ->addChild(new ScalarNode('name111', 'value111'))
                    ->addChild(new ScalarNode('name112', 'value112'))
            )
            ->addChild(
                (new ObjectNode('name12'))
                    ->addChild(new ScalarNode('name121', 'value121'))
                    ->addChild(new ScalarNode('name122', 'value122'))
            )
        ;

        $serialized = new \stdClass();
        $serialized->name1 = [];
        $serialized->name1[0] = new \stdClass();
        $serialized->name1[0]->name11 = new \stdClass();
        $serialized->name1[0]->name11->name111 = 'value111';
        $serialized->name1[0]->name11->name112 = 'value112';

        $serialized->name1[1] = new \stdClass();
        $serialized->name1[1]->name12 = new \stdClass();
        $serialized->name1[1]->name12->name121 = 'value121';
        $serialized->name1[1]->name12->name122 = 'value122';

        self::assertEquals($serialized, $node->serialize());
    }
}
