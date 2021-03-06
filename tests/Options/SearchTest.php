<?php

namespace Gibbo\Foursquare\ClientTests\Options;

use Gibbo\Foursquare\Client\Entity\Coordinates;
use Gibbo\Foursquare\Client\Options\Search;

/**
 * Tests the search options.
 */
class SearchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test creation with coordinates.
     */
    public function testCreateWithCoordinates()
    {
        $coordinates = new Coordinates(40.0, 50.0);

        $options = Search::createWithCoordinates($coordinates);

        $this->assertInstanceOf(Search::class, $options);
        $this->assertCount(1, $options->toArray());
        $this->assertSame($coordinates, $options->getCoordinates());
    }

    /**
     * Test creation with place.
     */
    public function testCreateWithPlace()
    {
        $options = Search::createWithPlace('Chicago, IL');

        $this->assertInstanceOf(Search::class, $options);
        $this->assertCount(1, $options->toArray());
        $this->assertArrayHasKey('near', $options->toArray());
    }

    /**
     * Test creation with an invalid place.
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider  invalidPlaceProvider
     */
    public function testCreateWithInvalidPlace($place)
    {
        Search::createWithPlace($place);
    }

    /**
     * Provides invalid place names.
     *
     * @return array
     */
    public function invalidPlaceProvider()
    {
        return [
            [null],
            [123],
            [[]],
            ['']
        ];
    }

    /**
     * Test to array.
     *
     * @param Search $options
     * @param array $expected
     *
     * @dataProvider  optionsProvider
     */
    public function testToArray(Search $options, array $expected)
    {
        $actual = $options->toArray();

        ksort($actual);
        ksort($expected);

        $this->assertEquals($actual, $expected);
    }

    /**
     * Provides options.
     *
     * @return array
     */
    public function optionsProvider()
    {
        return [
            [
                Search::createWithPlace('Chicago, IL')->setLimit(1)->setRadius(500),
                [
                    'near'   => 'Chicago, IL',
                    'limit'  => 1,
                    'radius' => 500
                ]
            ],
            [
                Search::createWithCoordinates(new Coordinates(40.12, 50.12))->setLimit(40)->setRadius(10),
                [
                    'll'     => new Coordinates(40.12, 50.12),
                    'limit'  => 40,
                    'radius' => 10
                ]
            ],
            [
                Search::createWithCoordinates(new Coordinates(40.12, 50.12))->setRadius(10),
                [
                    'll'     => new Coordinates(40.12, 50.12),
                    'radius' => 10
                ]
            ]
        ];
    }
}
