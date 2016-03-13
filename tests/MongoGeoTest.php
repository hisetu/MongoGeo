<?php

use hisetu\MongoGeo\MongoGeo;

include "../vendor/autoload.php";

class MongoGeoTest extends PHPUnit_Framework_TestCase
{
    private $mongoGeo;

    protected function setUp()
    {
        $this->mongoGeo = new MongoGeo(new MongoClient(), 'geo', 'loc');
        $this->mongoGeo->createPolygon('桃園國際機場一航廈入境大廳', 'airport', [
            [
                [121.236770, 25.080867],
                [121.238122, 25.081926],
                [121.239012, 25.080993],
                [121.237596, 25.079963],
                [121.236770, 25.080867]
            ]
        ]);
        $this->mongoGeo->createPolygon('桃園國際機場二航廈入境大廳', 'airport', [
            [
                [121.231534, 25.076921],
                [121.232822, 25.077893],
                [121.233755, 25.076902],
                [121.232457, 25.075901],
                [121.231534, 25.076921]
            ]
        ]);
    }

    public function test()
    {
        $this->assertEquals('桃園國際機場一航廈入境大廳', $this->mongoGeo->near(25.081156, 121.237990)[0]['name']);
        $this->assertEquals(0, count($this->mongoGeo->near(25.077033, 121.233769)));
        $this->assertEquals(0, count($this->mongoGeo->near(25.076026, 121.233204)));
        $this->assertEquals('桃園國際機場二航廈入境大廳', $this->mongoGeo->near(25.076292, 121.232584)[0]['name']);
        $this->assertEquals('桃園國際機場二航廈入境大廳', $this->mongoGeo->near(25.076345, 121.233023)[0]['name']);
        $this->assertEquals('桃園國際機場二航廈入境大廳', $this->mongoGeo->near(25.077148, 121.232319)[0]['name']);
        $this->assertEquals('桃園國際機場二航廈入境大廳', $this->mongoGeo->near(25.077551, 121.232668)[0]['name']);
    }

}