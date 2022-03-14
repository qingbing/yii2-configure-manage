<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\tableHeader\services;


use Exception;
use Yii;
use yii\base\Action;
use YiiConfigureManage\tableHeader\interfaces\IHeaderOptionService;
use YiiConfigureManage\tableHeader\models\HeaderCategory;
use YiiConfigureManage\tableHeader\models\HeaderOption;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\Req;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\ForbiddenHttpException;

/**
 * 逻辑类: 表头选项管理
 *
 * Class HeaderOptionService
 * @package YiiConfigureManage\tableHeader\services
 */
class HeaderOptionService extends Service implements IHeaderOptionService
{
    /**
     * @var HeaderCategory
     */
    protected $category;

    /**
     * 在action前统一执行
     *
     * @param Action|null $action
     * @return bool
     * @throws Exception
     */
    public function beforeAction(Action $action = null)
    {
        $this->category = HeaderCategory::findOne([
            'key' => $action->controller->getParam('key', null),
        ]);
        if (null === $this->category) {
            throw new BusinessException("不存在的表头类型");
        }
        return true;
    }

    /**
     * 表头选项列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        return $this->category->options;
    }

    /**
     * 添加表头选项
     *
     * @param array $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = Yii::createObject(HeaderOption::class);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑表头选项
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id'], $params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除表头选项
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        $model = $this->getModel($params);
        return $model->delete();
    }

    /**
     * 查看表头选项详情
     *
     * @param array $params
     * @return mixed|HeaderOption
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 刷新表单中所有选项排序
     *
     * @param array $params
     * @return bool
     * @throws \Throwable
     */
    public function refreshOrder(array $params): bool
    {
        $options = $this->category->options;
        AppHelper::app()->getDb()->transaction(function () use ($options) {
            $sort_order = 1;
            foreach ($options as $option) {
                $option->sort_order = $sort_order++;
                $option->saveOrException();
            }
        });
        return true;
    }

    /**
     * 上移选项顺序
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     */
    public function orderUp(array $params): bool
    {
        // 当前模型
        $model = $this->getModel($params);
        // 获取上一顺序模型
        $prevModel = $this->getBrother($model, PREV);
        // 交换 sort_order
        return $this->switchSortOrder($model, $prevModel);
    }

    /**
     * 下移选项顺序
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     */
    public function orderDown(array $params): bool
    {
        // 当前模型
        $model = $this->getModel($params);
        // 获取上一顺序模型
        $nextModel = $this->getBrother($model, NEXT);
        // 交换 sort_order
        return $this->switchSortOrder($model, $nextModel);
    }

    /**
     * 交换两个模型的 sort_order
     *
     * @param HeaderOption $sourceModel
     * @param HeaderOption $targetModel
     * @return bool
     * @throws \Throwable
     */
    protected function switchSortOrder(HeaderOption $sourceModel, ?HeaderOption $targetModel)
    {
        AppHelper::app()->getDb()->transaction(function () use ($sourceModel, $targetModel) {
            if (null === $targetModel) {
                return;
            }
            $sort_order              = $sourceModel->sort_order;
            $sourceModel->sort_order = $targetModel->sort_order;
            $sourceModel->saveOrException();

            $targetModel->sort_order = $sort_order;
            $targetModel->saveOrException();
        });
        return true;
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return HeaderOption
     * @throws BusinessException
     */
    protected function getModel(array $params): HeaderOption
    {
        $model = HeaderOption::findOne([
            'id'  => $params['id'] ?? null,
            'key' => $params['key'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("表头选项不存在");
        }
        return $model;
    }

    /**
     * 获取对调的兄弟模型
     *
     * @param HeaderOption $model
     * @param string $type
     * @return array|HeaderOption|null
     */
    protected function getBrother(HeaderOption $model, string $type)
    {
        $differs = [
            PREV => ['oper' => '<', 'sort' => 'sort_order DESC'],
            NEXT => ['oper' => '>', 'sort' => 'sort_order ASC'],
        ];
        return HeaderOption::find()
            ->andWhere(['=', 'key', $model->key])
            ->andWhere([$differs[$type]['oper'], 'sort_order', $model->sort_order])
            ->orderBy($differs[$type]['sort'])
            ->one();
    }
}
