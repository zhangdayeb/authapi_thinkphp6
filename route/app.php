<?php
use think\facade\Route;

/**
 * ====================================================================
 * 游戏后台管理系统路由配置
 * ====================================================================
 */

// ====================================================================
// 登录认证模块
// ====================================================================
Route::rule('login/index$', '/login.Login/index');         // 管理员登录页面
Route::rule('login/captcha$', '/login.Login/captcha');     // 验证码生成
Route::rule('login/captcha_check$', 'admin/Login/captcha_check'); // 验证码校验
Route::rule('login/agent$', '/login.agentLogin/index');    // 代理商登录页面

// ====================================================================
// 管理员信息模块
// ====================================================================
// 获取当前登录管理员信息（支持第三方登录）
Route::rule('admin/info$', '/auth.Action/curl_user_info'); 

// ====================================================================
// 权限控制模块
// ====================================================================
Route::rule('action/list$', '/auth.Action/index');   // 权限控制列表
Route::rule('action/add$', '/auth.Action/add');      // 添加权限控制
Route::rule('action/edit$', '/auth.Action/edit');    // 编辑权限控制
Route::rule('action/del$', '/auth.Action/del');      // 删除权限控制
Route::rule('action/status$', '/auth.Action/status'); // 权限状态管理

// ====================================================================
// 分支权限管理模块
// ====================================================================
Route::rule('auth/action$', '/auth.BranchAuth/action_list');     // 控制器权限列表
Route::rule('auth/action_edit$', '/auth.BranchAuth/action_edit'); // 控制器权限编辑
Route::rule('auth/menu$', '/auth.BranchAuth/menu_list');         // 菜单权限列表
Route::rule('auth/menu_edit$', '/auth.BranchAuth/menu_edit');    // 菜单权限编辑

// ====================================================================
// 菜单管理模块
// ====================================================================
Route::rule('menu/list$', '/auth.Menu/index');    // 后台菜单列表
Route::rule('menu/add$', '/auth.Menu/add');       // 添加后台菜单
Route::rule('menu/edit$', '/auth.Menu/edit');     // 编辑后台菜单
Route::rule('menu/detail$', '/auth.Menu/detail'); // 菜单详情查看
Route::rule('menu/del$', '/auth.Menu/del');       // 删除后台菜单
Route::rule('menu/column$', '/auth.Menu/lists');  // 菜单表单列表
Route::rule('menu/status$', '/auth.Menu/status'); // 菜单状态管理

// ====================================================================
// 角色管理模块
// ====================================================================
Route::rule('role/list$', '/auth.Role/index');   // 角色列表
Route::rule('role/add$', '/auth.Role/add');      // 添加角色
Route::rule('role/edit$', '/auth.Role/edit');    // 编辑角色
Route::rule('role/del$', '/auth.Role/del');      // 删除角色
Route::rule('role/status$', '/auth.Role/status'); // 角色状态管理

// ====================================================================
// 角色菜单关联模块
// ====================================================================
Route::rule('role_menu/list$', '/auth.RoleMenu/index'); // 角色菜单关联列表
Route::rule('role_menu/add$', '/auth.RoleMenu/add');    // 添加角色菜单关联
Route::rule('role_menu/edit$', '/auth.RoleMenu/edit');  // 编辑角色菜单关联

// ====================================================================
// 角色API权限模块
// ====================================================================
Route::rule('power/list$', '/auth.RolePower/index'); // 角色API接口权限列表
Route::rule('power/add$', '/auth.RolePower/add');    // 添加角色API权限
Route::rule('power/edit$', '/auth.RolePower/edit');  // 编辑角色API权限

// ====================================================================
// 代理关系链模块
// ====================================================================
Route::rule('relevant/list$', '/game.AgentList/index'); // 代理关系链列表

// ====================================================================
// 游戏下注记录模块
// ====================================================================
Route::rule('records/list$', '/game.Records/index');   // 下注记录列表
Route::rule('records/edit$', '/game.Records/edit');    // 编辑下注记录
Route::rule('records/del$', '/game.Records/del');      // 删除下注记录
Route::rule('records/retreat$', '/game.Records/retreat'); // 撤销下注

// ====================================================================
// 游戏管理模块
// ====================================================================
Route::rule('gamename/list$', '/game.GameName/index');   // 游戏列表
Route::rule('gamename/status$', '/game.GameName/status'); // 游戏状态管理
Route::rule('gamename/edit$', '/game.GameName/edit');    // 编辑游戏
Route::rule('gamename/add$', '/game.GameName/add');      // 添加游戏
Route::rule('gamename/del$', '/game.GameName/del');      // 删除游戏

// ====================================================================
// 游戏多语言管理模块
// ====================================================================
Route::rule('gamelang/list$', '/game.GameName/game_lang_list'); // 游戏多语言列表
Route::rule('gamelang/edit$', '/game.GameName/game_lang_edit'); // 编辑游戏多语言

// ====================================================================
// 游戏统计模块
// ====================================================================
Route::rule('profit/list$', '/game.Profit/index');     // 盈亏排行榜
Route::rule('usersort/list$', '/game.UserSort/index'); // 用户排行榜

// ====================================================================
// 游戏赔率管理模块
// ====================================================================
Route::rule('gameodds/list$', '/game.Odds/index'); // 游戏赔率列表
Route::rule('gameodds/edit$', '/game.Odds/edit');  // 编辑游戏赔率
Route::rule('gameodds/add$', '/game.Odds/add');    // 添加游戏赔率
Route::rule('gameodds/del$', '/game.Odds/del');    // 删除游戏赔率
Route::rule('gameodds/game$', '/game.Odds/game');  // 按游戏查看赔率

// ====================================================================
// 系统配置模块
// ====================================================================
Route::rule('config/list$', '/config.SysConfig/index');      // 系统配置列表
Route::rule('config/add$', '/config.SysConfig/add');         // 添加系统配置
Route::rule('config/edit$', '/config.SysConfig/edit');       // 编辑系统配置
Route::rule('config/detail$', '/config.SysConfig/detail');   // 配置详情查看
Route::rule('config/del$', '/config.SysConfig/del');         // 删除系统配置
Route::rule('config/info$', '/config.SysConfig/config_info'); // 获取配置信息
Route::rule('clear/token$', '/config.SysConfig/clear_token'); // 清理用户token
Route::rule('mysql/backups$', '/config.SysConfig/mysql_backups'); // 备份露珠数据表
Route::rule('mysql/records$', '/config.SysConfig/mysql_records'); // 备份下注记录表

// ====================================================================
// 控制面板模块
// ====================================================================
Route::rule('dashboard/all$', '/count.Dashboard/index'); // 控制面板数据统计

// ====================================================================
// 露珠管理模块（游戏结果记录）
// ====================================================================
Route::rule('luzhu/list$', '/desktop.luzhu/index');     // 露珠记录列表
Route::rule('luzhu/add$', '/desktop.luzhu/add');        // 添加露珠记录
Route::rule('luzhu/edit$', '/desktop.luzhu/edit');      // 编辑露珠记录
Route::rule('luzhu/status$', '/desktop.luzhu/status');  // 露珠记录状态管理
Route::rule('luzhu/del$', '/desktop.luzhu/newdel');     // 删除露珠记录
Route::rule('luzhu/retreat$', '/desktop.luzhu/retreat'); // 撤销露珠记录

// ====================================================================
// 露珠打印模块
// ====================================================================
Route::rule('print/start$', '/desktop.PrintLuzhu/printData'); // 开始打印露珠
Route::rule('print/list$', '/desktop.PrintLuzhu/index');      // 打印记录列表

// ====================================================================
// 作废露珠管理模块
// ====================================================================
Route::rule('vold/list$', '/desktop.VoidLuzhu/index');    // 作废露珠列表
Route::rule('vold/retreat$', '/desktop.VoidLuzhu/retreat'); // 撤销作废记录

// ====================================================================
// 游戏台桌管理模块
// ====================================================================
Route::rule('desktop/list$', '/desktop.desktop/index');    // 台桌列表
Route::rule('desktop/add$', '/desktop.desktop/add');       // 添加台桌
Route::rule('desktop/edit$', '/desktop.desktop/edit');     // 编辑台桌
Route::rule('desktop/status$', '/desktop.desktop/status'); // 台桌状态管理
Route::rule('desktop/updatedianji', '/desktop.desktop/updatedianji'); // 更新台桌点击数
Route::rule('desktop/del$', '/desktop.desktop/del');       // 删除台桌
Route::rule('desktop/game$', '/desktop.desktop/game');     // 游戏分类管理
Route::rule('desktop/table$', '/desktop.desktop/table_list'); // 根据游戏类型获取台桌列表
Route::rule('desktop/isxh$', '/desktop.desktop/is_xh');    // 限红功能开关

// ====================================================================
// 台桌多语言管理模块
// ====================================================================
Route::rule('tablelang/list$', '/desktop.desktop/game_lang_list'); // 台桌多语言列表
Route::rule('tablelang/edit$', '/desktop.desktop/game_lang_edit'); // 编辑台桌多语言

// ====================================================================
// 用户管理模块
// ====================================================================
Route::rule('user/is_status$', '/user.User/is_status'); // 用户虚拟账号状态设置
Route::rule('user/list$', '/user.User/index');          // 用户列表
Route::rule('user/agent$', '/user.User/agent');         // 代理商信息管理
Route::rule('user/agentedit$', '/user.User/agentedit'); // 代理商密码修改
Route::rule('user/info$', '/user.User/user_info');      // 指定用户信息查看
Route::rule('money/edit$', '/user.User/money_edit');    // 用户余额修改
Route::rule('xian_hong/edit$', '/user.User/xian_hong'); // 用户限红设置

Route::rule('user/edit$', '/user.User/edit');      // 编辑用户信息
Route::rule('user/add$', '/user.User/add');        // 添加用户
Route::rule('user/del$', '/user.User/del');        // 删除用户
Route::rule('user/detail$', '/user.User/detail');  // 用户详情查看
Route::rule('user/status$', '/user.User/status');  // 用户状态修改

// ====================================================================
// 用户实名认证模块
// ====================================================================
Route::rule('userreal/list$', '/user.RealName/index'); // 用户身份证认证列表

// ====================================================================
// 用户银行卡管理模块
// ====================================================================
Route::rule('pay_bank/list$', '/user.PayBank/index');     // 用户银行卡列表
Route::rule('pay_bank/del$', '/user.PayBank/del');        // 删除银行卡
Route::rule('pay_bank/default$', '/user.PayBank/default'); // 设置默认银行卡
Route::rule('pay_bank/info$', '/user.PayBank/info');      // 银行卡信息查看
Route::rule('pay_bank/edit$', '/user.PayBank/edit');      // 编辑银行卡信息

// ====================================================================
// 用户消费统计模块
// ====================================================================
Route::rule('records/total$', '/count.Records/index'); // 用户消费洗码统计

// ====================================================================
// 系统日志模块
// ====================================================================
Route::rule('money/type$', '/log.MoneyLog/status_type'); // 资金流动类型
Route::rule('login/log$', '/log.LoginLog/index');        // 用户登录日志
Route::rule('money/log$', '/log.MoneyLog/index');        // 资金流动日志
Route::rule('admin/log$', '/log.AdminLog/index');        // 后台操作日志

// ====================================================================
// 提现管理模块
// ====================================================================
Route::rule('pay/list$', '/log.PayCash/index');           // 提现申请列表
Route::rule('xima/list$', '/log.PayCash/xima_list');      // 洗码记录列表
Route::rule('agent_auth/list$', '/log.PayCash/auth_list'); // 代理授权列表
Route::rule('record_money/list$', 'admin/log.PayCash/record_list'); // 下注结算记录列表


// ====================================================================
// 会员提现管理模块
// ====================================================================

// 会员提现记录列表
Route::rule('user-pay-cash/list$', '/log.UserPayCash/list');

// 会员提现详情
Route::rule('user-pay-cash/detail$', '/log.UserPayCash/detail');

// 审核提现申请（单个）
Route::rule('user-pay-cash/approve$', '/log.UserPayCash/approve');

// 批量审核提现申请
Route::rule('user-pay-cash/batch-approve$', '/log.UserPayCash/batchApprove');

// 获取提现统计数据
Route::rule('user-pay-cash/statistics$', '/log.UserPayCash/statistics');

// 导出提现记录
Route::rule('user-pay-cash/export$', '/log.UserPayCash/export');

// 获取用户提现账户
Route::rule('user-pay-cash/user-accounts$', '/log.UserPayCash/userAccounts');

// 获取支付方式配置
Route::rule('user-pay-cash/payment-methods$', '/log.UserPayCash/paymentMethods');

// 获取管理员列表
Route::rule('user-pay-cash/admin-users$', '/log.UserPayCash/adminUsers');

/* 
使用示例：
前端请求地址对应关系：

POST /api/user-pay-cash/list              -> UserPayCash::list()
POST /api/user-pay-cash/detail            -> UserPayCash::detail()
POST /api/user-pay-cash/approve           -> UserPayCash::approve()
POST /api/user-pay-cash/batch-approve     -> UserPayCash::batchApprove()
POST /api/user-pay-cash/statistics        -> UserPayCash::statistics()
POST /api/user-pay-cash/export            -> UserPayCash::export()
POST /api/user-pay-cash/user-accounts     -> UserPayCash::userAccounts()
POST /api/user-pay-cash/payment-methods   -> UserPayCash::paymentMethods()
POST /api/user-pay-cash/admin-users       -> UserPayCash::adminUsers()
*/


// ====================================================================
// 充值管理模块
// ====================================================================
Route::rule('recharge/list$', '/log.PayRecharge/index');   // 充值记录列表
Route::rule('recharge/status$', '/log.PayRecharge/status'); // 确认充值操作

// ====================================================================
// 文件上传模块
// ====================================================================
Route::rule('upload/video$', '/upload.UploadData/video'); // 视频文件上传

// ====================================================================
// 后台管理员模块
// ====================================================================
Route::rule('admin/list$', '/user.Admins/index');    // 后台管理员列表
Route::rule('admin/add$', '/user.Admins/add');       // 添加后台管理员
Route::rule('/$', '/Index/index');                   // 后台首页
Route::rule('admin/edit$', '/user.Admins/edit');     // 编辑后台管理员
Route::rule('admin/detail$', '/user.Admins/detail'); // 管理员信息查看
Route::rule('admin/del$', '/user.Admins/del');       // 删除后台管理员

// ====================================================================
// 市场部等级管理模块
// ====================================================================
Route::rule('market_level/list$', '/user.MarketLevel/index');   // 市场部等级列表
Route::rule('market_level/add$', '/user.MarketLevel/add');      // 添加市场部等级
Route::rule('market_level/edit$', '/user.MarketLevel/edit');    // 编辑市场部等级
Route::rule('market_level/del$', '/user.MarketLevel/del');      // 删除市场部等级
Route::rule('market_level/detail$', '/user.MarketLevel/detail'); // 市场部等级详情

// ====================================================================
// 公告管理模块
// ====================================================================
Route::rule('notice/list$', '/notice.Notice/index');     // 公告列表
Route::rule('notice/add$', '/notice.Notice/add');        // 添加公告
Route::rule('notice/edit$', '/notice.Notice/edit');      // 编辑公告
Route::rule('notice/del$', '/notice.Notice/del');        // 删除公告
Route::rule('notice/detail$', '/notice.Notice/detail');  // 公告详情查看
Route::rule('notice/position$', '/notice.Notice/position'); // 公告位置设置
Route::rule('notice/status$', '/notice.Notice/status');  // 公告上下架管理