<?php

namespace CAstore\Utils;

use Deline\Component\MySQLDataSource;
use PHPUnit\Framework\TestCase;

class FiveStarTest extends TestCase
{
    private $dataSource;
    private $fiveStar;
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $database = array();
        $database["database_host"] = "localhost";
        $database["database_name"] = "test";
        $database["database_username"] = "root";
        $database["database_password"] = "Codimiracle855866";
        $this->dataSource = new MySQLDataSource($database);
        $this->dataSource->getConnection()->exec("TRUNCATE mark;");
        $this->fiveStar = new FiveStar();
        $this->fiveStar->setDataSource($this->dataSource);
    }

    public function testMark() {
        $this->fiveStar->mark(1,1,2.3);
        self::assertTrue(true);
    }

    public function testStars() {
        $this->testMark();
        $stars = $this->fiveStar->stars(1);
        self::assertGreaterThan(2.25, $stars);
        self::assertLessThanOrEqual(2.3, $stars);
    }
    protected function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->dataSource->getConnection()->exec("TRUNCATE mark;");
    }
}
