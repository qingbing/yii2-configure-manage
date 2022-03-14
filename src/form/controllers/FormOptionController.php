<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\form\controllers;


use Exception;
use YiiConfigureManage\form\interfaces\IFormOptionService;
use YiiConfigureManage\form\models\FormCategory;
use YiiConfigureManage\form\models\FormOption;
use YiiConfigureManage\form\services\FormOptionService;
use YiiHelper\abstracts\RestController;
use YiiHelper\validators\JsonValidator;

/**
 * 控制器: 表单选项管理
 *
 * Class FormOptionController
 * @package YiiConfigureManage\form\controllers
 *
 * @property-read IFormOptionService $service
 */
class FormOptionController extends RestController
{
    public $serviceInterface = IFormOptionService::class;
    public $serviceClass     = FormOptionService::class;

    /**
     * 表单选项列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '表单选项列表');
    }

    /**
     * 添加表单选项
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
            [['key', 'field', 'label', 'input_type', 'sort_order', 'is_required', 'is_enable'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['field', 'unique', 'label' => '选项字段', 'targetClass' => FormOption::class, 'targetAttribute' => 'field', 'filter' => ['key' => $key]],
            ['label', 'unique', 'label' => '选项名称', 'targetClass' => FormOption::class, 'targetAttribute' => 'label', 'filter' => ['key' => $key]],
            ['input_type', 'in', 'label' => '对齐方式', 'range' => array_keys(FormOption::inputTypes())],
            ['default', 'string', 'label' => '默认值'],
            ['description', 'string', 'label' => '表单选项描述'],
            ['required_msg', 'string', 'label' => '必填提示'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_required', 'boolean', 'label' => '必填'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['exts', JsonValidator::class, 'label' => '扩展参数'],
            ['rules', JsonValidator::class, 'label' => '验证规则'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加表单选项成功');
    }

    /**
     * 编辑表单选项
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
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FormOption::class, 'targetAttribute' => 'id'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            [
                'label',
                'unique',
                'label'           => '选项名称',
                'targetClass'     => FormOption::class,
                'targetAttribute' => 'label',
                'filter'          => [
                    'and',
                    ['key' => $key],
                    ['!=', 'id', $id],
                ]
            ],
            ['default', 'string', 'label' => '默认值'],
            ['description', 'string', 'label' => '表单选项描述'],
            ['required_msg', 'string', 'label' => '必填提示'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_required', 'boolean', 'label' => '必填'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
            ['exts', JsonValidator::class, 'label' => '扩展参数'],
            ['rules', JsonValidator::class, 'label' => '验证规则'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑表单选项成功');
    }

    /**
     * 删除表单选项
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FormOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除表单选项成功');
    }

    /**
     * 查看表单选项详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FormOption::class, 'targetAttribute' => 'id'],
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
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
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
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FormOption::class, 'targetAttribute' => 'id'],
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
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => FormOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->orderDown($params);
        // 渲染结果
        return $this->success($res, '选项顺序下移成功');
    }
}