<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\form\services;


use YiiConfigureManage\form\interfaces\IFormSettingService;
use YiiConfigureManage\form\logic\FormSettingLogic;
use YiiHelper\abstracts\Service;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务: 配置表单管理
 *
 * Class FormSettingService
 * @package YiiConfigureManage\form\services
 */
class FormSettingService extends Service implements IFormSettingService
{
    /**
     * 获取配置表单选项
     *
     * @param array $params
     * @return bool|mixed|string|null
     * @throws BusinessException
     */
    public function get(array $params)
    {
        return FormSettingLogic::getInstance($params['key'])->get();
    }

    /**
     * 保存配置表单数据
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function save(array $params): bool
    {
        return FormSettingLogic::getInstance($params['key'])->save($params);
    }
}