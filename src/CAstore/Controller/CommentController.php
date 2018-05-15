<?php
namespace CAstore\Controller;

use CAstore\Model\Entity\CommentInfo;
use CAstore\Service\CommentService;
use CAstore\Service\UserService;
use Deline\Controller\AbstractEntityController;


class CommentController extends AbstractEntityController
{

    /** @var UserService */
    private $userService;

    /** @var CommentService */
    private $commentService;

    public function onControllerStart()
    {
        parent::onControllerStart();
        $this->userService = $this->getContainer()
            ->getComponentCenter()
            ->getService("UserService");
            $this->commentService = $this->getContainer()
            ->getComponentCenter()
            ->getService("CommentService");
    }

    public function onEntityDelete()
    {}

    public function onEntityAppend()
    {
        $this->getContainer()
            ->getAuthorization()
            ->check("user");
        $commentInfo = new CommentInfo();
        $commentInfo->setTitle($_POST["title"]);
        $commentInfo->setContent($_POST["content"]);
        $commentInfo->setTargetId($_POST["aid"]);
        $commentInfo->setUserId($this->userService->getUserInfo()
            ->getId());
        $this->commentService->append($commentInfo);
        $this->view->setMessage("info", "评论成功！");
        $this->view->setPageName("system.info");
    }

    public function onEntityDetails()
    {}

    public function onEntityEdit()
    {}

    public function onControllerEnd()
    {}
    public function onEntityCount() {
        
    }
    
    public function onEntityList() {
        $this->onEntityPagerList();
    }
    
    public function onEntityPagerCount() {
        
    }
    
    public function onEntityPagerList() {
        $pn = $this->getPagerNumber();
        $this->view->setPageName("system.info");
        if ($pn < 1) {
            $this->view->setMessage("warning", "页号必须从 1 开始！");
            return;
        }
        $this->view->setMessage("info", "The page is not visible.");
        $comments = $this->commentService->queryByTargetIdWithPageNumber(2, $pn);
        $this->view->setData("comments", $comments);
    }
}

