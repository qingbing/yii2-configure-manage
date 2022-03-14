<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\block\controllers;


use Exception;
use YiiConfigureManage\block\interfaces\IBlockOptionService;
use YiiConfigureManage\block\models\BlockCategory;
use YiiConfigureManage\block\models\BlockOption;
use YiiConfigureManage\block\services\BlockOptionService;
use YiiHelper\abstracts\RestController;

/**
 * 控制器: 区块选项管理
 *
 * Class BlockOptionController
 * @package YiiConfigure\block\backend\controllers
 *
 * @property-read IBlockOptionService $service
 */
class BlockOptionController extends RestController
{
    public $serviceInterface = IBlockOptionService::class;
    public $serviceClass     = BlockOptionService::class;

    /**
     * 区块选项列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '引用标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '区块选项列表');
    }

    /**
     * 添加区块选项
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 数据提前获取
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'label', 'sort_order', 'is_enable'], 'required'],
            ['key', 'exist', 'label' => '引用标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['label', 'unique', 'label' => '选项名称', 'targetClass' => BlockOption::class, 'targetAttribute' => 'label', 'filter' => ['key' => $key]],
            ['link', 'string', 'label' => '链接地址'],
            ['src', 'string', 'label' => '图片地址'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['is_blank', 'boolean', 'label' => '新开窗口'],
            ['description', 'string', 'label' => '选项描述'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加区块选项成功');
    }

    /**
     * 编辑区块选项
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 数据提前获取
        $id  = $this->getParam('id');
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['id', 'key'], 'required'], // 必填做这么少为了table-cell-edit
            ['id', 'exist', 'label' => 'ID', 'targetClass' => BlockOption::class, 'targetAttribute' => 'id'],
            ['key', 'exist', 'label' => '区块标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            [
                'label',
                'unique',
                'label'           => '选项名称',
                'targetClass'     => BlockOption::class,
                'targetAttribute' => 'label',
                'filter'          => [
                    'and',
                    ['key' => $key],
                    ['!=', 'id', $id],
                ]
            ],
            ['link', 'string', 'label' => '链接地址'],
            ['src', 'string', 'label' => '图片地址'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['is_blank', 'boolean', 'label' => '新开窗口'],
            ['description', 'string', 'label' => '选项描述'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑区块选项成功');
    }

    /**
     * 删除区块选项
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '区块标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => BlockOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除区块选项成功');
    }

    /**
     * 查看区块选项详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '区块标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => BlockOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '选项详情');
    }

    /**
     * 刷新选项顺序
     *
     * @return array
     * @throws Exception
     */
    public function actionRefreshOrder()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '区块标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->refreshOrder($params);
        // 渲染结果
        return $this->success($res, '刷新选项顺序成功');
    }

    /**
     * 选项顺序上移
     *
     * @return array
     * @throws Exception
     */
    public function actionOrderUp()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '区块标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => BlockOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->orderUp($params);
        // 渲染结果
        return $this->success($res, '选项顺序上移成功');
    }

    /**
     * 选项顺序下移
     *
     * @return array
     * @throws Exception
     */
    public function actionOrderDown()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '区块标记', 'targetClass' => BlockCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => BlockOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->orderDown($params);
        // 渲染结果
        return $this->success($res, '选项顺序下移成功');
    }
}