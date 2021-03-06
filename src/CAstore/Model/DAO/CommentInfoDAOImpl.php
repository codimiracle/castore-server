<?php
namespace CAstore\Model\DAO;

use CAstore\Model\Entity\CommentInfo;
use Deline\Model\DAO\AbstractDAO;

class CommentInfoDAOImpl extends AbstractDAO implements CommentInfoDAO
{

    const INSERT_CONTENT = "INSERT INTO content(title, name, content, createdTime, updatedTime) VALUES (:comment_title, 'comment_name', :comment_content, NOW(), NOW())";

    const INSERT_COMMENT = "INSERT INTO comment(cid, tid, uid) VALUES (:cid, :tid, :uid)";

    const UPDATE_CONTENT = "UPDATE content SET content = :content, updatedTime = NOW() WHERE id = (SELECT cid FROM comment WHERE id = :id)";

    const DELETE_CONTENT = "DELETE FROM content WHERE id = (SELECT cid FROM comment WHERE id = :id)";

    const DELETE_COMMENT = "DELETE FROM comment WHERE id = :id";

    const QUERY = "SELECT * FROM comment_info";

    const QUERY_BY_ID = "SELECT * FROM comment_info WHERE id = :id";

    const QUERY_BY_TARGET_ID = "SELECT * FROM comment_info WHERE targetId = :targetId";

    private $lastInsertedId;
    /**
     *
     * @return CommentInfo
     */
    public function getTarget()
    {
        return parent::getTarget();
    }

    public function queryByTargetId($targetId)
    {
        return $this->getEntities(self::QUERY_BY_TARGET_ID, array(
            ":targetId" => $targetId
        ), CommentInfo::class);
    }
    
    public function getLastInsertedId()
    {
        return $this->lastInsertedId;
    }

    public function insert()
    {
        $connection = $this->getDataSource()->getConnection();
        try {
            $connection->beginTransaction();
            $prepared = $connection->prepare(self::INSERT_CONTENT);
            $prepared->bindValue(":comment_title", $this->getTarget()->getTitle());
            $prepared->bindValue(":comment_content", $this->getTarget()
                ->getContent());
            $prepared->execute();
            $this->lastInsertedId = $connection->lastInsertId(":id");
            $prepared = $connection->prepare(self::INSERT_COMMENT);
            $prepared->bindValue(":cid", $this->lastInsertedId);
            $prepared->bindValue(":tid", $this->getTarget()
                ->getTargetId());
            $prepared->bindValue(":uid", $this->getTarget()
                ->getUserId());
            $prepared->execute();
            $connection->commit();
        } catch (\PDOException $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    public function delete()
    {
        $connection = $this->getDataSource()->getConnection();
        try {
            $connection->beginTransaction();
            $prepared = $connection->prepare(self::DELETE_CONTENT);
            $prepared->bindValue(":id", $this->getTarget()
                ->getId());
            $prepared->execute();
            $prepared = $connection->prepare(self::DELETE_COMMENT);
            $prepared->bindValue(":id", $this->getTarget()
                ->getId());
            $prepared->execute();
            $connection->commit();
        } catch (\PDOException $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    public function update()
    {
        $connection = $this->getDataSource()->getConnection();
        try {
            $connection->beginTransaction();
            $prepared = $connection->prepare(self::UPDATE_CONTENT);
            $prepared->bindValue(":content", $this->getTarget()
                ->getContent());
            $prepared->bindValue(":id", $this->getTarget()
                ->getId());
            $prepared->execute();
            $connection->commit();
        } catch (\PDOException $e) {
            $connection->rollBack();
            throw $e;
        }
    }

    public function query()
    {
        return $this->getEntities(self::QUERY, array(), CommentInfo::class);
    }

    public function queryById($id)
    {
        return $this->getEntity(self::QUERY_BY_ID, array(
            ":id" => $id
        ), CommentInfo::class);
    }

}