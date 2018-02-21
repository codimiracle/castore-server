<?php
/**
 * Created by PhpStorm.
 * User: codimiracle
 * Date: 18-1-26
 * Time: 下午10:08
 */

namespace CAstore\Utils;

use CAstore\DAO\ICommentInfoDAO;
use CAstore\Entity\CommentInfo;
use Deline\Component\MySQLDataSource;
use PHPUnit\Framework\TestCase;

class ICommentInfoDAOTest extends TestCase
{
    private $commentInfo;
    private $dao;
    private $dataSource;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $database = array();
        $database["database_host"] = "localhost";
        $database["database_name"] = "test";
        $database["database_username"] = "root";
        $database["database_password"] = "Codimiracle855866";
        $this->dao = new ICommentInfoDAO();
        $this->dataSource = new MySQLDataSource($database);
        $this->dataSource->getConnection()->exec("
            TRUNCATE `comment`;
            TRUNCATE `content`;"
        );
        $this->dao->setDataSource($this->dataSource);
        $this->commentInfo = new CommentInfo();
        $this->commentInfo->content = "Hello Comment";
        $this->commentInfo->userId = 2;
        $this->commentInfo->contentId = 2;
    }

    public function testQueryNull() {
        $list = $this->dao->query();
        self::assertEmpty($list);
    }

    public function testQueryByIdNull() {
        $entity = $this->dao->queryById(1);
        self::assertNull($entity);
    }


    public function testInsert() {
        $this->dao->setTarget($this->commentInfo);
        $this->dao->insert();
        $commentInfo = $this->dao->queryById(1);
        self::assertNotNull($commentInfo);
        self::assertEquals($this->commentInfo->content, $commentInfo->getContent());
        self::assertEquals($this->commentInfo->userId, $commentInfo->getUserId());
        self::assertEquals($this->commentInfo->contentId, $commentInfo->getContentId());

    }

    public function testDelete() {
        $this->testInsert();
        $target = $this->dao->queryById(1);
        self::assertNotNull($target);
        $this->dao->setTarget($target);
        $this->dao->delete();
        $result = $this->dao->queryById(1);
        self::assertNull($result);
    }
    public function testUpdate() {
        $this->testInsert();
        $target = $this->dao->queryById(1);
        self::assertNotNull($target);
        $target->setContent("CXXXX");
        $this->dao->setTarget($target);
        $this->dao->update();
        $new = $this->dao->queryById(1);
        self::assertEquals($target->getContent(),$new->getContent());
        self::assertEquals($this->commentInfo->userId, $new->getUserId());
        self::assertEquals($this->commentInfo->contentId, $new->getContentId());
    }

    protected function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        $this->dataSource->getConnection()->exec("
            TRUNCATE `comment`;
            TRUNCATE `content`;"
        );
    }
}
