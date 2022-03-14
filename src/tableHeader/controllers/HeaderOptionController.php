<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\tableHeader\controllers;


use Exception;
use YiiConfigureManage\tableHeader\interfaces\IHeaderOptionService;
use YiiConfigureManage\tableHeader\models\HeaderCategory;
use YiiConfigureManage\tableHeader\models\HeaderOption;
use YiiConfigureManage\tableHeader\services\HeaderOptionService;
use YiiHelper\abstracts\RestController;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Traits\Models\TLabelEnable;

/**
 * 控制器: 表头选项管理
 *
 * Class HeaderOptionController
 * @package YiiConfigureManage\tableHeader\controllers
 *
 * @property-read IHeaderOptionService $service
 */
class HeaderOptionController extends RestController
{
    public $serviceInterface = IHeaderOptionService::class;
    public $serviceClass     = HeaderOptionService::class;

    /**
     * 表头选项列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '表头选项列表');
    }

    /**
     * 添加表头选项
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
            [['key', 'field', 'label', 'sort_order'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
            ['field', 'unique', 'label' => '选项字段', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'field', 'filter' => ['key' => $key]],
            ['label', 'unique', 'label' => '选项名称', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'label', 'filter' => ['key' => $key]],
            ['width', 'string', 'label' => '列宽度'],
            ['default', 'string', 'label' => '默认值'],
            ['component', 'string', 'label' => '组件名'], // 除了程序员（超管），其它人不能修改和查看，因为需要前端代码配合
            ['fixed', 'in', 'label' => '固定方向', 'range' => array_keys(HeaderOption::fixedTypes())],
            ['align', 'in', 'label' => '对齐方式', 'range' => array_keys(HeaderOption::alignTypes())],
            ['is_tooltip', 'boolean', 'label' => '使用tooltip'],
            ['is_editable', 'boolean', 'label' => '表格编辑'],
            ['is_resizable', 'boolean', 'label' => '拖动宽度'],
            ['options', JsonValidator::class, 'label' => '映射关系'],
            ['params', JsonValidator::class, 'label' => '参数内容'],
            ['description', 'string', 'label' => '表头选项描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_required', 'boolean', 'label' => '必填'],
            ['is_default', 'boolean', 'label' => '默认开启'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加表头选项成功');
    }

    /**
     * 编辑表头选项
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
            ['id', 'exist', 'label' => 'ID', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'id'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
            [
                'label',
                'unique',
                'label'           => '选项名称',
                'targetClass'     => HeaderOption::class,
                'targetAttribute' => 'label',
                'filter'          => [
                    'and',
                    ['key' => $key],
                    ['!=', 'id', $id],
                ]
            ],
            ['width', 'string', 'label' => '列宽度'],
            ['default', 'string', 'label' => '默认值'],
            ['component', 'string', 'label' => '组件名'], // 除了程序员（超管），其它人不能修改和查看，因为需要前端代码配合
            ['fixed', 'in', 'label' => '固定方向', 'range' => array_keys(HeaderOption::fixedTypes())],
            ['align', 'in', 'label' => '对齐方式', 'range' => array_keys(HeaderOption::alignTypes())],
            ['is_tooltip', 'in', 'label' => '过长隐藏', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_editable', 'in', 'label' => '表格编辑', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_resizable', 'in', 'label' => '拖动宽度', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['options', JsonValidator::class, 'label' => '映射关系'],
            ['params', JsonValidator::class, 'label' => '参数内容'],
            ['description', 'string', 'label' => '表头选项描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_required', 'boolean', 'label' => '必填'],
            ['is_default', 'boolean', 'label' => '默认开启'],
            ['is_enable', 'boolean', 'label' => '是否开启'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑表头选项成功');
    }

    /**
     * 删除表头选项
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除表头选项成功');
    }

    /**
     * 查看表头选项详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'id'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'id'],
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
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
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
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'id'],
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
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => HeaderCategory::class, 'targetAttribute' => 'key'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => HeaderOption::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->orderDown($params);
        // 渲染结果
        return $this->success($res, '选项顺序下移成功');
    }
}
