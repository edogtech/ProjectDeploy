<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
// error_reporting(0);//关闭报错
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();


//看看是不是 有人 调用了本接口
/* if($_SERVER['SERVER_ADDR'] === '127.0.0.1'){//本地环境
	$baseUrl = 'http://baiduyun/www_andlisoft/e_chongdian/www3.2.3/';
}else{//服务器环境
	$baseUrl = 'http://www.e-chongdian.com/';
}
file_get_contents($baseUrl.'index.php/APIes/OnlinePay/pay?a=mmmmmmmmmmmmmmm&out_trade_no='.$_POST['out_trade_no']); */

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];
	
	//将 支付宝 传值，转发给 TP框架接口
	if($_SERVER['SERVER_ADDR'] === '127.0.0.1'){//本地环境
		$baseUrl = 'http://local.zuche.com/';
	}else{//服务器环境
		//$baseUrl = 'http://121.42.53.24/';
		$baseUrl = 'http://192.168.1.100/';
	}
	
	substr($out_trade_no,0,3)=='EZC'?$flag=1:$flag=0;
	if($flag){//结算
		file_get_contents($baseUrl.'newapp/index.php/APIes/AliBalance/pay?out_trade_no='.$out_trade_no.'&trade_no='.$trade_no.'&trade_status='.$trade_status);
	}else{//保障金
		file_get_contents($baseUrl.'newapp/index.php/APIes/AliPay/pay?out_trade_no='.$out_trade_no.'&trade_no='.$trade_no.'&trade_status='.$trade_status);
	}

    if($_POST['trade_status'] == 'TRADE_FINISHED') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//该种交易状态只在两种情况下出现
		//1、开通了普通即时到账，买家付款成功后。
		//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("check failed!");
}
?>
