<?php
/**
 * Created by PhpStorm.
 * User: codimiracle
 * Date: 18-1-26
 * Time: 下午9:32
 */
namespace CAstore\DAO;

use Deline\DAO\DataAccessObject;

interface CommentInfoDAO extends DataAccessObject
{

    public function queryByContentId($content_id);
}