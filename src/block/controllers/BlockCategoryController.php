<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\block\controllers;


use Exception;
use YiiConfigureManage\block\interfaces\IBlockCategoryService;
use YiiConfigureManage\block\models\BlockCategory;
use YiiConfigureManage\block\services\BlockCategoryService;
use YiiHelper\abstracts\RestController;

/**
 * 控制器: 区块类型管理
 *
 * Class BlockCategoryController
 * @package YiiConfigureManage\block\controllers
 *
 * @property-read IBlockCategoryService $service
 */
class BlockCategoryController extends RestController
{
    public $serviceInterface = IBlockCategoryService::class;
    public $serviceClass     = BlockCategoryService::class;

    /**
     * 区块类型map
     *
     * @param array|null $params
     * @return array
     */
    public function actionTypeMap(): array
    {
        // 业务处理
        $res = $this->service->typeMap();
        // 渲染结果
        return $this->success($res, '区块类型列表');
    }

    /**
     * 图片类型列表
     *
     * @return array
     */
    public function actionImageTypes(): array
    {
        // 业务处理
        $res = $this->service->imageTypes();
        // 渲染结果
        return $this->success($res, '图片类型列表');
    }

    /**
     * 链接类型列表
     *
     * @return array
     */
    public function actionLinkTypes(): array
    {
        // 业务处理
        $res = $this->service->linkTypes();
        // 渲染结果
        return $this->success($res, '链接类型列表');
    }

    /**
     * 支持选项的类型列表
     *
     * @return array
     */
    public function actionListTypes(): array
    {
        // 业务处理
        $res = $this->service->listTypes();
        // 渲染结果
        return $this->success($res, '支持选项的类型列表');
    }

    /**
     * 区块类型列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'string', 'label' => '引用标识'],
            ['name', 'string', 'label' => '区块名称'],
            ['is_open', 'boolean', 'label' => '是否开放'],
            ['type', 'in', 'range' => array_keys(BlockCategory::types()), 'label' => '类型'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
        ], null, true);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '区块类型列表');
    }

    /**
     * 添加区块类型
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['type', 'key', 'name', 'sort_order', 'is_open', 'is_enable'], 'required'],
            ['type', 'in', 'range' => array_keys(BlockCategory::types()), 'label' => '区块类型'],
            ['key', 'unique', 'label' => '引用标识', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '区块名称', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'name'],
            ['description', 'string', 'label' => '区块描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_open', 'boolean', 'label' => '是否开放'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['src', 'string', 'label' => '图片地址'],
            ['content', 'safe', 'label' => '内容或链接'],
        ]);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加区块类型成功');
    }

    /**
     * 编辑区块类型
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数提前获取
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '引用标识', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '区块名称', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'name', 'filter' => ['!=', 'key', $key]],
            ['description', 'string', 'label' => '区块描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_open', 'boolean', 'label' => '是否开放'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['src', 'string', 'label' => '图片地址'],
            ['content', 'safe', 'label' => '内容或链接'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑区块类型成功');
    }

    /**
     * 删除区块类型
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '引用标识', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除区块类型成功');
    }

    /**
     * 查看区块类型详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '引用标识', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '区块类型详情');
    }
}