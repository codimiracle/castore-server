<?php
namespace CAstore\Model\DAO;

use Deline\Model\DAO\DataAccessObject;

interface CommentInfoDAO extends DataAccessObject
{

    public function queryByContentId($content_id);
}