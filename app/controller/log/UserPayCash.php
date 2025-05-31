<?php

namespace app\controller\log;

use app\controller\Base;
use app\model\MoneyLog as models;

class UserPayCash extends Base
{
    protected $model;

    /**
     * 会员提现管理控制器
     */
    public function initialize()
    {
        $this->model = new models();
        parent::initialize();
    }

    /**
     * 会员提现列表
     * 路由: POST /api/log/user-pay-cash/list
     */
    public function list()
    {
        // 当前页
        $page = $this->request->post('page', 1);
        // 每页显示数量
        $limit = $this->request->post('limit', 10);
        // 查询搜索条件
        $post = array_filter($this->request->post());

        // 模拟数据 - 会员提现记录
        $mockData = [
            [
                'id' => 1,
                'createTime' => '2025-05-30 22:52:07',
                'successTime' => null,
                'money' => '100.00',
                'moneyBalance' => '10001390.00',
                'moneyFee' => '2.00',
                'momeyActual' => '98.00',
                'msg' => '用户申请提现',
                'uId' => 7,
                'userName' => 'zhangsan',
                'uIp' => '98.159.43.112',
                'uCity' => '',
                'adminUid' => 0,
                'adminName' => null,
                'status' => 0, // 0-待审核
                'payType' => 'usdt',
                'uBankName' => 'USDT-TRC20',
                'uBackCard' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
                'uBackUserName' => 'zhangsan',
                'marketUid' => 0,
                'userType' => 2, // 会员
                'isFictitious' => 0,
                'userPhone' => '13800138000',
                'userStatus' => 1
            ],
            [
                'id' => 2,
                'createTime' => '2025-05-30 22:52:28',
                'successTime' => '2025-05-31 10:30:15',
                'money' => '1000.00',
                'moneyBalance' => '10001290.00',
                'moneyFee' => '20.00',
                'momeyActual' => '980.00',
                'msg' => '审核通过',
                'uId' => 7,
                'userName' => 'zhangsan',
                'uIp' => '98.159.43.112',
                'uCity' => '',
                'adminUid' => 1,
                'adminName' => 'admin',
                'status' => 1, // 已通过
                'payType' => 'usdt',
                'uBankName' => 'USDT-TRC20',
                'uBackCard' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
                'uBackUserName' => 'zhangsan',
                'marketUid' => 0,
                'userType' => 2,
                'isFictitious' => 0,
                'userPhone' => '13800138000',
                'userStatus' => 1
            ],
            [
                'id' => 3,
                'createTime' => '2025-05-31 17:41:13',
                'successTime' => null,
                'money' => '5000.00',
                'moneyBalance' => '518440.00',
                'moneyFee' => '100.00',
                'momeyActual' => '4900.00',
                'msg' => '用户申请提现',
                'uId' => 16,
                'userName' => 'lisi',
                'uIp' => '114.134.191.164',
                'uCity' => '',
                'adminUid' => 0,
                'adminName' => null,
                'status' => 0,
                'payType' => 'huiwang',
                'uBankName' => '汇旺',
                'uBackCard' => '017919380',
                'uBackUserName' => 'Ahua',
                'marketUid' => 0,
                'userType' => 2,
                'isFictitious' => 0,
                'userPhone' => '13900139000',
                'userStatus' => 1
            ],
            [
                'id' => 4,
                'createTime' => '2025-05-31 17:41:56',
                'successTime' => '2025-05-31 18:15:30',
                'money' => '5000.00',
                'moneyBalance' => '513440.00',
                'moneyFee' => '100.00',
                'momeyActual' => '4900.00',
                'msg' => '拒绝提现：账户信息不符',
                'uId' => 16,
                'userName' => 'lisi',
                'uIp' => '114.134.191.164',
                'uCity' => '',
                'adminUid' => 2,
                'adminName' => 'admin2',
                'status' => 2, // 已拒绝
                'payType' => 'huiwang',
                'uBankName' => '汇旺',
                'uBackCard' => '017919380',
                'uBackUserName' => 'Ahua',
                'marketUid' => 0,
                'userType' => 2,
                'isFictitious' => 0,
                'userPhone' => '13900139000',
                'userStatus' => 1
            ],
            [
                'id' => 5,
                'createTime' => '2025-05-31 15:30:45',
                'successTime' => null,
                'money' => '2000.00',
                'moneyBalance' => '8500.00',
                'moneyFee' => '40.00',
                'momeyActual' => '1960.00',
                'msg' => '用户申请提现',
                'uId' => 39,
                'userName' => 'wangwu',
                'uIp' => '192.168.1.100',
                'uCity' => '北京',
                'adminUid' => 0,
                'adminName' => null,
                'status' => 0,
                'payType' => 'aba',
                'uBankName' => 'ABA银行',
                'uBackCard' => '001234567890',
                'uBackUserName' => '王五',
                'marketUid' => 0,
                'userType' => 2,
                'isFictitious' => 0,
                'userPhone' => '13700137000',
                'userStatus' => 1
            ],
            [
                'id' => 6,
                'createTime' => '2025-05-31 14:22:18',
                'successTime' => '2025-05-31 16:45:20',
                'money' => '800.00',
                'moneyBalance' => '5200.00',
                'moneyFee' => '16.00',
                'momeyActual' => '784.00',
                'msg' => '审核通过',
                'uId' => 41,
                'userName' => 'zhaoliu',
                'uIp' => '203.45.67.89',
                'uCity' => '上海',
                'adminUid' => 1,
                'adminName' => 'admin',
                'status' => 1,
                'payType' => 'usdt',
                'uBankName' => 'USDT-ERC20',
                'uBackCard' => '0xAb5801a7D398351b8bE11C439e05C5B3259aeC9B',
                'uBackUserName' => '赵六',
                'marketUid' => 0,
                'userType' => 2,
                'isFictitious' => 0,
                'userPhone' => '13600136000',
                'userStatus' => 1
            ]
        ];

        // 模拟筛选逻辑
        $filteredData = $this->filterMockData($mockData, $post);
        
        // 模拟分页
        $total = count($filteredData);
        $start = ($page - 1) * $limit;
        $pageData = array_slice($filteredData, $start, $limit);

        // 模拟统计数据
        $statistics = $this->getMockStatistics($filteredData);

        $result = [
            'data' => $pageData,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $limit,
            'last_page' => ceil($total / $limit),
            'statistics' => $statistics
        ];

        $this->success($result);
    }

    /**
     * 获取提现详情
     * 路由: POST /api/log/user-pay-cash/detail
     */
    public function detail()
    {
        $id = $this->request->post('id');
        
        if (!$id) {
            $this->error('缺少必要参数');
        }

        // 模拟详情数据
        $mockDetail = [
            'id' => $id,
            'createTime' => '2025-05-30 22:52:07',
            'successTime' => null,
            'money' => '100.00',
            'moneyBalance' => '10001390.00',
            'moneyFee' => '2.00',
            'momeyActual' => '98.00',
            'msg' => '用户申请提现',
            'uId' => 7,
            'userName' => 'zhangsan',
            'uIp' => '98.159.43.112',
            'uCity' => '',
            'adminUid' => 0,
            'adminName' => null,
            'status' => 0,
            'payType' => 'usdt',
            'uBankName' => 'USDT-TRC20',
            'uBackCard' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
            'uBackUserName' => 'zhangsan',
            'marketUid' => 0,
            'userType' => 2,
            'isFictitious' => 0,
            'userPhone' => '13800138000',
            'userStatus' => 1,
            // 扩展信息
            'accountInfo' => [
                'accountType' => 'usdt',
                'walletAddress' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
                'networkType' => 'TRC20',
                'remarkName' => '主钱包'
            ],
            'userInfo' => [
                'registerTime' => '2025-05-30 12:37:58',
                'lastLoginTime' => '2025-05-31 18:30:00',
                'totalRecharge' => '10000000.00',
                'totalWithdraw' => '1100.00'
            ]
        ];

        $this->success($mockDetail);
    }

    /**
     * 审核提现申请
     * 路由: POST /api/log/user-pay-cash/approve
     */
    public function approve()
    {
        $id = $this->request->post('id');
        $action = $this->request->post('action'); // approve 或 reject
        $remark = $this->request->post('remark', '');
        $password = $this->request->post('password', '');

        if (!$id || !$action) {
            $this->error('缺少必要参数');
        }

        // 模拟密码验证（可选）
        if ($password && $password !== '123456') {
            $this->error('操作密码错误');
        }

        // 模拟业务逻辑
        if ($action === 'approve') {
            // 模拟审核通过
            $this->success([
                'message' => '审核通过成功',
                'id' => $id,
                'status' => 1,
                'successTime' => date('Y-m-d H:i:s'),
                'adminUid' => 1,
                'adminName' => 'admin',
                'remark' => $remark
            ]);
        } elseif ($action === 'reject') {
            // 模拟审核拒绝
            $this->success([
                'message' => '已拒绝该提现申请',
                'id' => $id,
                'status' => 2,
                'successTime' => date('Y-m-d H:i:s'),
                'adminUid' => 1,
                'adminName' => 'admin',
                'remark' => $remark
            ]);
        } else {
            $this->error('无效的操作类型');
        }
    }

    /**
     * 批量审核提现申请
     * 路由: POST /api/log/user-pay-cash/batch-approve
     */
    public function batchApprove()
    {
        $ids = $this->request->post('ids', []);
        $action = $this->request->post('action'); // approve 或 reject
        $remark = $this->request->post('remark', '');
        $password = $this->request->post('password', '');

        if (empty($ids) || !$action) {
            $this->error('缺少必要参数');
        }

        // 模拟密码验证
        if ($password && $password !== '123456') {
            $this->error('操作密码错误');
        }

        $successCount = count($ids);
        $statusText = $action === 'approve' ? '通过' : '拒绝';

        $this->success([
            'message' => "已批量{$statusText} {$successCount} 条申请",
            'successCount' => $successCount,
            'processedIds' => $ids,
            'action' => $action,
            'remark' => $remark
        ]);
    }

    /**
     * 获取提现统计
     * 路由: POST /api/log/user-pay-cash/statistics
     */
    public function statistics()
    {
        $post = array_filter($this->request->post());

        // 模拟统计数据
        $mockStatistics = [
            'totalAmount' => '18900.00',
            'totalFee' => '378.00',
            'totalActual' => '18522.00',
            'pendingCount' => 3,
            'approvedCount' => 2,
            'rejectedCount' => 1,
            'todayAmount' => '7100.00',
            'monthAmount' => '18900.00',
            'pendingAmount' => '7100.00',
            'approvedAmount' => '6800.00',
            'rejectedAmount' => '5000.00'
        ];

        $this->success($mockStatistics);
    }

    /**
     * 导出提现记录
     * 路由: POST /api/log/user-pay-cash/export
     */
    public function export()
    {
        $post = array_filter($this->request->post());

        // 模拟导出逻辑
        $exportData = [
            'filename' => '会员提现记录_' . date('YmdHis') . '.xlsx',
            'downloadUrl' => '/download/withdrawal_' . date('YmdHis') . '.xlsx',
            'totalRecords' => 6,
            'exportTime' => date('Y-m-d H:i:s')
        ];

        $this->success($exportData);
    }

    /**
     * 获取用户提现账户
     * 路由: POST /api/log/user-pay-cash/user-accounts
     */
    public function userAccounts()
    {
        $userId = $this->request->post('userId');

        if (!$userId) {
            $this->error('缺少用户ID参数');
        }

        // 模拟用户提现账户数据
        $mockAccounts = [
            [
                'id' => 1,
                'userId' => $userId,
                'accountType' => 'usdt',
                'accountName' => null,
                'walletAddress' => 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE',
                'networkType' => 'TRC20',
                'remarkName' => '主钱包',
                'isDefault' => true,
                'status' => 1
            ],
            [
                'id' => 2,
                'userId' => $userId,
                'accountType' => 'aba',
                'accountName' => '张三',
                'accountNumber' => '001234567890',
                'phoneNumber' => '85512345678',
                'bankBranch' => '金边分行',
                'isDefault' => false,
                'status' => 1
            ],
            [
                'id' => 3,
                'userId' => $userId,
                'accountType' => 'huiwang',
                'accountName' => '张三',
                'accountNumber' => '85512345678',
                'idNumber' => 'P123456789',
                'isDefault' => false,
                'status' => 1
            ]
        ];

        $this->success($mockAccounts);
    }

    /**
     * 获取支付方式配置
     * 路由: POST /api/log/user-pay-cash/payment-methods
     */
    public function paymentMethods()
    {
        // 模拟支付方式配置
        $mockMethods = [
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

        $this->success($mockMethods);
    }

    /**
     * 获取管理员列表
     * 路由: POST /api/log/user-pay-cash/admin-users
     */
    public function adminUsers()
    {
        // 模拟管理员列表
        $mockAdmins = [
            [
                'id' => 1,
                'username' => 'admin',
                'nickname' => '超级管理员',
                'role' => 'super_admin'
            ],
            [
                'id' => 2,
                'username' => 'admin2',
                'nickname' => '财务管理员',
                'role' => 'finance_admin'
            ],
            [
                'id' => 3,
                'username' => 'auditor',
                'nickname' => '审核员',
                'role' => 'auditor'
            ]
        ];

        $this->success($mockAdmins);
    }

    /**
     * 模拟数据筛选
     */
    private function filterMockData($data, $filters)
    {
        return array_filter($data, function($item) use ($filters) {
            // 用户名筛选
            if (isset($filters['username']) && !empty($filters['username'])) {
                if (strpos($item['userName'], $filters['username']) === false) {
                    return false;
                }
            }

            // 用户ID筛选
            if (isset($filters['userId']) && !empty($filters['userId'])) {
                if ($item['uId'] != $filters['userId']) {
                    return false;
                }
            }

            // 状态筛选
            if (isset($filters['status']) && $filters['status'] !== '') {
                if ($item['status'] != $filters['status']) {
                    return false;
                }
            }

            // 支付方式筛选
            if (isset($filters['payType']) && !empty($filters['payType'])) {
                if ($item['payType'] != $filters['payType']) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * 获取模拟统计数据
     */
    private function getMockStatistics($data)
    {
        $totalAmount = array_sum(array_column($data, 'money'));
        $totalFee = array_sum(array_column($data, 'moneyFee'));
        $totalActual = array_sum(array_column($data, 'momeyActual'));

        $pendingCount = count(array_filter($data, function($item) {
            return $item['status'] == 0;
        }));

        $approvedCount = count(array_filter($data, function($item) {
            return $item['status'] == 1;
        }));

        $rejectedCount = count(array_filter($data, function($item) {
            return $item['status'] == 2;
        }));

        return [
            'totalAmount' => number_format($totalAmount, 2),
            'totalFee' => number_format($totalFee, 2),
            'totalActual' => number_format($totalActual, 2),
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount
        ];
    }
}