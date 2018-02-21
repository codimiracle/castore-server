<?php
namespace CAstore\Action;

use CAstore\Operation\AppOperation;
use CAstore\Verifier\AppsAppendVerifier;
use Deline\Action\AbstractEntityAction;
use Deline\Component\ComponentCenter;

class AppsAction extends AbstractEntityAction
{

    const SUBMIT_ID_APP_APPEND = "apps_append";

    /** @var  AppOperation */
    private $appOperation;

    public function onActionStart()
    {
        parent::onActionStart();
        $this->attachAction("/^\\/$/", "onAppRoot");
        $this->appOperation = ComponentCenter::getService($this->container, "AppOperation");
    }

    public function onActionEnd()
    {}

    public function onEntityAppend()
    {
        if ($this->isSubmit(self::SUBMIT_ID_APP_APPEND)) {
            $verifier = new AppsAppendVerifier();
        } else {
            $this->view->setPageTitle("添加应用");
            $this->view->setPageName("apps.append");
        }
    }

    public function onAppRoot()
    {
        $this->view->setPageTitle("应用");
        $this->view->setPageName("apps.main");
    }

    public function onEntityEdit()
    {
        /** @var AppInfo $entity */
        $entity = $this->appOperation->queryById($this->getEntityId());
        if ($entity) {
            $this->view->setPageTitle("编辑应用 - " . $entity->getName());
        } else {
            $this->container->dispatch("/System/PageNotFound");
        }
    }

    public function onEntityDelete()
    {}

    public function onEntityUpdate()
    {}

    public function onEntityDetails()
    {
        $id = $this->getEntityId();
        if ($id != - 1) {
            $entity = $this->appOperation->queryById($id);
            if ($entity) {
                $this->view->setData("app_info", $entity);
            }
        }
        $this->container->dispatch("/System/PageNotFound");
    }
}
