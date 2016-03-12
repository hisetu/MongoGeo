<?php

use hisetu\MongoGeo\MongoGeo;

include "../vendor/autoload.php";

class MongoGeoTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $mongoGeo = new MongoGeo(new MongoClient(), 'geo', 'loc');
        $mongoGeo->createPolygon('桃源國際機場二航廈入境', 'airport', [
            [
                [121.231534, 25.076921],
                [121.232822, 25.077893],
                [121.233755, 25.076902],
                [121.232457, 25.075901],
                [121.231534, 25.076921]
            ]
        ]);

        $this->assertTrue(count($mongoGeo->near(25.081156, 121.237990)) == 0);
        $this->assertTrue(count($mongoGeo->near(25.077033, 121.233769)) == 0);
        $this->assertTrue(count($mongoGeo->near(25.076026, 121.233204)) == 0);

        $this->assertTrue(count($mongoGeo->near(25.076292, 121.232584)) > 0);
        $this->assertTrue(count($mongoGeo->near(25.076345, 121.233023)) > 0);
        $this->assertTrue(count($mongoGeo->near(25.077148, 121.232319)) > 0);
        $this->assertTrue(count($mongoGeo->near(25.077551, 121.232668)) > 0);
    }
}