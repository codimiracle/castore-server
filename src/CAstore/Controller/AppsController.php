<?php
namespace CAstore\Controller;

use CAstore\Model\Entity\AppInfo;
use CAstore\Service\AppService;
use CAstore\Validator\AppsAppendingValidator;
use CAstore\Validator\AppsEditingValidator;
use Deline\Component\PageNotFoundException;
use Deline\Controller\AbstractEntityController;
use Deline\Service\FileService;
use Deline\Service\UploadService;
use Deline\Utils\DelineUploadHandler;

class AppsController extends AbstractEntityController
{

    const SUBMIT_ID_APP_APPEND = "apps_append";

    const SUBMIT_ID_APP_EDIT = "app_edit";

    /** @var  AppService */
    private $appService;

    /** @var FileService */
    private $fileService;

    /** @var UploadService */
    private $uploadService;

    public function onControllerStart()
    {
        parent::onControllerStart();
        $this->attachAction("/^\\/Query($|\\/)/", "onAppSearch");
        $this->appService = $this->container->getComponentCenter()->getService("AppService");
        $this->fileService = $this->container->getComponentCenter()->getService("FileService");
        $this->uploadService = $this->container->getComponentCenter()->getService("UploadService");
        $this->commentService = $this->container->getComponentCenter()->getService("CommentService");
    }

    public function onControllerEnd()
    {}

    const POWERPOINT_FIELD = "powerpoint";

    const APP_ICON_FIELD = "icon";

    const APP_PACKAGE_FIELD = "package";

    const IMAGE_MIMETYPE = "image/*";

    const PACKAGE_MIMETYPE = "application/vnd.android.package-archive";

    const ENTITY_DIR = "static/resources/apps";

    public function onEntityAppend()
    {
        global $logger;
        $this->container->getAuthorization()->check("content");
        $this->view->setPageTitle("添加应用");
        if ($this->isSubmit(self::SUBMIT_ID_APP_APPEND)) {
            $message = "";
            // 创建验证器
            $validator = new AppsAppendingValidator();
            $validator->verifyAll();
            if ($validator->isValidity()) { // 是否有效
                                            // 创建 AppInfo 实体
                $appInfo = new AppInfo();
                $appInfo->setTitle($_POST["title"]);
                $appInfo->setName($_POST["name"]);
                $appInfo->setDescription($_POST["description"]);
                $appInfo->setDeveloper($_POST["developer"]);
                $appInfo->setPackage($_POST["package"]);
                $appInfo->setPlatform($_POST["platform"]);
                $appInfo->setVersion($_POST["version"]);
                $this->appService->append($appInfo);
                $appContentId = $this->appService->getLastInsertedId();
                
                // 处理文件上传
                
                $powerpoint_dir = self::ENTITY_DIR . "/powerpoints";
                $icon_dir = self::ENTITY_DIR . "/icons";
                $package_dir = self::ENTITY_DIR . "/packages";
                
                $logger->addDebug("AppsController", array(
                    "upload_image_dir" => $powerpoint_dir
                ));
                if ($this->uploadService->isMimeType(self::APP_PACKAGE_FIELD, self::PACKAGE_MIMETYPE)) {
                    $packageUploadHandler = new DelineUploadHandler(self::APP_PACKAGE_FIELD, $appContentId, array(
                        "upload_dir" => $package_dir,
                        "upload_field_prefix" => "app_"
                    ));
                    $packageUploadHandler->setFileService($this->fileService);
                    $packageUploadHandler->setUploadService($this->uploadService);
                    $successful = $packageUploadHandler->handle();
                    if ($successful) {
                        
                    }
                }
                if ($this->uploadService->isMimeType(self::APP_ICON_FIELD, self::IMAGE_MIMETYPE)) {
                    $iconUploadHandler = new DelineUploadHandler(self::APP_ICON_FIELD, $appContentId, array(
                        "upload_dir" => $icon_dir,
                        "upload_field_prefix" => "app_"
                    ));
                    $iconUploadHandler->setFileService($this->fileService);
                    $iconUploadHandler->setUploadService($this->uploadService);
                    $successful = $iconUploadHandler->handle();
                    if (! $successful) {
                        $message = "应用图标添加失败！";
                    }
                } else {
                    $message = "应用图标格式不正确，请上传正确的图片格式！";
                }
                if ($this->uploadService->isMimeType(self::POWERPOINT_FIELD, self::IMAGE_MIMETYPE)) {
                    $powerpointUploadHandler = new DelineUploadHandler(self::POWERPOINT_FIELD, $appContentId, array(
                        "upload_dir" => $powerpoint_dir,
                        "upload_field_prefix" => "app_"
                    ), true);
                    $powerpointUploadHandler->setFileService($this->fileService);
                    $powerpointUploadHandler->setUploadService($this->uploadService);
                    $successful = $powerpointUploadHandler->handle();
                    if ($successful) {
                        $message .= "添加应用成功！";
                        $this->view->setPageName("system.info");
                        $this->view->setMessage("info", $message);
                        return;
                    } else {
                        $message .= "添加幻灯片时发生错误, 部分文件没有上传成功！";
                    }
                } else {
                    $message .= "幻灯片图片格式不正确，请上传正确的图片格式！";
                }
            } else {
                $message = $validator->getResultMessage();
            }
            $this->view->setPageName("system.info");
            $this->view->setMessage("error", $message);
            return;
        } else {
            $this->view->setPageName("apps.append");
        }
    }

    public function onAppSearch()
    {
        $this->view->setPageName("apps.search");
        $this->view->setPageName("应用搜索");
    }

    public function onEntityPagerCount()
    {
        
    }

    public function onEntityPagerList()
    {
        $this->view->setPageTitle("应用");
        $this->view->setPageName("apps.main");
        $pagerNumber = $this->getPagerNumber();
        if ($pagerNumber < 1) {
            $pagerNumber = 1;
        }
        $this->view->setData("applications", $this->appService->queryWithPagerNumber($pagerNumber));
    }

    public function onEntityEdit()
    {
        $this->container->getAuthorization()->check("content");
        // EntityId
        $id = $this->getEntityId();
        /** @var AppInfo $entity */
        $entity = $this->appService->queryById($id);
        if ($entity) {
            $this->view->setPageTitle("编辑应用 - " . $entity->getName());
            if ($this->isSubmit(self::SUBMIT_ID_APP_EDIT)) {
                $validator = new AppsEditingValidator();
                $message = null;
                if ($validator->isValidatity()) {
                    $entity->setName($_POST["name"]);
                    $entity->setTitle($_POST["title"]);
                    $entity->setPackage($_POST["package"]);
                    $entity->setDeveloper($_POST["developer"]);
                    $entity->setDescription($_POST["description"]);
                    $entity->setPlatform($_POST["platform"]);
                    $entity->setVersion($_POST["version"]);
                    $this->appService->edit($entity);
                    $message = "更新 App 信息成功！";
                } else {
                    $message = $validator->getResultMessage();
                }
                $this->view->setPageName("system.info");
                $this->view->setMessage("error", $message);
            } else {
                $this->view->setPageName("apps.edit");
                $this->view->setData("app_info", $entity);
            }
        } else {
            throw new PageNotFoundException("无法找到 ID 为\"" . $id . "\"的 APP 实体进行编辑操作！");
        }
    }

    public function onEntityDelete()
    {
        $this->container->getAuthorization()->check("content");
        $id = $this->getEntityId();
        $entity = $this->appService->queryById($id);
        if ($entity) {}
    }

    public function onEntityDetails()
    {
        $id = $this->getEntityId();
        if ($id != - 1) {
            /** @var AppInfo $entity */
            $entity = $this->appService->queryById($id);
            
            if ($entity) {
                $powerpoints = $this->fileService->queryByTargetIdWithField($entity->getContentId(), "app_" . self::POWERPOINT_FIELD);
                $icon = $this->fileService->queryByTargetIdWithField($entity->getContentId(), "app_" . self::APP_ICON_FIELD);
                /** @var $commentService CommentService */
                $commentService = $this->getContainer()
                    ->getComponentCenter()
                    ->getService("CommentService");
                $comments = $commentService->queryByTargetIdWithPageNumber($entity->getContentId(), 1);
                $this->view->setPageTitle($entity->getTitle());
                $this->view->setPageName("apps.details");
                $this->view->setData("appInfo", $entity);
                $this->view->setData("appIcon", $icon[0]);
                $this->view->setData("appPowerpoints", $powerpoints);
                $this->view->setData("appComments", $comments);
                return;
            }
        }
        throw new PageNotFoundException("Id 为\"" . $id . "\"的 App 实体并不存在！");
    }
    public function onEntitySearchPagerList()
    {
        $this->view->setPageTitle("搜索应用");
        $this->view->setPageName("app.search");
        $pagerNumber = $this->getSearchPagerNumber();
        $this->view->setData("results", $this->appService->queryByKeywordWithPagerNumber($this->getSearchingKeyword(), $pagerNumber));
    }

    public function onEntitySearchPagerCount()
    {
        
    }

    public function onEntitySearch()
    {
        $this->onEntitySearchPagerList();
    }

}
