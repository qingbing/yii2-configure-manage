<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\replaceSetting\controllers;


use Exception;
use YiiConfigureManage\replaceSetting\interfaces\IReplaceSettingService;
use YiiConfigureManage\replaceSetting\models\ReplaceSetting;
use YiiConfigureManage\replaceSetting\services\ReplaceSettingService;
use YiiHelper\abstracts\RestController;
use YiiHelper\validators\JsonValidator;

/**
 * 控制器: 替换配置(web，只为编辑内容提供接口输出)
 *
 * Class ReplaceSettingController
 * @package YiiConfigureManage\replaceSetting\controllers
 *
 * @property-read IReplaceSettingService $service
 */
class ReplaceSettingController extends RestController
{
    public $serviceInterface = IReplaceSettingService::class;
    public $serviceClass     = ReplaceSettingService::class;

    /**
     * 替换配置列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['code', 'string', 'label' => '标识码'],
            ['name', 'string', 'label' => '配置名称'],
            ['is_open', 'boolean', 'label' => '公开状态'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '替换配置列表');
    }

    /**
     * 添加替换配置
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code', 'name', 'description', 'sort_order', 'is_open'], 'required'],
            [['is_open', 'sort_order'], 'integer'],
            ['code', 'unique', 'label' => '标识码', 'targetClass' => ReplaceSetting::class],
            ['name', 'unique', 'label' => '配置名称', 'targetClass' => ReplaceSetting::class],
            ['is_open', 'boolean', 'label' => '公开状态'],
            ['sort_order', 'safe', 'label' => '排序'],
            ['template', 'safe', 'label' => '模板'],
            ['description', 'safe', 'label' => '描述'],
            ['replace_fields', JsonValidator::class, 'label' => '字段集'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '编辑替换配置成功');
    }

    /**
     * 编辑替换配置
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code', 'name', 'description', 'sort_order', 'is_open'], 'required'],
            ['code', 'label' => '标识码', 'exist', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => ['code', 'name']],
            ['name', 'unique', 'label' => '配置名称', 'targetClass' => ReplaceSetting::class, 'filter' => ['!=', 'code', $this->getParam('code')]],
            ['is_open', 'boolean', 'label' => '公开状态'],
            ['replace_fields', JsonValidator::class, 'label' => '字段集'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['template', 'safe', 'label' => '模板'],
            ['content', 'safe', 'label' => '内容'],
            ['description', 'safe', 'label' => '描述'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑替换配置成功');
    }

    /**
     * 替换配置详情
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code'], 'required'],
            ['code', 'exist', 'label' => '标识码', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => 'code'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除替换配置成功');
    }

    /**
     * 替换配置详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code'], 'required'],
            ['code', 'exist', 'label' => '标识码', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => 'code'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '替换配置详情');
    }

    /**
     * 开放状态的替换配置设置成默认内容
     *
     * @return array
     * @throws Exception
     */
    public function actionSetDefault()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code'], 'required'],
            ['code', 'exist', 'label' => '标识码', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => 'code'],
        ]);
        // 业务处理
        $res = $this->service->setDefault($params);
        // 渲染结果
        return $this->success($res, '设置成功');
    }

    /**
     * 启用状态的替换配置做成选项
     *
     * @return array
     * @throws Exception
     */
    public function actionOptions()
    {
        // 业务处理
        $res = $this->service->options();
        // 渲染结果
        return $this->success($res, '替换选项');
    }

    /**
     * 保存替换配置内容
     *
     * @return array
     * @throws Exception
     */
    public function actionSaveContent()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code', 'content'], 'required'],
            ['code', 'exist', 'label' => '标识码', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => 'code'],
            ['content', 'safe', 'label' => '模板'],
        ]);
        // 业务处理
        $res = $this->service->saveContent($params);
        // 渲染结果
        return $this->success($res, '保存替换配置内容成功');
    }

    /**
     * 替换配置详情
     *
     * @return array
     * @throws Exception
     */
    public function actionDetail()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code'], 'required'],
            ['code', 'exist', 'label' => '标识码', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => 'code'],
        ]);
        // 业务处理
        $res = $this->service->detail($params);
        // 渲染结果
        return $this->success($res, '替换配置详情');
    }
}