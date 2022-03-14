<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\replaceSetting\services;


use Yii;
use YiiConfigureManage\replaceSetting\interfaces\IReplaceSettingService;
use YiiConfigureManage\replaceSetting\models\ReplaceSetting;
use YiiHelper\abstracts\SuperService;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\ForbiddenHttpException;
use Zf\Helper\Exceptions\UnsupportedException;

/**
 * 服务类: 替换配置(web，只为编辑内容提供接口输出)
 *
 * Class ReplaceSettingService
 * @package YiiConfigureManage\replaceSetting\services
 */
class ReplaceSettingService extends SuperService implements IReplaceSettingService
{
    /**
     * 项目列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = ReplaceSetting::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, 'is_open');
        // like 查询
        $this->likeWhere($query, $params, ['code', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 仅供超管使用: 添加替换配置
     *
     * @param array $params
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $this->superRequired();
        $model = Yii::createObject(ReplaceSetting::class);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 仅供超管使用: 编辑替换配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $this->superRequired();
        $model = $this->getModel($params);
        unset($params['code']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除替换配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        throw new UnsupportedException("该功能未开通，建议通过SQL实现");
        //$this->superRequired();
        //return $this->getModel($params)->delete();
    }

    /**
     * 仅供超管使用: 查看详情
     *
     * @param array $params
     * @return mixed|ReplaceSetting
     * @throws BusinessException
     * @throws ForbiddenHttpException
     */
    public function view(array $params)
    {
        $this->superRequired();
        return $this->getModel($params);
    }

    /**
     * 替换配置设置成默认内容
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function setDefault(array $params = []): bool
    {
        $model = $this->getModel($params);
        $model->content = NULL;
        return $model->saveOrException();
    }

    /**
     * 替换配置做成选项卡
     *
     * @return array
     */
    public function options(): array
    {
        $query = ReplaceSetting::find()
            ->select([
                "code",
                "name",
            ])
            ->orderBy('sort_order ASC');
        return $query->asArray()
            ->all();
    }

    /**
     * 保存替换配置内容
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function saveContent(array $params = []): bool
    {
        $model = $this->getModel($params);
        $model->content = $params['content'];
        return $model->saveOrException();
    }

    /**
     * 替换配置详情
     *
     * @param array $params
     * @return array
     * @throws BusinessException
     */
    public function detail(array $params = []): array
    {
        $model = $this->getModel($params);
        return [
            "code"           => $model->code,
            "name"           => $model->name,
            "description"    => $model->description,
            "content"        => $model->content ?: $model->template,
            "replace_fields" => $model->replace_fields,
        ];
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return ReplaceSetting
     * @throws BusinessException
     */
    protected function getModel(array $params): ReplaceSetting
    {
        $model = ReplaceSetting::findOne([
            'code' => $params['code'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("替换配置不存在");
        }
        return $model;
    }
}