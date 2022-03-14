<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\tableHeader\interfaces;

use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口: 表头选项管理
 *
 * Interface IHeaderOptionService
 * @package YiiConfigureManage\tableHeader\interfaces
 */
interface IHeaderOptionService extends ICurdService
{
    /**
     * 刷新表头中选项排序
     *
     * @param array $params
     * @return bool
     */
    public function refreshOrder(array $params): bool;

    /**
     * 上移选项顺序
     *
     * @param array $params
     * @return bool
     */
    public function orderUp(array $params): bool;

    /**
     * 下移选项顺序
     *
     * @param array $params
     * @return bool
     */
    public function orderDown(array $params): bool;
}