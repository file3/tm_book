<?php
require("src/GetData.php");

class GetDataTest extends PHPUnit_Framework_TestCase
{
    public function testGetEmpty()
    {
        $gd = new GetData();
        $this->assertEquals(false, empty($gd));
        $db = $gd->get_db();
        $this->assertEquals(false, empty($db));
        $dbi = $gd->get_dbi();
        $this->assertEquals(false, empty($dbi));
/*      $gd->load();
        $this->assertEquals(false, empty($gd->results));
        $this->assertArrayHasKey("total", $gd->results);
        $this->assertGreaterThan(0, $gd->results["total"]);
*/
    }
}
?>
