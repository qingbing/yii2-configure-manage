<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\block\services;

use Yii;
use yii\db\StaleObjectException;
use YiiConfigureManage\block\interfaces\IBlockCategoryService;
use YiiConfigureManage\block\models\BlockCategory;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\ForbiddenHttpException;

/**
 * 服务: 区块类型管理
 *
 * Class BlockCategoryService
 * @package YiiConfigureManage\block\services
 */
class BlockCategoryService extends Service implements IBlockCategoryService
{
    /**
     * 区块类型列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = BlockCategory::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['type', 'is_open', 'is_enable']);
        // like 查询
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加区块类型
     *
     * @param array $params
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new BlockCategory();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑区块类型
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除区块类型
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function del(array $params): bool
    {
        return $this->getModel($params)->delete();
    }

    /**
     * 查看区块类型详情
     *
     * @param array $params
     * @return mixed|BlockCategory
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return BlockCategory
     * @throws BusinessException
     */
    protected function getModel(array $params): BlockCategory
    {
        $model = BlockCategory::findOne([
            'key' => $params['key'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("区块类型不存在");
        }
        return $model;
    }
}