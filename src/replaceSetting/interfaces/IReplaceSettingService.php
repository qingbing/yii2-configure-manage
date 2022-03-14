<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\replaceSetting\interfaces;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口类: 替换配置(web，只为编辑内容提供接口输出)
 *
 * Interface IReplaceSettingService
 * @package YiiConfigureManage\replaceSetting\interfaces
 */
interface IReplaceSettingService extends ICurdService
{
    /**
     * 开放状态的替换配置做成选项
     *
     * @return array
     */
    public function options(): array;

    /**
     * 开放状态的替换配置设置成默认内容
     *
     * @param array $params
     * @return bool
     */
    public function setDefault(array $params = []): bool;

    /**
     * 保存替换配置内容
     *
     * @param array $params
     * @return bool
     */
    public function saveContent(array $params = []): bool;

    /**
     * 替换配置详情
     *
     * @param array $params
     * @return array
     */
    public function detail(array $params = []): array;
}