<?php

namespace app\controller\log;

use app\controller\Base;
use think\facade\Db;
use think\facade\Request;
use think\facade\Log;
use think\Exception;

class UserPayCash extends Base
{
    /**
     * 会员提现管理控制器
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 会员提现列表（简化调试版）
     * 路由: POST /api/user-pay-cash/list
     */
    public function list()
    {
        try {
            // 获取基本参数
            $page = (int)$this->request->post('page', 1);
            $limit = (int)$this->request->post('limit', 10);

            // 简单查询，先不使用复杂的JOIN
            $total = Db::name('common_pay_cash')->count();
            
            $offset = ($page - 1) * $limit;
            $list = Db::name('common_pay_cash')
                ->order('create_time', 'desc')
                ->limit($offset, $limit)
                ->select()
                ->toArray();

            // 简化数据格式处理
            foreach ($list as &$item) {
                $item['createTime'] = $item['create_time'];
                $item['successTime'] = $item['success_time'];
                $item['userName'] = '用户' . $item['u_id']; // 临时简化
                $item['uId'] = $item['u_id'];
                $item['userPhone'] = '';
                $item['userType'] = 2;
                $item['userStatus'] = 1;
                $item['isFictitious'] = 0;
                $item['uBankName'] = $item['u_bank_name'];
                $item['uBackCard'] = $item['u_back_card'];
                $item['uBackUserName'] = $item['u_back_user_name'];
                $item['adminName'] = null;
            }

            $result = [
                'data' => $list,
                'total' => $total,
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($total / $limit),
                'statistics' => [
                    'totalAmount' => '0.00',
                    'totalFee' => '0.00',
                    'totalActual' => '0.00',
                    'pendingCount' => 0,
                    'approvedCount' => 0,
                    'rejectedCount' => 0
                ]
            ];

            return json(['code' => 1, 'message' => '获取成功', 'data' => $result]);

        } catch (Exception $e) {
            Log::error('获取提现列表失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '获取数据失败: ' . $e->getMessage()]);
        }
    }

    /**
     * 获取提现详情
     * 路由: POST /api/user-pay-cash/detail
     */
    public function detail()
    {
        try {
            $id = (int)$this->request->post('id');
            
            if (!$id) {
                return json(['code' => 0, 'message' => '缺少必要参数']);
            }

            $detail = Db::name('common_pay_cash as pc')
                ->leftJoin('common_user as u', 'pc.u_id = u.id')
                ->leftJoin('dianji_user_withdrawal_accounts as wa', 'u.id = wa.user_id AND wa.is_default = 1')
                ->field([
                    'pc.*',
                    'u.user_name',
                    'u.type as user_type',
                    'u.is_fictitious',
                    'u.phone as user_phone',
                    'u.status as user_status',
                    'u.create_time as register_time',
                    'u.money_total_recharge',
                    'u.money_total_withdraw',
                    'wa.account_name',
                    'wa.account_number',
                    'wa.wallet_address',
                    'wa.network_type',
                    'wa.remark_name'
                ])
                ->where('pc.id', $id)
                ->find();

            if (!$detail) {
                return json(['code' => 0, 'message' => '记录不存在']);
            }

            // 处理数据格式
            $detail['createTime'] = $detail['create_time'];
            $detail['successTime'] = $detail['success_time'];
            $detail['userName'] = $detail['user_name'];
            $detail['uId'] = $detail['u_id'];
            $detail['userPhone'] = $detail['user_phone'];
            $detail['userType'] = $detail['user_type'];
            $detail['userStatus'] = $detail['user_status'];
            $detail['isFictitious'] = $detail['is_fictitious'];
            $detail['uBankName'] = $detail['u_bank_name'];
            $detail['uBackCard'] = $detail['u_back_card'];
            $detail['uBackUserName'] = $detail['u_back_user_name'];

            // 获取管理员信息
            if ($detail['admin_uid'] > 0) {
                $admin = Db::name('common_admin')
                    ->where('id', $detail['admin_uid'])
                    ->field('user_name, remarks')
                    ->find();
                $detail['adminName'] = $admin ? ($admin['remarks'] ?: $admin['user_name']) : '';
            } else {
                $detail['adminName'] = null;
            }

            // 扩展信息
            $detail['accountInfo'] = [
                'accountType' => $detail['pay_type'],
                'walletAddress' => $detail['wallet_address'],
                'networkType' => $detail['network_type'],
                'remarkName' => $detail['remark_name']
            ];

            $detail['userInfo'] = [
                'registerTime' => $detail['register_time'],
                'totalRecharge' => $detail['money_total_recharge'],
                'totalWithdraw' => $detail['money_total_withdraw']
            ];

            return json(['code' => 1, 'message' => '获取成功', 'data' => $detail]);

        } catch (Exception $e) {
            Log::error('获取提现详情失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '获取详情失败']);
        }
    }

    /**
     * 审核提现申请
     * 路由: POST /api/user-pay-cash/approve
     */
    public function approve()
    {
        try {
            $id = (int)$this->request->post('id');
            $action = $this->request->post('action'); // approve 或 reject
            $remark = $this->request->post('remark', '');
            $password = $this->request->post('password', '');

            if (!$id || !$action) {
                return json(['code' => 0, 'message' => '缺少必要参数']);
            }

            // 验证操作密码（如果提供）
            if (!empty($password) && !$this->verifyOperationPassword($password)) {
                return json(['code' => 0, 'message' => '操作密码错误']);
            }

            // 获取提现记录
            $withdrawal = Db::name('common_pay_cash')
                ->where('id', $id)
                ->find();

            if (!$withdrawal) {
                return json(['code' => 0, 'message' => '提现记录不存在']);
            }

            if ($withdrawal['status'] != 0) {
                return json(['code' => 0, 'message' => '该记录已处理，无法重复操作']);
            }

            // 开启事务
            Db::startTrans();

            try {
                $adminUid = $this->getAdminId(); // 获取当前管理员ID
                $updateData = [
                    'success_time' => date('Y-m-d H:i:s'),
                    'admin_uid' => $adminUid,
                    'msg' => $remark ?: ($action === 'approve' ? '审核通过' : '审核拒绝')
                ];

                if ($action === 'approve') {
                    // 审核通过
                    $updateData['status'] = 1;
                    
                    // 更新提现记录
                    Db::name('common_pay_cash')
                        ->where('id', $id)
                        ->update($updateData);

                    // 记录资金流水 - 提现完成
                    $this->addMoneyLog(
                        $withdrawal['u_id'],
                        2, // 支出
                        201, // 提现
                        $withdrawal['money_balance'],
                        $withdrawal['money_balance'], // 余额已在申请时扣除
                        0, // 此时不再变动余额
                        $id,
                        "提现审核通过 - 提现ID:{$id} 金额:{$withdrawal['money']}$ 实际到账:{$withdrawal['momey_actual']}$"
                    );

                    $message = '审核通过成功';

                } elseif ($action === 'reject') {
                    // 审核拒绝，需要返还金额到用户账户
                    $updateData['status'] = 2;
                    
                    // 获取用户当前余额
                    $user = Db::name('common_user')
                        ->where('id', $withdrawal['u_id'])
                        ->field('money_balance')
                        ->find();

                    if (!$user) {
                        throw new Exception('用户不存在');
                    }

                    $oldBalance = $user['money_balance'];
                    $newBalance = bcadd($oldBalance, $withdrawal['money'], 2);

                    // 更新用户余额
                    Db::name('common_user')
                        ->where('id', $withdrawal['u_id'])
                        ->update(['money_balance' => $newBalance]);

                    // 更新提现记录
                    Db::name('common_pay_cash')
                        ->where('id', $id)
                        ->update($updateData);

                    // 记录资金流水 - 提现退款
                    $this->addMoneyLog(
                        $withdrawal['u_id'],
                        1, // 收入
                        401, // 提现退款
                        $oldBalance,
                        $newBalance,
                        $withdrawal['money'],
                        $id,
                        "提现审核拒绝退款 - 提现ID:{$id} 退款金额:{$withdrawal['money']}$ 原因:{$remark}"
                    );

                    $message = '已拒绝该提现申请并退还金额';
                } else {
                    throw new Exception('无效的操作类型');
                }

                // 提交事务
                Db::commit();

                return json([
                    'code' => 1,
                    'message' => $message,
                    'data' => [
                        'id' => $id,
                        'status' => $updateData['status'],
                        'successTime' => $updateData['success_time'],
                        'adminUid' => $adminUid,
                        'remark' => $updateData['msg']
                    ]
                ]);

            } catch (Exception $e) {
                Db::rollback();
                throw $e;
            }

        } catch (Exception $e) {
            Log::error('审核提现申请失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 批量审核提现申请
     * 路由: POST /api/user-pay-cash/batch-approve
     */
    public function batchApprove()
    {
        try {
            $ids = $this->request->post('ids', []);
            $action = $this->request->post('action'); // approve 或 reject
            $remark = $this->request->post('remark', '');
            $password = $this->request->post('password', '');

            if (empty($ids) || !$action) {
                return json(['code' => 0, 'message' => '缺少必要参数']);
            }

            // 验证操作密码
            if (!empty($password) && !$this->verifyOperationPassword($password)) {
                return json(['code' => 0, 'message' => '操作密码错误']);
            }

            // 获取待处理的提现记录
            $withdrawals = Db::name('common_pay_cash')
                ->where('id', 'in', $ids)
                ->where('status', 0)
                ->select()
                ->toArray();

            if (empty($withdrawals)) {
                return json(['code' => 0, 'message' => '没有找到待处理的提现记录']);
            }

            $successCount = 0;
            $failedCount = 0;
            $errors = [];

            // 开启事务
            Db::startTrans();

            try {
                foreach ($withdrawals as $withdrawal) {
                    try {
                        $this->processSingleWithdrawal($withdrawal, $action, $remark);
                        $successCount++;
                    } catch (Exception $e) {
                        $failedCount++;
                        $errors[] = "ID {$withdrawal['id']}: " . $e->getMessage();
                    }
                }

                if ($failedCount > 0) {
                    Db::rollback();
                    return json(['code' => 0, 'message' => '批量处理失败: ' . implode('; ', $errors)]);
                } else {
                    Db::commit();
                }

                $statusText = $action === 'approve' ? '通过' : '拒绝';
                return json([
                    'code' => 1,
                    'message' => "已批量{$statusText} {$successCount} 条申请",
                    'data' => [
                        'successCount' => $successCount,
                        'processedIds' => array_column($withdrawals, 'id'),
                        'action' => $action,
                        'remark' => $remark
                    ]
                ]);

            } catch (Exception $e) {
                Db::rollback();
                throw $e;
            }

        } catch (Exception $e) {
            Log::error('批量审核提现申请失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 获取提现统计
     * 路由: POST /api/user-pay-cash/statistics
     */
    public function statistics()
    {
        try {
            $post = $this->request->post();
            $statistics = $this->getStatistics($post);
            return json(['code' => 1, 'message' => '获取成功', 'data' => $statistics]);
        } catch (Exception $e) {
            Log::error('获取提现统计失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '获取统计数据失败']);
        }
    }

    /**
     * 导出提现记录
     * 路由: POST /api/user-pay-cash/export
     */
    public function export()
    {
        try {
            $post = $this->request->post();

            // 这里可以实现Excel导出逻辑
            // 暂时返回模拟数据
            $exportData = [
                'filename' => '会员提现记录_' . date('YmdHis') . '.xlsx',
                'downloadUrl' => '/download/withdrawal_' . date('YmdHis') . '.xlsx',
                'totalRecords' => 0, // 实际导出记录数
                'exportTime' => date('Y-m-d H:i:s')
            ];

            return json(['code' => 1, 'message' => '导出成功', 'data' => $exportData]);
        } catch (Exception $e) {
            Log::error('导出提现记录失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '导出失败']);
        }
    }

    /**
     * 获取用户提现账户
     * 路由: POST /api/user-pay-cash/user-accounts
     */
    public function userAccounts()
    {
        try {
            $userId = (int)$this->request->post('userId');

            if (!$userId) {
                return json(['code' => 0, 'message' => '缺少用户ID参数']);
            }

            $accounts = Db::name('dianji_user_withdrawal_accounts')
                ->where('user_id', $userId)
                ->where('status', 1)
                ->order('is_default desc, id desc')
                ->select()
                ->toArray();

            return json(['code' => 1, 'message' => '获取成功', 'data' => $accounts]);
        } catch (Exception $e) {
            Log::error('获取用户提现账户失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '获取账户信息失败']);
        }
    }

    /**
     * 获取支付方式配置
     * 路由: POST /api/user-pay-cash/payment-methods
     */
    public function paymentMethods()
    {
        try {
            // 从系统配置或数据库获取支付方式配置
            $methods = [
                [
                    'type' => 'usdt',
                    'name' => 'USDT',
                    'icon' => '/images/usdt.png',
                    'enabled' => true,
                    'feeRate' => 2.0,
                    'minAmount' => 10,
                    'maxAmount' => 50000,
                    'networks' => ['TRC20', 'ERC20']
                ],
                [
                    'type' => 'aba',
                    'name' => 'ABA银行',
                    'icon' => '/images/aba.png',
                    'enabled' => true,
                    'feeRate' => 1.5,
                    'minAmount' => 20,
                    'maxAmount' => 100000
                ],
                [
                    'type' => 'huiwang',
                    'name' => '汇旺',
                    'icon' => '/images/huiwang.png',
                    'enabled' => true,
                    'feeRate' => 2.5,
                    'minAmount' => 50,
                    'maxAmount' => 20000
                ]
            ];

            return json(['code' => 1, 'message' => '获取成功', 'data' => $methods]);
        } catch (Exception $e) {
            Log::error('获取支付方式配置失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '获取配置失败']);
        }
    }

    /**
     * 获取管理员列表
     * 路由: POST /api/user-pay-cash/admin-users
     */
    public function adminUsers()
    {
        try {
            $admins = Db::name('common_admin')
                ->field('id, user_name as username, remarks as nickname, role')
                ->order('id desc')
                ->select()
                ->toArray();

            // 处理管理员数据格式
            foreach ($admins as &$admin) {
                // 如果remarks为空或为'0'，则使用user_name作为nickname
                if (empty($admin['nickname']) || $admin['nickname'] === '0') {
                    $admin['nickname'] = $admin['username'];
                }
            }

            return json(['code' => 1, 'message' => '获取成功', 'data' => $admins]);
        } catch (Exception $e) {
            Log::error('获取管理员列表失败: ' . $e->getMessage());
            return json(['code' => 0, 'message' => '获取管理员列表失败']);
        }
    }

    /**
     * 获取统计数据
     */
    private function getStatistics($conditions = [])
    {
        try {
            $query = Db::name('common_pay_cash as pc')
                ->leftJoin('common_user as u', 'pc.u_id = u.id');

            // 应用筛选条件
            $this->applyConditions($query, $conditions);

            $stats = $query->field([
                'COUNT(*) as total_count',
                'SUM(CASE WHEN pc.status = 0 THEN 1 ELSE 0 END) as pending_count',
                'SUM(CASE WHEN pc.status = 1 THEN 1 ELSE 0 END) as approved_count',
                'SUM(CASE WHEN pc.status = 2 THEN 1 ELSE 0 END) as rejected_count',
                'SUM(pc.money) as total_amount',
                'SUM(pc.money_fee) as total_fee',
                'SUM(pc.momey_actual) as total_actual'
            ])->find();

            return [
                'totalAmount' => number_format($stats['total_amount'] ?: 0, 2),
                'totalFee' => number_format($stats['total_fee'] ?: 0, 2),
                'totalActual' => number_format($stats['total_actual'] ?: 0, 2),
                'pendingCount' => (int)$stats['pending_count'],
                'approvedCount' => (int)$stats['approved_count'],
                'rejectedCount' => (int)$stats['rejected_count']
            ];
        } catch (Exception $e) {
            Log::error('获取统计数据失败: ' . $e->getMessage());
            return [
                'totalAmount' => '0.00',
                'totalFee' => '0.00',
                'totalActual' => '0.00',
                'pendingCount' => 0,
                'approvedCount' => 0,
                'rejectedCount' => 0
            ];
        }
    }

    /**
     * 应用查询条件
     */
    private function applyConditions($query, $conditions)
    {
        if (isset($conditions['status']) && $conditions['status'] !== '') {
            $query->where('pc.status', (int)$conditions['status']);
        }

        if (isset($conditions['start']) && !empty($conditions['start'])) {
            $query->where('pc.create_time', '>=', $conditions['start']);
        }

        if (isset($conditions['end']) && !empty($conditions['end'])) {
            $query->where('pc.create_time', '<=', $conditions['end']);
        }
    }

    /**
     * 处理单个提现申请
     */
    private function processSingleWithdrawal($withdrawal, $action, $remark)
    {
        $adminUid = $this->getAdminId();
        $updateData = [
            'success_time' => date('Y-m-d H:i:s'),
            'admin_uid' => $adminUid,
            'msg' => $remark ?: ($action === 'approve' ? '批量审核通过' : '批量审核拒绝')
        ];

        if ($action === 'approve') {
            $updateData['status'] = 1;
            
            // 更新提现记录
            Db::name('common_pay_cash')
                ->where('id', $withdrawal['id'])
                ->update($updateData);

            // 记录资金流水
            $this->addMoneyLog(
                $withdrawal['u_id'],
                2,
                201,
                $withdrawal['money_balance'],
                $withdrawal['money_balance'],
                0,
                $withdrawal['id'],
                "批量提现审核通过 - 提现ID:{$withdrawal['id']}"
            );

        } elseif ($action === 'reject') {
            $updateData['status'] = 2;
            
            // 获取用户当前余额并返还
            $user = Db::name('common_user')
                ->where('id', $withdrawal['u_id'])
                ->field('money_balance')
                ->find();

            if (!$user) {
                throw new Exception("用户不存在 (ID: {$withdrawal['u_id']})");
            }

            $oldBalance = $user['money_balance'];
            $newBalance = bcadd($oldBalance, $withdrawal['money'], 2);

            // 更新用户余额
            Db::name('common_user')
                ->where('id', $withdrawal['u_id'])
                ->update(['money_balance' => $newBalance]);

            // 更新提现记录
            Db::name('common_pay_cash')
                ->where('id', $withdrawal['id'])
                ->update($updateData);

            // 记录资金流水
            $this->addMoneyLog(
                $withdrawal['u_id'],
                1,
                401,
                $oldBalance,
                $newBalance,
                $withdrawal['money'],
                $withdrawal['id'],
                "批量提现审核拒绝退款 - 提现ID:{$withdrawal['id']}"
            );
        }
    }

    /**
     * 添加资金流水记录
     */
    private function addMoneyLog($uid, $type, $status, $moneyBefore, $moneyEnd, $money, $sourceId, $mark)
    {
        Db::name('common_pay_money_log')->insert([
            'create_time' => date('Y-m-d H:i:s'),
            'type' => $type,
            'status' => $status,
            'money_before' => $moneyBefore,
            'money_end' => $moneyEnd,
            'money' => $money,
            'uid' => $uid,
            'source_id' => $sourceId,
            'market_uid' => 0,
            'mark' => $mark
        ]);
    }

    /**
     * 验证操作密码
     */
    private function verifyOperationPassword($password)
    {
        // 这里实现密码验证逻辑
        // 可以从配置文件或数据库获取正确的密码
        $correctPassword = '123456'; // 示例密码
        return $password === $correctPassword;
    }

    /**
     * 获取当前管理员ID
     */
    private function getAdminId()
    {
        // 这里需要从session或token中获取当前登录的管理员ID
        // 可以从Base控制器中获取，或者从JWT token中解析
        
        // 示例：从session中获取
        // $adminId = session('admin_id');
        // if (!$adminId) {
        //     throw new Exception('未登录或登录已过期');
        // }
        // return $adminId;
        
        // 暂时返回默认值（实际项目中需要修改）
        return 1; // 示例管理员ID
    }
}