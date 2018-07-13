<?php
class YouZiAction extends CommonAction {
	function _initialize() {
		//$this->_inject_check(1);//调用过滤函数
		$this->_checkUser();
		$this->_Admin_checkUser();//后台权限检测
		$this->_Config_name();//调用参数
		header("Content-Type:text/html; charset=utf-8");

	}
	//================================================二级验证
	public function cody(){
		$UrlID  = (int) $_GET['c_id'];
	    if (empty($UrlID)){
            $this->error('二级密码错误1!');
            exit;
        }
        if(!empty($_SESSION['user_pwd2'])){
			$url = __URL__."/codys/Urlsz/$UrlID";
			$this->_boxx($url);
			exit;
		}
		$cody   =  M ('cody');
        $list	=  $cody->where("c_id=$UrlID")->field('c_id')->find();
		if ($list){
			$this->assign('vo',$list);
			$this->display('../Public/cody');
			exit;
		}else{
			$this->error('二级密码错误!');
			exit;
		}
	}
	//====================================二级验证后调转页面
	public function codys(){
		$Urlsz	= $_POST['Urlsz'];
		if(empty($_SESSION['user_pwd2'])){
			$pass	= $_POST['oldpassword'];
			$fck   =  M ('fck');
		    if (!$fck->autoCheckToken($_POST)){
	            $this->error('页面过期请刷新页面!');
	            exit();
	        }
			if (empty($pass)){
				$this->error('二级密码错误!');
				exit();
			}
			$where =  array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['passopen'] = md5($pass);
			$list = $fck->where($where)->field('id')->find();
			if($list == false){
				$this->error('二级密码错误!');
				exit();
			}
			$_SESSION['user_pwd2'] = 1;
		}else{
			$Urlsz = $_GET['Urlsz'];
		}
		switch ($Urlsz){
			case 1;
				$_SESSION['UrlPTPass'] = 'MyssShenShuiPuTao';
				$bUrl = __URL__.'/auditMenber';//审核会员
				$this->_boxx($bUrl);
				break;
			case 2;
				$_SESSION['UrlPTPass'] = 'MyssGuanShuiPuTao';
				$bUrl = __URL__.'/adminMenber';//会员管理
				$this->_boxx($bUrl);
				break;
			case 3;
				$_SESSION['UrlPTPass'] = 'MyssPingGuoCP';
				$bUrl = __URL__.'/setParameter';//参数设置
				$this->_boxx($bUrl);
				break;
			case 4;
				$_SESSION['UrlPTPass'] = 'MyssPingGuo';
				$bUrl = __URL__.'/adminParameter';//比例设置
				$this->_boxx($bUrl);
				break;
			case 5;
				$_SESSION['UrlPTPass'] = 'MyssMiHouTao';
				$bUrl = __URL__.'/adminFinance';//拨出比例
				$this->_boxx($bUrl);
				break;
			case 6;
				$_SESSION['UrlPTPass'] = 'MyssGuanPaoYingTao';
				$bUrl = __URL__.'/adminCurrency';//提现管理
				$this->_boxx($bUrl);
				break;
			case 7;
				$_SESSION['UrlPTPass'] = 'MyssHaMiGua';
//				$bUrl = __URL__.'/adminManageTables';//数据库管理
				$bUrl = __APP__.'/Backup/';//数据库管理
				$this->_boxx($bUrl);
				break;
			case 8;
				$_SESSION['UrlPTPass'] = 'MyssPiPa';
				$bUrl = __URL__.'/adminFinanceTable';//奖金查询
				$this->_boxx($bUrl);
				break;
			case 9;
				$_SESSION['UrlPTPass'] = 'MyssQingKong';
				$bUrl = __URL__.'/delTable';//清空数据
				$this->_boxx($bUrl);
				break;
			case 10;
				$_SESSION['UrlPTPass'] = 'MyssGuanXiGua';
				$bUrl = __URL__.'/adminAgents';//代理商管理
				$this->_boxx($bUrl);
				break;
			case 11;
				$_SESSION['UrlPTPass'] = 'MyssBaiGuoJS';
				$bUrl = __URL__.'/adminClearing';//奖金结算
				$this->_boxx($bUrl);
				break;
			case 12;
				$_SESSION['UrlPTPass'] = 'MyssGuanMangGuo';
				$bUrl = __URL__.'/adminCurrencyRecharge';//充值管理
				$this->_boxx($bUrl);
				break;
			case 13;
                $_SESSION['UrlPTPass'] = 'MyssGuansingle';
                $bUrl = __URL__.'/adminsingle';//加单管理
                $this->_boxx($bUrl);
                break;
			case 18;
                $_SESSION['UrlPTPass'] = 'MyssMoneyFlows';
                $bUrl = __URL__.'/adminmoneyflows';//财务流向管理
                $this->_boxx($bUrl);
                break;
			case 19;
                $_SESSION['UrlPTPass'] = 'MyssProduct';
                $bUrl = __URL__.'/product';//加单管理
                $this->_boxx($bUrl);
                break;
			case 23;
                $_SESSION['UrlPTPass'] = 'MyssOrdersList';
                $bUrl = __URL__.'/OrdersList';//加单管理
                $this->_boxx($bUrl);
                break;
            case 24;
                $_SESSION['UrlPTPass'] = 'MyssWuliuList';
                $bUrl = __URL__.'/adminLogistics';//物流管理
                $this->_boxx($bUrl);
                break;
            case 25;
				$_SESSION['UrlPTPass'] = 'MyssGuanXiGuaJB';
				$bUrl = __URL__.'/adminJB';//金币中心管理
				$this->_boxx($bUrl);
				break;
			case 26;
				$_SESSION['UrlPTPass'] = 'MyssGuanChanPin';
				$bUrl = __URL__.'/pro_index';//产品管理
				$this->_boxx($bUrl);
				break;
			case 27;
				$_SESSION['UrlPTPass'] = 'MyssGuanzy';
				$bUrl = __URL__.'/admin_zy';//专营店管理
				$this->_boxx($bUrl);
				break;
			case 28;
				$_SESSION['UrlPTPass'] = 'MyssShenqixf';
				$bUrl = __URL__.'/adminXiaofei';//消费申请
				$this->_boxx($bUrl);
				break;
			case 29;
				$_SESSION['UrlPTPass'] = 'MyssJinji';
				$bUrl = __URL__.'/adminmemberJJ';//晋级
				$this->_boxx($bUrl);
				break;
			case 30;
                $_SESSION['UrlPTPass'] = 'MyssGuanUplevel';
                $bUrl = __URL__.'/admin_level';//会员升级
                $this->_boxx($bUrl);
                break;
			case 21;
                $_SESSION['UrlPTPass'] = 'MyssGuanXiGuaUp';
                $bUrl = __URL__.'/adminUserUp';//升级管理
                $this->_boxx($bUrl);
                break;
			case 22;
                $_SESSION['UrlPTPass'] = 'MyssPingGuoCPB';
                $bUrl = __URL__.'/setParameter_B';
                $this->_boxx($bUrl);
                break;
			default;
				$this->error('二级密码错误!');
				break;
		}
	}

	//订单处理
	public function OrdersAc(){
		//获取复选框的值
		$id_str = $_POST['tabledb'];
		$action = $_POST['action'];

		switch($action){
			case '发货':
				$this->ConfrimOrders($id_str,2);  //发货
				break;
			case '无货':
				$this->ConfrimOrders($id_str,1);  //无货
				break;
			default:
				$this->ConfrimOrders($id_str,2);  //发货
				break;
		}
	}

	//订单处理(已发货,无货)
	private function ConfrimOrders($id_str=0,$resu){
		$fck    = M ('fck');
		$orders = M ('orders');

		$where = array();
		$where['id'] = array('in',$id_str);
		$where['status'] = array('eq',0);

		$data = array();
		$data['status'] = $resu;
		$data['confirm_time'] = time();

		$rs = $orders -> where($where) -> data($data) -> save();  //更新为已发货
		if($rs){
			foreach($id_str as $vo){
				$rs = $orders -> find($vo);
				$money = $rs['pmoney'] * $rs['pnum'];
				$fck -> query("update __TABLE__ set points=points+$money,cpzj=cpzj+$money where id=". $rs['uid']);  //cpzj 累计消费金额
			}
			$bUrl = __URL__.'/OrdersList';
			$this->_box(1,'订单处理成功！',$bUrl,1);
		}else{
			$this->error('数据错误!');
			exit;
		}
	}


	//订单管理
	public function OrdersList(){
		if ($_SESSION['UrlPTPass'] != 'MyssOrdersList'){
			$this->error('数据错误!');
			exit;
		}

		$orders = M ('orders');  //订单表

		//判断show状态
		$s = (int) $_GET['s'];
		if($s <=0 or $s >=3){
			$s = 1;
		}

		$where = array();
		$page_where = '';
		if(isset($_REQUEST['title']) and !empty($_REQUEST['title'])){
			$where['pname'] = array('like','%'. trim($_REQUEST['title'] .'%'));
			$page_where = 'title='. trim($_REQUEST['title']);
		}

		if($s == 2){
			$where['status'] = array('gt',0);  //0未发货,1无货,2已发货
		}else{
			$where['status'] = array('eq',0);  //0未发货,1无货,2已发货
		}

		$field  = '*';
		//=====================分页开始==============================================
		import ( "@.ORG.ZQPage" );  //导入分页类
		$count = $orders->where($where)->count();//总页数
        $listrows = C('ONE_PAGE_RE');//每页显示的记录数
		$Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
		//===============(总页数,每页显示记录数,css样式 0-9)
		$show = $Page->show();//分页变量
		$this->assign('page',$show);//分页变量输出到模板
		$list = $orders->where($where)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
		$this->assign('list',$list);//数据输出到模板
		//=================================================

		$this->assign('s',$s);
		$this -> display('OrdersList');
	}

	//============================================会员升级页面显示
	public function admin_level($GPid=0){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssGuanUplevel'){
			$fck = M('fck');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			$map['sel_level'] = array('lt',90);

            //查询字段
            $field  = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,f4,cpzj,pdt,u_level,sel_level';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
      		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('rdt desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);

            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ('admin_level');
		}else{
			$this->error('数据错误!');
			exit;
		}
	}





	//========================================数据库管理
	public function adminManageTables(){
		if ($_SESSION['UrlPTPass'] == 'MyssHaMiGua'){
			$Url =__ROOT__.'/HaMiGua/';
			$_SESSION['shujukuguanli!12312g@#$%^@#$!@#$~!@#$'] = md5("^&%#hdgfhfg$@#$@gdfsg13123123!@#!@#");
			$this ->_boxx($Url);
		}
	}
	//============================================审核会员
	public function auditMenber($GPid=0){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
			$fck = M('fck');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			$map['is_pay'] = array('eq',0);

            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('is_pay,id,rdt desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
            $this->assign('list',$list);//数据输出到模板
            //=================================================



			$this->display ('auditMenber');
		}else{
			$this->error('数据错误!');
			exit;
		}
	}
	public function auditMenberData() {
		if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao' ){
			//查看会员详细信息
			$fck = M ( 'fck' );
			$ID = (int) $_GET['PT_id'];
			//判断获取数据的真实性 是否为数字 长度
			if (strlen($ID) > 11){
				$this->error ('数据错误!');
				exit;
			}
			$where = array();
			$where['id'] = $ID;
			$field = '*';
			$vo = $fck ->where($where)->field($field)->find();
			if ($vo){
				$this->assign ( 'vo', $vo );
				$this->display ();
			}else{
				$this->error ('数据错误!');
				exit;
			}
		}else{
			$this->error ('数据错误!');
			exit;
		}
	}
	public function auditMenberData2() {
		if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao' ){
			//查看会员详细信息
			$fck = M ( 'fck' );
			$ID = (int) $_GET['PT_id'];
			//判断获取数据的真实性 是否为数字 长度
			if (strlen($ID) > 11){
				$this->error ('数据错误!');
				exit;
			}
			$where = array();
			$where['id'] = $ID;
			$field = '*';
			$vo = $fck ->where($where)->field($field)->find();
			if ($vo){
				$this->assign ( 'vo', $vo );
				$this->display ();
			}else{
				$this->error ('数据错误!');
				exit;
			}
		}else{
			$this->error ('数据错误!');
			exit;
		}
	}
	public function auditMenberData2AC() {
		if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao' ){

			$fck = M ('fck');
			$data = array();

			$where['id'] = (int) $_POST['id'];
			$rs = $fck -> where('is_pay = 0') -> find($where['id']);
			if(!$rs){
				$this->error ('非法操作!');
				exit;
			}

			$data['nickname'] = $_POST['NickName'];
			$rs = $fck -> where($data) -> find();
			if($rs){
				if($rs['id'] != $where['id']){
					$this->error ('该会员名已经存在!');
					exit;
				}
			}

			$data['bank_name'] = $_POST['BankName'];
			$data['bank_card'] = $_POST['BankCard'];
			$data['user_name'] = $_POST['UserName'];
			$data['bank_province'] = $_POST['BankProvince'];
			$data['bank_city'] = $_POST['BankCity'];
			$data['user_code'] = $_POST['UserCode'];
			$data['bank_address'] = $_POST['BankAddress'];
			$data['user_address'] = $_POST['UserAddress'];
			$data['user_post'] = $_POST['UserPost'];
			$data['user_tel'] = $_POST['UserTel'];
			$data['bank_province'] = $_POST['BankProvince'];
			$data['is_lock'] = $_POST['isLock'];

			$fck->where($where)->data($data)->save();
			$bUrl = __URL__.'/auditMenberData2/PT_id/'.$where['id'];
			$this->_box(1,'修改会员信息！',$bUrl,1);

		}else{
			$this->error ('数据错误!');
			exit;
		}
	}
	public function auditMenberAC(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST['tabledb'];
		if (!isset($PTid) || empty($PTid)){
			$bUrl = __URL__.'/auditMenber';
			$this->_box(0,'请选择会员！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '开通会员';
				$this->_auditMenberOpenUser($PTid);
				break;
			case '设为空单';
				$this->_auditMenberOpenNull($PTid);
				break;
			case '删除会员';
				$this->_auditMenberDelUser($PTid);
				break;
			case '申请通过';
				$this->_AdminLevelAllow($PTid);
				break;
			case '拒绝通过';
				$this->_AdminLevelNo($PTid);
				break;
		default;
			$bUrl = __URL__.'/auditMenber';
			$this->_box(0,'没有该会员！',$bUrl,1);
			break;
		}
	}


	//审核会员升级-通过
	private function _AdminLevelAllow($PTid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanUplevel'){
			$fck = M ('fck');
			$where = array();
			$where['id'] = array ('in',$PTid);
			$where['sel_level'] = array('lt',90);
			$vo = $fck ->where($where)->field('id,sel_level')->select();
			foreach($vo as $voo){
				$where = array();
				$data  = array();
				$where['id'] = $voo['id'];
				$data['u_level']   = $voo['sel_level'];
				$data['sel_level'] = 98;
				$fck -> where($where) -> data($data) -> save();
			}

			$bUrl = __URL__.'/admin_level';
			$this->_box(1,'会员升级通过！',$bUrl,1);
		}else{
			$this->error ('数据错误!');
			exit;
		}

	}

	//审核会员升级-拒绝
	private function _AdminLevelNo($PTid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanUplevel'){
			$fck = M ('fck');
			$where = array();
			$where['id'] = array ('in',$PTid);
			$where['sel_level'] = array('lt',90);
			$vo = $fck ->where($where)->field('id')->select();
			foreach($vo as $voo){
				$where = array();
				$data  = array();
				$where['id'] = $voo['id'];
				$data['sel_level'] = 97;
				$fck -> where($where) -> data($data) -> save();
			}

			$bUrl = __URL__.'/admin_level';
			$this->_box(1,'拒绝会员升级！',$bUrl,1);
		}else{
			$this->error ('数据错误!');
			exit;
		}

	}

    //===============================================设为空单
    private function _auditMenberOpenNull($PTid=0){
        if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
            $fck = D ('Fck');
            $where = array();
            if (!$fck->autoCheckToken($_POST)){
                $this->error('页面过期，请刷新页面！');
                exit;
            }
            $ID = $_SESSION[C('USER_AUTH_KEY')];
            $where['id'] = array ('in',$PTid);
            $where['is_pay'] = 0;
            $field = "id,u_level,re_id,cpzj,re_path,user_id,p_path,p_level,shop_id,f4";
            $vo = $fck ->where($where)->order('rdt asc')->field($field)->select();
            $nowdate = strtotime(date('c'));
            $nowday=strtotime(date('Y-m-d'));
            foreach($vo as $voo){

            	$mars = $fck->where('is_pay>0')->field('n_pai')->order('n_pai desc')->find();
				$max_p = $mars['n_pai']+1;

                //开通
                $fck->query("update __TABLE__ set `is_pay`=2,`pdt`={$nowdate},is_fenh=1,get_date=".$nowday.",n_pai=".$max_p." where `id`=".$voo['id']);
            }
            unset($fck,$where,$field,$vo,$nowday);
            $bUrl = __URL__.'/auditMenber';
            $this->_box(1,'设为空单！',$bUrl,1);
            exit;

        }else{
            $this->error('错误！');
            exit;
        }
    }

	//===============================================开通会员

	private function _auditMenberOpenUser($PTid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
			$fck = D ('Fck');
			$gouwu = M ('gouwu');
			$fee_rs = M ('fee') -> find();
			$fee_s3  = explode('|',$fee_rs['s3']);   //进各网金额数组
			$ssl = $fee_rs['s6']/100;
			$orders = M ('orders');  //订单表
			$where = array();
//			if (!$fck->autoCheckToken($_POST)){
//				$this->error('页面过期，请刷新页面！');
//                exit;
//			}

			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = array ('in',$PTid);
			$where['is_pay'] = 0;
			$field = "*";
			$vo = $fck ->where($where)->field($field)->order('id asc')->select();
			$nowdate = strtotime(date('c'));
			$nowday = strtotime(date('Y-m-d'));
			$fck->emptyTime();
			foreach($vo as $voo){

				$ppath=$voo['p_path'];
				//上级未开通不能开通下级员工
				$frs_where['is_pay'] = array('eq',0);
				$frs_where['id'] = $voo['father_id'];
				$frs = $fck -> where($frs_where) -> find();
				if($frs){
					$this->error('开通失败，上级未开通');
					exit;
				}

				//给推荐人添加推荐人数或单数
				$fck->query("update __TABLE__ set `re_nums`=re_nums+1,re_f4=re_f4+".$voo['f4']." where `id`=".$voo['re_id']);

				//生成发货单
				M('gouwu')->query("update __TABLE__ set lx=1 where uid=".$voo['id']);

				//开通会员
				$fck->query("update __TABLE__ set `is_pay`=1,`pdt`=".$nowdate.",open=1,get_date=".$nowday."  where `id`=".$voo['id']);

				//统计单数
				$fck->xiangJiao($voo['id'], $voo['f4']);

				//算出奖金
				$fck->getusjj($voo['id']);

				//全部奖金结算
				$this->_clearing();

			}
//			$this->_clearing();
			unset($fck,$where,$field,$vo,$nowday);
			$bUrl = __URL__.'/auditMenber';
			$this->_box(1,'开通会员成功！',$bUrl,1);
			exit;

		}else{
			$this->error('错误！');
			exit;
		}
	}
	private function _auditMenberDelUser($PTid=0){
		//删除会员
		if ($_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
			$fck = M ('fck');
			$ispay = M('ispay');
			$gouwu = M('gouwu');
			$where['is_pay'] = 0;
//			$where['id'] = array ('in',$PTid);
			foreach($PTid as $voo){
				$rs = $fck -> find($voo);
				if($rs){
					$whe['father_name'] = $rs['user_id'];
					$rss = $fck -> where($whe) -> find();
					if($rss){
						$bUrl = __URL__.'/auditMenber';
						$this -> error('该 '. $rs['user_id'] .' 会员有下级会员，不能删除！');
						exit;
					}else{
						$gouwu->where('uid='.$voo)->delete();
		            	$where['id'] = $voo;
			            $a= $fck->where($where)->delete();
					}
				}else{
					$this->error('错误!');
				}
			}

			$bUrl = __URL__.'/auditMenber';
			$this->_box(1,'删除会员成功！',$bUrl,1);

//			$rs = $fck->where($where)->delete();
//			if ($rs){
//				$bUrl = __URL__.'/auditMenber';
//				$this->_box(1,'删除会员！',$bUrl,1);
//				exit;
//			}else{
//				$bUrl = __URL__.'/auditMenber';
//				$this->_box(0,'删除会员！',$bUrl,1);
//				exit;
//			}
		}else{
			$this->error('错误!');
		}
	}
//1111111111
	public function adminMenber($GPid=0){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M('fck');
			$UserID = $_REQUEST['UserID'];
			$ss_type = (int) $_REQUEST['type'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			$map['is_pay'] = array('egt',1);
            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
       		$listrows = 20;//每页显示的记录数
            $page_where = 'UserID=' . $UserID . '&type=' . $ss_type;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc,id desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
            $this->assign('list',$list);//数据输出到模板
            //=================================================


			$title = '会员管理';
			$this->assign('title',$title);
			$this->display ('adminMenber');
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function adminlookfh(){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){

			$uid = (int)$_GET['uid'];
			if(empty($uid)){
				$this->error('数据错误!');
				exit;
			}
			$fenhong = M('fenhong');
			$where = array();
			$where['uid'] = array('eq',$uid);

			//查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fenhong->where($where)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = '';//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fenhong->where($where)->field($field)->order('f_num asc,id asc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================
            $this->display();

		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	//会员晋级管理
	public function adminmemberJJ($GPid=0){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssJinji'){
			$fck = M('fck');
			$UserID = $_REQUEST['UserID'];
			$u_sd = $_REQUEST['u_sd'];
			$uulv = (int)$_REQUEST['ulevel'];

			$ss_type = (int) $_REQUEST['type'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);

				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			if(!empty($u_sd)){
				$map['is_lock'] =1;
            }
            if(!empty($uulv)){
            	$map['u_level'] =$uulv;
            }
			$map['is_pay'] = array('egt',1);
			$danshu = $fck->where($map)->sum('xxxx2');
			$renshu = $fck->where($map)->count();//总人数
            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID . '&type=' . $ss_type. '&ulevel=' . $uulv;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc,id desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
            $level = array();
			for($i=0;$i<count($HYJJ) ;$i++){
				$level[$i] = $HYJJ[$i+1];
			}
			$this->assign('level',$level);
//            dump($HYJJ);exit;
            $this->assign('danshu',$danshu);
            $this->assign('count',$renshu);
            $this->assign('list',$list);//数据输出到模板
            //=================================================


			$title = '会员管理';
			$this->assign('title',$title);
			$this->display ();
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}


	//会员晋级
	public function MenberJinji(){
		if ($_SESSION['UrlPTPass'] == 'MyssJinji'){
			$where = array();
			$fck = M('fck');

	    	$uid = (int)$_REQUEST['uid'];

			$frs = $fck->find($uid);
			$voo = 0;
			$this->_levelConfirm($voo);

			$level = array();
			for($i=0;$i<count($voo) ;$i++){
				$level[$i] = $voo[$i+1];
			}
			$this->assign('level',$level);


			$fee = M ('fee');
			$fee_rs =$fee->field('s1,s2,s9,s4,s5')->find();
			$s1 =explode('|',$fee_rs['s1']);
			$s2 =explode('|',$fee_rs['s2']);
			$s3 =explode('|',$fee_rs['s9']);
			$s4 =$fee_rs['s4'];

			$this->assign('sx1',$s3);

			$promo = M('promo');
			$field  = '*';
			$map['uid'] =$uid;
            $list = $promo->where($map)->field($field)->order('id desc')->select();


            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->assign('uid',$uid);
			$this->assign('s4',$s4);
			$this->assign('le',$voo);
			$this->assign('level',$level);
			$this->assign('frs',$frs);//数据输出到模板
			$this->display();
		}else{
			$this->error('错误！');
			exit;
		}
	}

	//晋级处理
	public function jinjiConfirm(){
		if ($_SESSION['UrlPTPass'] == 'MyssJinji'){
			$ulevel = $_POST['uLevel'];
			$uid = (int)$_REQUEST['uid'];
			$where['id'] = $uid;
			$promo = M('promo');
			$fck = D('Fck');
			$fck_rs = $fck->where($where)->find();
			$fee = M ('fee');
			$fee_rs =$fee->field('s1,s2,s9,s4,s5')->find();
			$s1 =explode('|',$fee_rs['s1']);
			$s2 =explode('|',$fee_rs['s2']);
			$s3 =explode('|',$fee_rs['s9']);
			$s4 =explode('|',$fee_rs['s4']);
			$s5 =explode('|',$fee_rs['s5']);

			$ulevel = $ulevel+1;
			$needlv = $ulevel-1;
			$oldlv  = $fck_rs['u_level']-1;
			$needmo = $s3[$needlv];
			$needdl = $s2[$needlv];
			$olddl  = $s2[$oldlv];
			$okdanl = $needdl-$olddl;

			if($fck_rs['u_level'] >=$ulevel){
				$this->error('升级参数不正确！');
			}

			if($fck_rs['u_level'] >=4){
				$this->error('已经是最高级，无法再升级！');
			}

			$content = $_POST['content'];		//备注
			if (empty($content)){
				$this->error('备注不能为空!');
				exit;
			}

			$promo->startTrans();
			// 写入帐号数据
			$data['money']				= $needmo;	//注册金额
			$data['u_level']			= $fck_rs['u_level'];
			$data['uid']       			= $uid;
			$data['user_id']			= $fck_rs['user_id'];
			$data['create_time']		= time();
			$data['pdt']				= time();
			$data['up_level']			= $ulevel;
			$data['danshu']			= $okdanl;
			$data['is_pay']			= 1;
			$data['user_name']			= $content;
			$data['u_bank_name']		= $fck_rs['bank_name'];
			$data['type']				= 0;
			//批量写入
	        $result = $promo->add($data);
			unset($data);
			if($result) {
				$time=time();
				$fck->commit();//提交事务

				$fck->xiangJiao($uid,$okdanl);

				$ss = $fck->where('id ='.$uid)->field('re_id,user_id')->find();

				$oldxf = $s5[$oldlv];
				$newxf = $s5[$needlv];
				$xiaof = $newxf-$oldxf;

				$oldjf = $s4[$oldlv];
				$newjf = $s4[$needlv];
				$gpjf = $newjf-$oldjf;

				$fck->query("update __TABLE__ set u_level=$ulevel,cpzj=$needmo where `id`=".$uid);

//				//配送股票
//				$this->member_buygp($uid,$gpjf);

				$bUrl = __URL__.'/adminmemberJJ';
				$this->_box(1,'晋级成功！',$bUrl,3);
			}else{
				//事务回滚：
	            $fck->rollback();
				$this->error('晋级失败！');
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}


	public function jinjishow(){
		//查看详细信息

			$promo = M('promo');
			$ID = (int) $_GET['Sid'];
			$where = array();
			$where['id'] = $ID;
			$srs = $promo->where($where)->field('user_name')->find();
			$this->assign('srs',$srs);
			unset($promo,$where,$srs);
			$this->display ('jinjishow');
	}

	public function premAdd(){
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
        $id = (int) $_GET['id'];
        $table = M ('fck');
        $rs = $table -> field('id,is_boss,prem') ->find($id);
        if ($rs){
            $ars = array();
            $arr = explode(',',$rs['prem']);
            for ($i=1;$i<=30;$i++){
                if (in_array($i,$arr)){
                    $ars[$i] = "checked";
                }else{
                    $ars[$i] = "";
                }
            }
            $this->assign('ars',$ars);
            $this->assign('rs',$rs);
            $title = '修改权限';
        }else{
            $title = '添加权限';
        }

        $this->assign('title',$title);
        $this->display('premAdd');
        }else{
            $this->error('权限错误!');
        }
    }

    public function premAddSave(){
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
            $id = (int) $_POST['id'];
            if ($id == 1 && $_SESSION[C('USER_AUTH_KEY')] != 1){
                $this->error('不能修改该会员的权限!');
                exit;
            }
            $table = M ('fck');
            $is_boss = $_POST['is_boss'];
            $boss = $_POST['isBoss'];
            $arr = ',';
            if (is_array($is_boss)){
                foreach ($is_boss as $vo){
                    $arr .= $vo.',';
                }
            }
            $data = array();
            $data['is_boss'] = $boss;
            $data['prem'] = $arr;
            $data['id'] = $id;
//            if ($id == 1){
//            	$this->error('不能修改最高会员！');
//            }
            $table->save($data);
            $title = '修改权限';
            $bUrl = __URL__.'/adminMenber';
            $this->_box(1,$title,$bUrl,2);
        }else{
            $this->error('权限错误!');
        }
    }

	//显示劳资详细
	public function BonusShow($GPid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$hi = M ('history');

			$where = array();
			$where['Uid']   = $_REQUEST['PT_id'];
			$where['type'] = 19;

			$list = $hi -> where($where) -> select();
			$this -> assign('list',$list);
			$this->display ('BonusShow');
		}else{
			$this->error('数据错误!');
			exit;
		}
	}


	public function adminuserData() {
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle' || $_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
			//查看会员详细信息
			$fck = M ( 'fck' );
			$ID = (int) $_GET['PT_id'];
			//判断获取数据的真实性 是否为数字 长度
			if (strlen($ID) > 15){
				$this->error ('数据错误!');
				exit;
			}
			$where = array();
			//查询条件
			//$where['ReID'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $ID;
			$field ='*';
			$vo = $fck ->where($where)->field($field)->find();
			if ($vo){
				$this->assign ('vo',$vo);

				$fee = M ('fee');
				$fee_s = $fee->field('str29')->find();
				$bank = explode('|',$fee_s['str29']);
				$this->assign('bank',$bank);
				$this->assign('b_bank',$vo);

				$this->display ();
			}else{
				$this->error ('数据错误!');
				exit;
			}
		}else{
			$this->error ('数据错误!');
			exit;
		}
	}

	/* --------------- 修改保存会员信息 ---------------- */
//	public function adminuserDataSave(){
//		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle' || $_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
//			$fck = M('fck');
//
////			$_POST['shop_name'] = trim($_POST['shopname']);
////			$whe = array();
////			$whe['user_id']  = $_POST['shop_name'];
////			$whe['is_agent'] = 2;
////			$shop_rs = $fck -> where($whe) -> field('id,user_id') -> find();
////			if(!$shop_rs){
////				$this->error ('没有该报单中心!');
////				exit;
////			}
//
//
//			//$_POST['NickName'] = $this->nickname($_POST['NickName'],$_POST['ID']);  //检测昵称
//			$_POST['BankName'] = $this->bank_name($_POST['BankName']);  //检测银行
//
//
//			$data = array();
////			$data['shop_id']          = $shop_rs['id'];        //所属报单中心ID
////			$data['shop_name']        = $shop_rs['user_id'];   //所属报单中心user_id
//			$data['pwd1']             = trim($_POST['pwd1']);      //一级密码不加密
//			$data['pwd2']             = trim($_POST['pwd2']);
//			$data['pwd3']             = trim($_POST['pwd3']);
//			$data['password']         = md5(trim($_POST['pwd1'])); //一级密码加密
//			$data['passopen']         = md5(trim($_POST['pwd2']));
//			$data['passopentwo']      = md5(trim($_POST['pwd3']));
//			$data['nickname']         = $_POST['NickName'];        //会员昵称
//			$data['bank_name']        = $_POST['BankName'];        //银行名称
//			$data['bank_card']        = $_POST['BankCard'];        //银行卡号
//			$data['user_name']        = $_POST['UserName'];        //开户姓名
//
//			$data['bank_province']    = $_POST['BankProvince'];  //省份
//			$data['bank_city']        = $_POST['BankCity'];      //城市
//			$data['bank_address']     = $_POST['BankAddress'];     //开户地址
//			$data['user_code']        = $_POST['UserCode'];        //身份证号码
//			$data['user_address']     = $_POST['UserAddress'];     //联系地址
//			$data['email']            = $_POST['email'];       //电子邮箱
//			$data['user_tel']         = $_POST['UserTel'];         //联系电话
//			$data['qq']         	  = $_POST['qq'];         //联系电话
//			$data['id']               = $_POST['ID'];              //要修改资料的AutoId
//			$data['agent_use']        = $_POST['AgentUse'];        //奖金币
//			$data['agent_cash']        = $_POST['AgentCash'];        //电子币
//
//			$rs = $fck->save($data);
//			if($rs){
//				$bUrl = __URL__.'/adminuserData/PT_id/'.$_POST['ID'];
//				$this->_box(1,'修改成功！',$bUrl,1);
//			}else{
//				$this->error('修改错误!');
//				exit;
//			}
//		}else{
//			$this->error('操作错误!');
//			exit;
//		}
//	}

	public function adminuserDataSave() {
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle' || $_SESSION['UrlPTPass'] == 'MyssShenShuiPuTao'){
            $fck     =   M('fck');
            if(!$fck->autoCheckToken($_POST)) {
                $this->error('页面过期，请刷新页面！');
            }
            $ID = (int) $_POST['ID'];
            $data = array();
            $data['pwd1']             = trim($_POST['pwd1']);      //一级密码不加密
			$data['pwd2']             = trim($_POST['pwd2']);
			$data['pwd3']             = trim($_POST['pwd3']);
			$data['password']         = md5(trim($_POST['pwd1'])); //一级密码加密
			$data['passopen']         = md5(trim($_POST['pwd2']));
			$data['passopentwo']      = md5(trim($_POST['pwd3']));

			$wenti = trim($_POST['wenti']);
			$wenti_dan = trim($_POST['wenti_dan']);
			if(!empty($wenti)){
          		$data['wenti']         = $wenti;
			}
			if(!empty($wenti_dan)){
          		$data['wenti_dan']     = $wenti_dan;
			}


            $data['nickname']         = $_POST['NickName'];
            $data['bank_name']        = $_POST['BankName'];
            $data['bank_card']        = $_POST['BankCard'];
            $data['user_name']        = $_POST['UserName'];
            $data['bank_province']    = $_POST['BankProvince'];
            $data['bank_city']        = $_POST['BankCity'];
            $data['bank_address']     = $_POST['BankAddress'];
            $data['user_code']        = $_POST['UserCode'];
            $data['user_address']     = $_POST['UserAddress'];
//            $data['user_post']        = $_POST['UserPost'];
//            $data['user_phone']       = $_POST['user_phone'];//邮编
            $data['user_tel']         = $_POST['UserTel'];
//            $data['is_lock']          = $_POST['isLock'];
            $data['qq']          = $_POST['qq'];
            $data['email']          = $_POST['email'];
            $data['agent_use']          = $_POST['AgentUse'];
            $data['agent_cash']          = $_POST['AgentCash'];
            $data['zjj']          = $_POST['zjj'];
            $data['fanli']				= $_POST['fanli'];
            $data['fanli_time']		= $_POST['fanli_time'];
            $data['fanli_num']			= $_POST['fanli_num'];
            $data['id']               = $_POST['ID'];

			$data['agent_kt']				= $_POST['AgentKt'];
			$data['agent_xf']				= $_POST['AgentXf'];
			$data['agent_gp']				= $_POST['AgentGp'];
			$data['agent_cf']				= $_POST['agent_cf'];
			$data['gp_num']				= (int)$_POST['gp_num'];

			$data['wang_j']               = (int)$_POST['wang_j'];
			$data['wang_t']               = (int)$_POST['wang_t'];



//            $data['u_level']          = $_POST['uLevel'];
//            if ($_POST['ID'] == 1){
//                $data['is_boss'] = 1;
//            }else{
//                $data['is_boss'] = $_POST['isBoss'];
//            }
            //$data['agent_use'] = $_POST['AgentUse'];
            //$data['agent_cash'] = $_POST['AgentCash'];
            $ReName = $_POST['ReName'];
            $re_where = array();
            $where = array();
            $where['nickname']  = $ReName;
            $where['user_id']   = $ReName;
            $where['_logic']    = 'or';
            $re_where['_complex']    = $where;
            $re_fck_rs = $fck->where($re_where)->field('id,nickname,user_id')->find();
            if ($re_fck_rs){
					if ($ID == 1){
							$data['re_id'] = 0;
							$data['re_name'] = 0;
					}else{
							$data['re_id'] = $re_fck_rs['id'];
							$data['re_name'] = $re_fck_rs['user_id'];
					}
			}else{
					if ($ID != 1 ){
						 $this->error('推荐人不存在，请重新输入！');
						exit;
					}
			}


            $p_shop = $_POST['p_shop'];
            $c_shop = $_POST['c_shop'];
            $a_shop = $_POST['a_shop'];
            $p_shop_id = 0;
            if (!empty($p_shop)){
                $p_where = array();
                $p_where['nickname'] = $p_shop;
                $p_where['is_agent'] = 2;
                $p_where['shoplevel'] = 3;
                $p_rs = $fck->where($p_where)->field('id,nickname,shop_path')->find();
                if (!$p_rs){
                    $this->error('省级代理不存在，请重新输入！');
                    exit;
                }
                $p_shop_id = $p_rs['id'];
            }
            $c_shop_id = 0;
            if (!empty($c_shop)){
                $p_where = array();
                $p_where['nickname'] = $c_shop;
                $p_where['is_agent'] = 2;
                $p_where['shoplevel'] = 2;
                $p_rs = $fck->where($p_where)->field('id,nickname,shop_path')->find();
                if (!$p_rs){
                    $this->error('市级代理不存在，请重新输入！');
                    exit;
                }
                $c_shop_id = $p_rs['id'];
            }
            $a_shop_id = 0;
            if (!empty($a_shop)){
                $p_where = array();
                $p_where['nickname'] = $a_shop;
                $p_where['is_agent'] = 2;
                $p_where['shoplevel'] = 1;
                $p_rs = $fck->where($p_where)->field('id,nickname,shop_path')->find();
                if (!$p_rs){
                    $this->error('县级代理不存在，请重新输入！');
                    exit;
                }
                $a_shop_id = $p_rs['id'];
            }
//            $where_nic = array();
//            $where_nic['nickname'] = $data['nickname'];
//            $rs = $fck -> where($where_nic) -> find();
//            if($rs){
//                if($rs['id'] != $data['id']){
//                    $this->error ('该会员编号已经存在!');
//                    exit;
//                }
//            }
            $where = array();
            $id = $_SESSION[C('USER_AUTH_KEY')];
            $where['id'] = $data['id'];
            $frs = $fck->where($where)->field('id,user_id,password,passopen,p_shop,c_shop,a_shop')->find();
            if ($frs){
                if ($frs['p_shop'] != $p_shop_id){
                    $data['p_shop'] = $p_shop_id;
                }
                if ($frs['c_shop'] != $c_shop_id){
                    $data['c_shop'] = $c_shop_id;
                }
                if ($frs['a_shop'] != $a_shop_id){
                    $data['a_shop'] = $a_shop_id;
                }
//
//                if ($_POST['Password']!= $frs['password']){
//                    $data['password'] = md5($_POST['Password']);
//                    if ($id == $data['id']){
//                        $_SESSION['login_sf_list_u'] = md5($frs['user_id']. ALL_PS .$data['password'].$_SERVER['HTTP_USER_AGENT']);
//                    }
//                }
//                if ($_POST['PassOpen'] != $frs['passopen']){
//                    $data['passopen'] = md5($_POST['PassOpen']);
//                }
            }

            $result =   $fck->save($data);
            if($result) {
                $bUrl = __URL__.'/adminMenber';
                $this->_box(1,'资料修改成功！',$bUrl,1);
                exit;
            }else{
                $bUrl = __URL__.'/adminMenber';
                $this->_box(0,'资料修改失败！',$bUrl,1);
            }
        }else{
            $bUrl = __URL__.'/adminMenber';
            $this->_box(0,'数据错误！',$bUrl,1);
            exit;
        }
    }


	public function slevel() {
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle'){
			//查看会员详细信息
			$fck = M ( 'fck' );
			$ID = (int) $_GET['PT_id'];
			//判断获取数据的真实性 是否为数字 长度
			if (strlen($ID) > 15){
				$this->error ('数据错误!');
				exit;
			}
			$where = array();
			//查询条件
			//$where['ReID'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $ID;
			$field ='*';
			$vo = $fck ->where($where)->field($field)->find();
			if ($vo){
				$this->assign ( 'vo', $vo );
				$this->display ();
			}else{
				$this->error ('数据错误!');
				exit;
			}
		}else{
			$this->error ('数据错误!');
			exit;
		}
	}
	public function slevelsave() {  //升级保存数据
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua' || $_SESSION['UrlPTPass'] == 'MyssGuansingle'){
			//查看会员详细信息
			$fck = D ('Fck');
			$fee = M ('fee');
			$ID = (int) $_POST['ID'];
			$slevel = (int) $_POST['slevel'];  //升级等级

			//判断获取数据的真实性 是否为数字 长度
			if (strlen($ID) > 15 or $ID <= 0){
				$this->error ('数据错误!');
				exit;
			}

			$fee_rs = $fee -> find(1);
			if( $slevel <= 0 or $slevel >= 7){
				$this->error('升级等级错误！');
				exit;
			}

			$where = array();
			//查询条件
			//$where['ReID'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $ID;
			$field ='*';
			$vo = $fck ->where($where)->field($field)->find();
			if($vo){
				switch($slevel){  //通过注册等级从数据库中找出注册金额及认购单数
					case 1:
						 $cpzj = $fee_rs['uf1'];  //注册金额
						 $F4 = $fee_rs['jf1'];    //自身认购单数
						 break;
					case 2:
						 $cpzj = $fee_rs['uf2'];
						 $F4 = $fee_rs['jf2'];
						 break;
					case 3:
						 $cpzj = $fee_rs['uf3'];
						 $F4 = $fee_rs['jf3'];
						 break;
					case 4:
						 $cpzj = $fee_rs['uf4'];
						 $F4 = $fee_rs['jf4'];
						 break;
					case 5:
						 $cpzj = $fee_rs['uf5'];
						 $F4 = $fee_rs['jf5'];
						 break;
					case 6:
						 $cpzj = $fee_rs['uf6'];
						 $F4 = $fee_rs['jf6'];
						 break;
				}

				$number = $F4 - $vo['f4'];  //升级所需单数差
				$data = array();
				$data['u_level'] = $slevel;  // 升级等级
				$data['cpzj'] = $cpzj;     // 注册金额
				$data['f4'] = $F4;       // 自身认购单数
				$fck -> where($where) -> data($data) -> save();

				$fck->xiangJiao_lr($ID,$number);//住上统计单数

				$bUrl = __URL__.'/adminMenber';
				$this->_box(1,'会员升级！',$bUrl,1);
				exit;
			}else{
				$this->error ('数据错误!');
				exit;
			}
		}else{
			$this->error ('数据错误!');
			exit;
		}
	}
	public function adminMenberAC(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST['tabledb'];
		if (!isset($PTid) || empty($PTid)){
			$bUrl = __URL__.'/adminMenber';
			$this->_box(0,'请选择会员！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '开启会员';
				$this->_adminMenberOpen($PTid);
				break;
			case '锁定会员';
				$this->_adminMenberLock($PTid);
				break;
			case '奖金提现';
				$this->adminMenberCurrency($PTid);
				break;
			case '开启奖金';
				$this->adminMenberFenhong($PTid);
				break;
			case '删除会员';
				$this->adminMenberDel($PTid);
				break;
			case '关闭奖金';
				$this->_Lockfenh($PTid);
				break;
			case '奖金转电子币';
				$this->adminMenberZhuan($PTid);
				break;
			case '设为报单中心';
				$this->_adminMenberAgent($PTid);
				break;
		default;
			$bUrl = __URL__.'/adminMenber';
			$this->_box(0,'没有该会员！',$bUrl,1);
			break;
		}
	}

	public function adminMenberDL(){

		if($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$result=$fck->execute('update __TABLE__ set agent_cash=agent_cash+agent_use,agent_use=0 where is_pay>0');

			$bUrl = __URL__.'/adminMenber';
			$this->_box(1,'转换会员奖金为电子币！',$bUrl,1);

		}else{
			$this->error('错误2!');
		}
	}

	public function adminMenberZhuan($PTid=0){

		if($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where['id'] = array ('in',$PTid);
			$rs = $fck->where($where)->field('id')->select();
			foreach($rs as $vo){
				$myid=$vo['id'];
				$fck->execute('update __TABLE__ set agent_cash=agent_cash+agent_use,agent_use=0 where is_pay>0 and id='.$myid.'');
			}
			unset($fck,$where,$rs,$myid,$result);
			$bUrl = __URL__.'/adminMenber';
			$this->_box(1,'转换会员奖金为电子币！',$bUrl,1);

		}else{
			$this->error('错误2!');
		}
	}

	private function adminMenberDel($PTid=0){
		if($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$times = M ('times');
			$bonus = M ('bonus');
			$history = M ('history');
			$chongzhi = M ('chongzhi');
			$gouwu = M ('gouwu');
			$tiqu = M ('tiqu');
			$zhuanj = M ('zhuanj');

			foreach($PTid as $voo){
				$rs = $fck -> find($voo);
				if($rs){
					$id = $rs['id'];
					$whe['id'] = $rs['father_id'];
					$con = $fck ->where($whe)->count();
					if($id == 1){
						$bUrl = __URL__.'/adminMenber';
						$this -> error('该 '. $rs['user_id'] .' 不能删除！');
						exit;
					}
					if($con==2){
						$bUrl = __URL__.'/adminMenber';
						$this -> error('该 '. $rs['user_id'] .' 会员有下级会员，不能删除！');
						exit;
					}
					if($con==1){
						$this->set_Re_Path($id);
						$this->set_P_Path($id);
					}
					$where = array();
					$where['id'] = $voo;
					$map['uid'] = $voo;
					$bonus->where($map) -> delete();
					$history->where($map) -> delete();
					$chongzhi->where($map) -> delete();
					$times->where($map) -> delete();
					$tiqu->where($map) -> delete();
					$zhuanj->where($map) -> delete();
					$gouwu->where($map) -> delete();
					$fck->where($where)->delete();
					$bUrl = __URL__.'/adminMenber';
					$this->_box(1,'删除会员！',$bUrl,1);
				}
			}
		}else{
			$this->error('错误!');
		}
	}


	public function set_Re_Path($id){
		$fck = M("fck");
		$frs = $fck -> find($id);

		$r_rs = $fck -> find($frs['re_id']);
		$xr_rs = $fck ->where("re_id=".$id)-> select();
		foreach($xr_rs as $xr_vo){
			$re_Level = $r_rs['re_level'] + 1;
			$re_path = $r_rs['re_path'].$r_rs['id'].',';
			$fck->execute("UPDATE __TABLE__ SET re_id=".$r_rs['id'].",re_name='".$r_rs['user_id']."',re_path='".$re_path."',re_level=".$re_Level." where `id`= ".$xr_vo['id']);
			//修改推荐路径
			$f_where = array();
			$f_where['re_path'] = array('like','%,'.$xr_vo['id'].',%');
			$ff_rs = $fck->where($f_where)->order('re_level asc')->select();
			$r_where = array();
			foreach($ff_rs as $fvo){
				$r_where['id'] = $fvo['re_id'];
				$sr_rs = $fck->where($r_where)->find();
				$r_pLevel = $sr_rs['re_level'] + 1;
				$r_re_path = $sr_rs['re_path'].$sr_rs['id'].',';
				$fck->execute("UPDATE __TABLE__ SET re_path='".$r_re_path."',re_level=".$r_pLevel." where `id`= ".$fvo['id']);
			}
		}
	}

	public function set_P_Path($id){
		$fck = M("fck");
		$frs = $fck -> find($id);

		$r_rs = $fck -> find($frs['father_id']);
		$xr_rs = $fck ->where("father_id=".$id)-> find();
		if($xr_rs){
			$p_level = $r_rs['p_level'] + 1;
			$p_path = $r_rs['p_path'].$r_rs['id'].',';
			$fck->execute("UPDATE __TABLE__ SET treeplace=".$frs['treeplace'].",father_id=".$r_rs['id'].",father_name='".$r_rs['user_id']."',p_path='".$p_path."',p_level=".$p_level." where `id`= ".$xr_rs['id']);
			//修改推荐路径
			$f_where = array();
			$f_where['p_path'] = array('like','%,'.$xr_rs['id'].',%');
			$ff_rs = $fck->where($f_where)->order('p_level asc')->select();
			$r_where = array();
			foreach($ff_rs as $fvo){
				$r_where['id'] = $fvo['father_id'];
				$sr_rs = $fck->where($r_where)->find();
				$p_level = $sr_rs['p_level'] + 1;
				$p_path = $sr_rs['p_path'].$sr_rs['id'].',';
				$fck->execute("UPDATE __TABLE__ SET p_path='".$p_path."',p_level=".$p_level." where `id`= ".$fvo['id']);
			}
		}
	}

	public function jiandan($Pid=0,$DanShu=1,$pdt,$t_rs){
        //========================================== 往上统计单数
        $fck = M ('fck');
        $where = array();
        $where['id'] = $Pid;
        $field = 'treeplace,father_id,pdt';
        $vo = $fck ->where($where)->field($field)->find();
        if ($vo){
            $Fid = $vo['father_id'];
            $TPe = $vo['treeplace'];
			if($pdt>$t_rs){
				if ($TPe == 0 && $Fid > 0){
                	$fck->execute("update __TABLE__ Set `l`=l-$DanShu, `benqi_l`=benqi_l-$DanShu where `id`=".$Fid);
	            }elseif($TPe == 1 && $Fid > 0){
	                $fck->execute("update __TABLE__ Set `r`=r-$DanShu, `benqi_r`=benqi_r-$DanShu  where `id`=".$Fid);
	            }
			}else{
				if ($TPe == 0 && $Fid > 0){
                	$fck->execute("update __TABLE__ Set `l`=l-$DanShu where `id`=".$Fid);
	            }elseif($TPe == 1 && $Fid > 0){
	                $fck->execute("update __TABLE__ Set `r`=r-$DanShu  where `id`=".$Fid);
	            }
			}

            if ($Fid > 0) $this->jiandan($Fid,$DanShu,$pdt,$t_rs);
        }
        unset($where,$field,$vo,$pdt,$t_rs);
    }

	private function adminMenberFenhong($PTid=0){
		if($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where['id'] = array ('in',$PTid);
			$where['is_pay'] = array('gt',0);
			$rs = $fck->where($where)->setField('is_fenh','0');
			if ($rs){
				$bUrl = __URL__.'/adminMenber';
				$this->_box(1,'开启奖金成功！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminMenber';
				$this->_box(0,'开启奖金失败！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}

	private function _Lockfenh($PTid=0){
		//锁定会员
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where['is_pay'] = array('egt',1);
			$where['_string'] = 'id>1';
			$where['id'] = array ('in',$PTid);
			$rs = $fck->where($where)->setField('is_fenh','1');

			if ($rs){
				$bUrl = __URL__.'/adminMenber';
				$this->_box(1,'关闭奖金成功！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminMenber';
				$this->_box(0,'关闭奖金失败！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误!');
		}
	}

	//开启会员
	private function _adminMenberOpen($PTid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where['id'] = array ('in',$PTid);
			$data['is_pay'] = 1;
			$rs = $fck->where($where)->setField('is_lock','0');
			if ($rs){
				$bUrl = __URL__.'/adminMenber';
				$this->_box(1,'开启会员！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminMenber';
				$this->_box(0,'开启会员！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}
	private function _adminMenberLock($PTid=0){
		//锁定会员
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where['is_pay'] = array('egt',1);
			$where['is_boss'] = 0;
			$where['id'] = array ('in',$PTid);
			$rs = $fck->where($where)->setField('is_lock','1');
			if ($rs){
				$bUrl = __URL__.'/adminMenber';
				$this->_box(1,'锁定会员！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminMenber';
				$this->_box(0,'锁定会员！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误!');
		}
	}

	//设为报单中心
	private function _adminMenberAgent($PTid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where['id'] = array ('in',$PTid);
			$where['is_agent'] = array ('lt',2);
			$rs2 = $fck->where($where)->setField('adt',mktime());
			$rs1 = $fck->where($where)->setField('is_agent','2');
			if ($rs1){
				$bUrl = __URL__.'/adminMenber';
				$this->_box(1,'设置报单中心成功！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminMenber';
				$this->_box(0,'设置报单中心失败！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}

    public function adminMenberUP(){
        //会员晋级
        if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
            $fck = M ('fck');
            $PTid = (int) $_GET['UP_ID'];
			$rs = $fck -> find($PTid);

			if(!$rs){
				$this->error('非法操作！');
				exit;
			}

			switch($rs['u_level']){
				case 1:
          	    	 $fck->query("update __TABLE__ SET u_level=2,b12=2000 where id=".$PTid);
					 break;
				case 2:
          	    	 $fck->query("update __TABLE__ SET u_level=3,b12=4000 where id=".$PTid);
					 break;
			}

            unset($fck,$PTid);
            $bUrl = __URL__.'/adminMenber';
            $this->_box(1,'晋升！',$bUrl,1);
        }else{
            $this->error('错误!');
        }
    }


	//=================================================管理员帮会员提现处理
	public function adminMenberCurrency($PTid=0){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao'){
			$fck = M ('fck');
			$where = array();//
			$tiqu = M ('tiqu');
			//查询条件
			$where['id'] = array('in',$PTid);
			$where['agent_use'] = array('egt',100);
			$field ='id,user_id,agent_use,bank_name,bank_card,user_name';
			$fck_rs = $fck ->where($where)->field($field)->select();

			$data = array();
			$tiqu_where = array();
			$eB = 0.02;//提现税收
			$nowdate = strtotime(date('c'));
			foreach ($fck_rs as $vo){
				$is_qf = 0;//区分上次有没有提现
				$ePoints = 0;
				$ePoints = $vo['agent_use'];

				$tiqu_where['uid'] = $vo['id'];
				$tiqu_where['is_pay'] = 0;
				$trs = $tiqu ->where($tiqu_where)->field('id')->find();
				if ($trs){
					$is_qf = 1;
				}
				//提现税收
//				if ($ePoints >= 10 && $ePoints <= 100){
//					$ePoints1 = $ePoints - 2;
//				}else{
//					$ePoints1 = $ePoints - $ePoints * $eB;//(/100);
//				}

				if ($is_qf == 0){
					$fck->query("update __TABLE__ SET `zsq`=zsq+agent_use,`agent_use`=0 where `id`=".$vo['id']);
					//开始事务处理
					$data['uid']            = $vo['id'];
					$data['user_id']        = $vo['user_id'];
					$data['rdt']            = $nowdate;
					$data['money']          = $ePoints;
					$data['money_two']      = $ePoints;
					$data['is_pay']         = 1;
					$data['user_name']      = $vo['user_name'];
					$data['bank_name']      = $vo['bank_name'];
					$data['bank_card']      = $vo['bank_card'];
					$tiqu->add($data);
				}
			}
			unset($fck,$where,$tiqu,$field,$fck_rs,$data,$tiqu_where,$eB,$nowdate);
			$bUrl = __URL__.'/adminMenber';
			$this->_box(1,'奖金提现！',$bUrl,1);
			exit;
		}else{
			$this->error('错误!');
			exit;
		}
	}

//========================================出纳管理

	public function adminFinance(){
		if ($_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
			$times = M ('times');
			$field = '*';
			$where = 'is_count=0';
			$Numso = array();
			$Numss = array();

			$rs = $times->where($where)->field($field)->order(' id desc')->find();
			$Numso['0'] = 0;
			$Numso['1'] = 0;
			$Numso['2'] = 0;
			if ($rs){
				$eDate = strtotime(date('c'));  //time()
				$sDate = $rs['benqi'] ;//时间

				$this->MiHouTaoBenQi($eDate, $sDate, $Numso, 0);
				$this->assign('list3', $Numso);   //本期收入
				$this->assign('list4', $sDate);   //本期时间截
			}else{
				$this->assign('list3', $Numso);
			}

			$fee = M('fee');
			$fee_rs = $fee->field('s18')->find();
			$fee_s7 = explode('|',$fee_rs['s18']);
			$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组

            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $times->where($where)->count();//总页数
            $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
            $Page = new ZQPage($count, $listrows, 1);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $rs = $times->where($where)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$rs);//数据输出到模板

			if ($rs){
				$occ = 1;
				$Numso['1'] = $Numso['1']+$Numso['0'];
				$Numso['3'] = $Numso['3']+$Numso['0'];
				foreach ($rs as $Roo){
					$eDate          = $Roo['benqi'];//本期时间
                    $sDate          = $Roo['shangqi'];//上期时间
					$Numsd          = array();
					$Numsd[$occ][0] = $eDate;
					$Numsd[$occ][1] = $sDate;

					$this->MiHouTaoBenQi($eDate,$sDate,$Numss,1);
					//$Numoo = $Numss['0'];   //当期收入
					$Numss[$occ]['0'] = $Numss['0'];
					$Dopp  = M ('bonus');
					$field = '*';
					$where = " s_date>= '".$sDate."' And e_date<= '".$eDate."' ";
					$rsc   = $Dopp->where($where)->field($field)->select();
					$Numss[$occ]['1'] = 0;

					foreach ($rsc as $Roc){
						$Numss[$occ]['1'] += $Roc['b0'] ;  //当期支出
						$Numb2[$occ]['1'] += $Roc['b1'];
						$Numb3[$occ]['1'] += $Roc['b2'];
						$Numb4[$occ]['1'] += $Roc['b3'];
						//$Numoo          += $Roc['b9'];//当期收入
					}
					$Numoo              = $Numss['0'];//当期收入
					$Numss[$occ]['2']   = $Numoo - $Numss[$occ]['1'];   //本期赢利
					$Numss[$occ]['3']   = substr( floor(($Numss[$occ]['1'] / $Numoo) * 100) , 0 ,3);  //本期拔比
					$Numso['1']        += $Numoo;  //收入合计
					$Numso['2']        += $Numss[$occ]['1'];           //支出合计
					$Numso['3']        += $Numss[$occ]['2'];           //赢利合计
					$Numso['4']         = substr( floor(($Numso['2'] / $Numso['1']) * 100) , 0 ,3);  //总拔比
					$Numss[$occ]['4']   = substr( ($Numb2[$occ]['1'] / $Numoo) * 100 , 0 ,4);  //小区奖金拔比
					$Numss[$occ]['5']   = substr( ($Numb3[$occ]['1'] / $Numoo) * 100 , 0 ,4);  //互助基金拔比
					$Numss[$occ]['6']   = substr( ($Numb4[$occ]['1'] / $Numoo) * 100 , 0 ,4); //管理基金拔比
					$Numss[$occ]['7']	= $Numb2[$occ]['1'];//小区奖金
					$Numss[$occ]['8'] 	= $Numb3[$occ]['1'] ;  //互助基金
					$Numss[$occ]['9'] 	= $Numb4[$occ]['1'];//管理基金
					$Numso['5']        += $Numb2[$occ]['1'];  //小区奖金合计
					$Numso['6']        += $Numb3[$occ]['1'];  //互助基金合计
					$Numso['7']        += $Numb4[$occ]['1'];  //管理基金合计
					$Numso['8']         = substr( ($Numso['5'] / $Numso['1']) * 100 , 0 ,4);  //小区奖金总拔比
					$Numso['9']         = substr( ($Numso['6'] / $Numso['1']) * 100 , 0 ,4);  //互助基金总拔比
					$Numso['10']         = substr( ($Numso['7'] / $Numso['1']) * 100 , 0 ,4);  //管理基金总拔比
					$occ++;
				}
			}

			$PP = $_GET['p'];
			$this->assign('PP',$PP);
			$this->assign('list1',$Numss);
			$this->assign('list2',$Numso);
			$this->assign('list5',$Numsd);
			$this->display('adminFinance');
		}else{
			$this->error('错误!');
			exit;
		}
	}


    public function adminFinanceList(){

        //当期收入会员列表
        if ($_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
            $fck = M('fck');
            $eDate  = $_REQUEST['eDate'];
            $sDate  = $_REQUEST['sDate'];
            $UserID = $_REQUEST['UserID'];
            $ss_type = (int) $_REQUEST['type'];
            if (!empty($UserID)){
            	import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                //if ($ss_type == 0){
//                    $map['nickname'] = array('like',"%".$UserID."%");
//                }else{
//                    $map['user_name'] = array('like',"%".$UserID."%");
//                }

				 unset($KuoZhan);
				$where['user_id'] = array('like',"%".$UserID."%");
            	$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_name'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}


//            if (!empty($eDate) && !empty($sDate)){
//                $map['pdt'] = array(array('egt',$eDate),array('elt',$sDate));
//            }else{
//            	$map['pdt'] = array('egt',$sDate);
//            }
			$cDate = $sDate + 23 * 3600 + 3599;
            $map['pdt'] = array(array('gt',$sDate),array('elt',$eDate));
            $map['is_pay'] = array('egt',1);
            //查询字段
            $field  = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,cpzj,pdt,u_level,zjj,agent_use,is_lock,open,re_name';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
            $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
            $page_where = 'UserID=' . $UserID . '&type=' . $ss_type .'&eDate='. $eDate .'&sDate='. $sDate ;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc')->page($Page->getPage().','.$listrows)->select();
            //dump( $fck->getLastSql() );
			//exit;
            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//商户级别
            $this->assign('list',$list);//数据输出到模板
            //=================================================


            $title = '当期收入';
            $this->assign('title',$title);
			$this->assign('sDate',$sDate);
			$this->assign('eDate',$eDate);
            $this->display ('adminFinanceList');
           // $this->display ('adminFinanceTableList');
            return;
        }else{
            $this->error('数据错误!');
            exit;
        }
    }


	private function MiHouTaoBenQi($eDate,$sDate,&$Numss,$ppo){
		if ($_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
			$bonus = M ('bonus');
			$field = '*';

			$fck = M ('fck');
			$fwhere = array();
			$fwhere['is_pay'] = 1;
			//$sDate = $eDate + 23 * 3600 + 3599;
			$fwhere['pdt'] = array(array('gt',$sDate),array('elt',$eDate));
			$Numss['0'] = $fck->where($fwhere)->sum('cpzj');
			if (is_numeric($Numss['0']) == false){
				$Numss['0'] = 0;
			}
			//foreach ($fck_rs as $vo){
			//	$Numss['0'] += $vo['cpzj'];
			//}
			unset($bonus,$field,$where);
			//unset($rs);
			unset($fck,$fwhere);
			//unset($fck_rs);
		}else{
			$this->error('错误');
			exit;
		}
	}

    public function adminFinanceTableGrant(){
    	//奖金发放
    	if ($_SESSION['UrlPTPass'] == 'MyssPiPa'){
    		$DID = (int) $_GET['Tid'];
    		if ($DID){
    			$fck = M ('fck');
    			$bonus = M ('bonus');
    			$times = M ('times');
    			$where = array();
    			$where['did'] = $DID;
    			$where['type'] = 0;
    			$brs = $bonus->where($where)->select();
    			foreach ($brs as $vo){
    				$money = 0;
    				$money = $vo['b0'] - ($vo['b5'] - $vo['b6']);
    				$fck->query("update __TABLE__ SET `agent_use`=agent_use+". $money .",`zjj`=zjj+". $money .",re_peat_money=re_peat_money+". $vo['b5'] .",m_money=m_money+". $vo['b1'] ." where id=".$vo['uid']);
    			}
    			$times->where("id=$DID")->setField('type',1);
    			$bonus->where("did=$DID")->setField('type',1);
    			unset($fck,$bonus,$times,$where,$brs);
    			$bUrl = __URL__.'/adminFinanceTable';
                $this->_box(1,'发放奖金！',$bUrl,1);
                exit;
    		}
    	}else{
            $this->error('错误');
            exit;
        }
    }

	//=====================================================奖金查询(所有期所有会员)
	public function adminFinanceTable(){
		if ($_SESSION['UrlPTPass'] == 'MyssPiPa'){
				$bonus = M ('bonus');  //奖金表
				$fee   = M ('fee');    //参数表
				$times = M ('times');  //结算时间表

				$fee_rs = $fee->field('s18')->find();
				$fee_s7 = explode('|',$fee_rs['s18']);
				$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组

				$where = array();
				$sql = '';
				if(isset($_REQUEST['FanNowDate'])){  //日期查询
					if(!empty($_REQUEST['FanNowDate'])){
						$time1 = strtotime($_REQUEST['FanNowDate']);                // 这天 00:00:00
						$time2 = strtotime($_REQUEST['FanNowDate']) + 3600*24 -1;   // 这天 23:59:59
						$sql = "where e_date >= $time1 and e_date <= $time2";
					}
				}


				$field  = '*';
				//=====================分页开始==============================================
				import ( "@.ORG.ZQPage" );  //导入分页类
				$count = count($bonus -> query("select id from __TABLE__ ". $sql ." group by did")); //总记录数
       			$listrows = C('ONE_PAGE_RE');//每页显示的记录数
				$page_where = 'FanNowDate=' . $_REQUEST['FanNowDate'];//分页条件
				if(!empty($page_where)){
					$Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
				}else{
					$Page = new ZQPage($count, $listrows, 1, 0, 3);
				}
				//===============(总页数,每页显示记录数,css样式 0-9)
				$show = $Page->show();//分页变量
				$this->assign('page', $show);//分页变量输出到模板
				$status_rs = ($Page->getPage()-1)*$listrows;
				$list = $bonus -> query("select e_date,did,sum(b0) as b0,sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8 from __TABLE__ ". $sql ." group by did  order by did desc limit ". $status_rs .",". $listrows);
				$this->assign('list',$list);//数据输出到模板
				//=================================================

				//各项奖每页汇总
		$count = array();
		foreach($list as $vo){
			for($b=0;$b<=10;$b++){
				$count[$b] += $vo['b'.$b];
				$count[$b] = $this->_2Mal($count[$b],2);
			}
		}

		//奖项名称与显示
		$b_b = array();
		$c_b = array();
		$b_b[1]  = C('Bonus_B1');
		$c_b[1]  = C('Bonus_B1c');
		$b_b[2]  = C('Bonus_B2');
		$c_b[2]  = C('Bonus_B2c');
		$b_b[3]  = C('Bonus_B3');
		$c_b[3]  = C('Bonus_B3c');
		$b_b[4]  = C('Bonus_B4');
		$c_b[4]  = C('Bonus_B4c');
		$b_b[5]  = C('Bonus_B5');
		$c_b[5]  = C('Bonus_B5c');
		$b_b[6]  = C('Bonus_B6');
		$c_b[6]  = C('Bonus_B6c');
		$b_b[7]  = C('Bonus_B7');
		$c_b[7]  = C('Bonus_B7c');
		$b_b[8]  = C('Bonus_B8');
		$c_b[8]  = C('Bonus_B8c');
		$b_b[9]  = C('Bonus_B9');
		$c_b[9]  = C('Bonus_B9c');
		$b_b[10] = C('Bonus_B10');
		$c_b[10] = C('Bonus_B10c');
		$b_b[11] = C('Bonus_HJ');   //合计
		$c_b[11] = C('Bonus_HJc');
		$b_b[0]  = C('Bonus_B0');   //实发
		$c_b[0]  = C('Bonus_B0c');
		$b_b[12] = C('Bonus_XX');   //详细
		$c_b[12] = C('Bonus_XXc');
		$this -> assign('b_b',$b_b);
		$this -> assign('c_b',$c_b);
		$this->assign('count',$count);

				//输出扣费奖索引
				$this->assign('ind',7);  //数组索引 +1

				$this->display('adminFinanceTable');
		}else{
			$this->error('错误');
			exit;
		}
	}

	//=====================================================查询这一期得奖会员资金
	public function adminFinanceTableShow(){
		if ($_SESSION['UrlPTPass'] == 'MyssPiPa' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
				$bonus = M ('bonus');  //奖金表
				$fee   = M ('fee');    //参数表
				$times = M ('times');  //结算时间表

				$fee_rs = $fee->field('s18')->find();
				$fee_s7 = explode('|',$fee_rs['s18']);
				$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组
				$UserID = $_REQUEST['UserID'];
				$where = array();
				$sql = '';
				$did = (int) $_REQUEST['did'];
				$field  = '*';

				if($UserID !=""){
					$sql =" and user_id like '%".$UserID."%'";
				}
				//=====================分页开始==============================================92607291105
				import ( "@.ORG.ZQPage" );  //导入分页类
				$count = count($bonus -> query("select id from __TABLE__ where did= ". $did .$sql)); //总记录数
       			$listrows = C('ONE_PAGE_RE');//每页显示的记录数
				$page_where = 'did/' . $_REQUEST['did'];//分页条件
				if(!empty($page_where)){
					$Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
				}else{
					$Page = new ZQPage($count, $listrows, 1, 0, 3);
				}
				//===============(总页数,每页显示记录数,css样式 0-9)
				$show = $Page->show();//分页变量
				$this->assign('page', $show);//分页变量输出到模板
				$status_rs = ($Page->getPage()-1)*$listrows;
				$list = $bonus -> query("select * from __TABLE__ where did =". $did . $sql ."  order by did desc limit ". $status_rs .",". $listrows);
				$this->assign('list',$list);//数据输出到模板
				//=================================================
				$this->assign('did',$did);
				//查看的这期的结算时间
				$this -> assign('confirm',$list[0]['e_date']);


			$count = array();
			foreach($list as $vo){
				for($b=0;$b<=10;$b++){
					$count[$b] += $vo['b'.$b];
					$count[$b] = $this->_2Mal($count[$b],2);
				}
			}

		//奖项名称与显示
		$b_b = array();
		$c_b = array();
		$b_b[1]  = C('Bonus_B1');
		$c_b[1]  = C('Bonus_B1c');
		$b_b[2]  = C('Bonus_B2');
		$c_b[2]  = C('Bonus_B2c');
		$b_b[3]  = C('Bonus_B3');
		$c_b[3]  = C('Bonus_B3c');
		$b_b[4]  = C('Bonus_B4');
		$c_b[4]  = C('Bonus_B4c');
		$b_b[5]  = C('Bonus_B5');
		$c_b[5]  = C('Bonus_B5c');
		$b_b[6]  = C('Bonus_B6');
		$c_b[6]  = C('Bonus_B6c');
		$b_b[7]  = C('Bonus_B7');
		$c_b[7]  = C('Bonus_B7c');
		$b_b[8]  = C('Bonus_B8');
		$c_b[8]  = C('Bonus_B8c');
		$b_b[9]  = C('Bonus_B9');
		$c_b[9]  = C('Bonus_B9c');
		$b_b[10] = C('Bonus_B10');
		$c_b[10] = C('Bonus_B10c');
		$b_b[11] = C('Bonus_HJ');   //合计
		$c_b[11] = C('Bonus_HJc');
		$b_b[0]  = C('Bonus_B0');   //实发
		$c_b[0]  = C('Bonus_B0c');
		$b_b[12] = C('Bonus_XX');   //详细
		$c_b[12] = C('Bonus_XXc');

		$this -> assign('b_b',$b_b);
		$this -> assign('c_b',$c_b);
		$this->assign('count',$count);



				$this->assign('int',7);


				$this->display('adminFinanceTableShow');
		}else{
			$this->error('错误');
			exit;
		}
	}


	//===============================================消费管理
	public function adminXiaofei(){
		if ($_SESSION['UrlPTPass'] == 'MyssShenqixf'){
			$xiaof = M ('xiaof');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				$map['user_id'] = array('like',"%".$UserID."%");
			}

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $xiaof->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $xiaof->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display('adminXiaofei');
		}else{
			$this->error('错误!');
			exit;
		}
	}
	//处理消费
	public function adminXiaofeiAC(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST['tabledb'];
		$fck = M ('fck');
//	    if (!$fck->autoCheckToken($_POST)){
//            $this->error('页面过期，请刷新页面！');
//            exit;
//        }
		if (empty($PTid)){
			$bUrl = __URL__.'/adminXiaofei';
			$this->_box(0,'请选择！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '确认':
				$this->_adminXiaofeiConfirm($PTid);
				break;
			case '删除':
				$this->_adminXiaofeiDel($PTid);
				break;
		default:
			$bUrl = __URL__.'/adminXiaofei';
			$this->_box(0, '没有该记录！', $bUrl,1);
			break;
		}
	}

	//====================================================确认消费
	private function _adminXiaofeiConfirm($PTid){
		if ($_SESSION['UrlPTPass'] == 'MyssShenqixf'){
			$xiaof = M ('xiaof');
			$fck = M('fck');//
			$where = array();
			$where['is_pay'] = 0;               //未审核的申请
			$where['id'] = array ('in',$PTid);  //所有选中的会员ID
			$rs = $xiaof->where($where)->select();  //tiqu表要通过的申请记录数组
			$history = M('history');
			$data = array();
			$fck_where = array();
			$nowdate = strtotime(date('c'));
			foreach($rs as $rss){
				//开始事务处理
				$fck->startTrans();
				//明细表
				$data['uid']           = $rss['uid'];
				$data['user_id']       = $rss['user_id'];
				$data['action_type']   = '重复消费';
				$data['pdt']           = $nowdate;
				$data['epoints']       = -$rss['money'];
				$data['bz']            = '重复消费';
				$data['did']           = 0;
				$data['allp']          = 0;
				$history->create();
				$rs1 = $history->add($data);
				if ($rs1){
					//提交事务
					$xiaof->execute("UPDATE __TABLE__ set `is_pay`=1 where `id`=".$rss['id']);
					$fck->commit();
				}else{
					//事务回滚：
					$fck->rollback();
				}
			}
			unset($xiaof,$fck,$where,$rs,$history,$data,$nowdate,$fck_where);
			$bUrl = __URL__.'/adminXiaofei';
			$this->_box(1,'确认消费成功！',$bUrl,1);
		}else{
			$this->error('错误!');
			exit;
		}
	}
	//删除消费
	private function _adminXiaofeiDel($PTid){
		if ($_SESSION['UrlPTPass'] == 'MyssShenqixf'){
			$xiaof = M ('xiaof');
			$where = array();
			$where['is_pay'] = 0;
			$where['id'] = array ('in',$PTid);
			$trs = $xiaof->where($where)->select();
			$fck = M ('fck');
			foreach ($trs as $vo){
				$fck->execute("UPDATE __TABLE__ SET agent_cash=agent_cash+{$vo['money']} WHERE id={$vo['uid']}");
			}
			$rs = $xiaof->where($where)->delete();
			if ($rs){
				$bUrl = __URL__.'/adminXiaofei';
				$this->_box(1,'删除成功！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminXiaofei';
				$this->_box(1,'删除成功！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误!');
			exit;
		}
	}


	public function financeDaoChu_ChuN(){
        //导出excel
		set_time_limit(0);

		header("Content-Type:   application/vnd.ms-excel");
        header("Content-Disposition:   attachment;   filename=Cashier.xls");
		header("Pragma:   no-cache");
		header("Content-Type:text/html; charset=utf-8");
		header("Expires:   0");

		$m_page = (int)$_GET['p'];
		if(empty($m_page)){
			$m_page=1;
		}

        $times = M ('times');
        $Numso = array();
		$Numss = array();
        $map = 'is_count=0';
        //查询字段
        $field   = '*';
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $times->where($map)->count();//总页数
        $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
        $s_p = $listrows*($m_page-1)+1;
        $e_p = $listrows*($m_page);

        $title   =   "当期出纳 第".$s_p."-".$e_p."条 导出时间:".date("Y-m-d   H:i:s");



        echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        //   输出标题
        echo   '<tr   bgcolor="#cccccc"><td   colspan="6"   align="center">'   .   $title   .   '</td></tr>';
        //   输出字段名
        echo   '<tr  align=center>';
        echo   "<td>期数</td>";
        echo   "<td>结算时间</td>";
        echo   "<td>当期收入</td>";
        echo   "<td>当期支出</td>";
        echo   "<td>当期盈利</td>";
        echo   "<td>拨出比例</td>";
        echo   '</tr>';
        //   输出内容

        $rs = $times->where($map)->order(' id desc')->find();
		$Numso['0'] = 0;
		$Numso['1'] = 0;
		$Numso['2'] = 0;
		if ($rs){
			$eDate = strtotime(date('c'));  //time()
			$sDate = $rs['benqi'] ;//时间

			$this->MiHouTaoBenQi($eDate, $sDate, $Numso, 0);
		}


        $page_where = '';//分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $list = $times ->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();

//		dump($list);exit;

		$occ = 1;
		$Numso['1'] = $Numso['1']+$Numso['0'];
		$Numso['3'] = $Numso['3']+$Numso['0'];
		$maxnn=0;
		foreach ($list as $Roo){

			$eDate          = $Roo['benqi'];//本期时间
            $sDate          = $Roo['shangqi'];//上期时间
			$Numsd          = array();
			$Numsd[$occ][0] = $eDate;
			$Numsd[$occ][1] = $sDate;

			$this->MiHouTaoBenQi($eDate,$sDate,$Numss,1);
			//$Numoo = $Numss['0'];   //当期收入
			$Numss[$occ]['0'] = $Numss['0'];
			$Dopp  = M ('bonus');
			$field = '*';
			$where = " s_date>= '".$sDate."' And e_date<= '".$eDate."' ";
			$rsc   = $Dopp->where($where)->field($field)->select();
			$Numss[$occ]['1'] = 0;
			$nnn=0;
			foreach ($rsc as $Roc){
				$nnn++;
				$Numss[$occ]['1'] += $Roc['b0'] ;  //当期支出
				$Numb2[$occ]['1'] += $Roc['b1'];
				$Numb3[$occ]['1'] += $Roc['b2'];
				$Numb4[$occ]['1'] += $Roc['b3'];
				//$Numoo          += $Roc['b9'];//当期收入
			}
			$maxnn+=$nnn;
			$Numoo              = $Numss['0'];//当期收入
			$Numss[$occ]['2']   = $Numoo - $Numss[$occ]['1'];   //本期赢利
			$Numss[$occ]['3']   = substr( floor(($Numss[$occ]['1'] / $Numoo) * 100) , 0 ,3);  //本期拔比
			$Numso['1']        += $Numoo;  //收入合计
			$Numso['2']        += $Numss[$occ]['1'];           //支出合计
			$Numso['3']        += $Numss[$occ]['2'];           //赢利合计
			$Numso['4']         = substr( floor(($Numso['2'] / $Numso['1']) * 100) , 0 ,3);  //总拔比
			$Numss[$occ]['4']   = substr( ($Numb2[$occ]['1'] / $Numoo) * 100 , 0 ,4);  //小区奖金拔比
			$Numss[$occ]['5']   = substr( ($Numb3[$occ]['1'] / $Numoo) * 100 , 0 ,4);  //互助基金拔比
			$Numss[$occ]['6']   = substr( ($Numb4[$occ]['1'] / $Numoo) * 100 , 0 ,4); //管理基金拔比
			$Numss[$occ]['7']	= $Numb2[$occ]['1'];//小区奖金
			$Numss[$occ]['8'] 	= $Numb3[$occ]['1'] ;  //互助基金
			$Numss[$occ]['9'] 	= $Numb4[$occ]['1'];//管理基金
			$Numso['5']        += $Numb2[$occ]['1'];  //小区奖金合计
			$Numso['6']        += $Numb3[$occ]['1'];  //互助基金合计
			$Numso['7']        += $Numb4[$occ]['1'];  //管理基金合计
			$Numso['8']         = substr( ($Numso['5'] / $Numso['1']) * 100 , 0 ,4);  //小区奖金总拔比
			$Numso['9']         = substr( ($Numso['6'] / $Numso['1']) * 100 , 0 ,4);  //互助基金总拔比
			$Numso['10']        = substr( ($Numso['7'] / $Numso['1']) * 100 , 0 ,4);  //管理基金总拔比
			$occ++;
		}


        $i = 0;
        foreach($list as $row)   {
            $i++;
            echo   '<tr align=center>';
            echo   '<td>'   .   $row['id']   .   '</td>';
            echo   '<td>'   .   date("Y-m-d H:i:s",$row['benqi'])   .   '</td>';
            echo   '<td>'   .   $Numss[$i][0].  '</td>';
            echo   '<td>'   .   $Numss[$i][1]   .   '</td>';
            echo   '<td>'   .   $Numss[$i][2]   .   '</td>';
            echo   '<td>'   .   $Numss[$i][3]   .   ' % </td>';
            echo   '</tr>';
        }
        echo   '</table>';
    }


	public function financeDaoChu_JJCX(){
        //导出excel
		set_time_limit(0);

		header("Content-Type:   application/vnd.ms-excel");
		header("Content-Disposition:   attachment;   filename=Bonus-query.xls");
		header("Pragma:   no-cache");
		header("Content-Type:text/html; charset=utf-8");
		header("Expires:   0");

		$m_page = (int)$_REQUEST['p'];
		if(empty($m_page)){
			$m_page=1;
		}
		$fee   = M ('fee');    //参数表
        $times = M ('times');
        $bonus = M ('bonus');  //奖金表
        $fee_rs = $fee->field('s18')->find();
		$fee_s7 = explode('|',$fee_rs['s18']);

        $where = array();
		$sql = '';
		if(isset($_REQUEST['FanNowDate'])){  //日期查询
			if(!empty($_REQUEST['FanNowDate'])){
				$time1 = strtotime($_REQUEST['FanNowDate']);                // 这天 00:00:00
				$time2 = strtotime($_REQUEST['FanNowDate']) + 3600*24 -1;   // 这天 23:59:59
				$sql = "where e_date >= $time1 and e_date <= $time2";
			}
		}

        $field   = '*';
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = count($bonus -> query("select id from __TABLE__ ". $sql ." group by did")); //总记录数
        $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
		$page_where = 'FanNowDate=' . $_REQUEST['FanNowDate'];//分页条件
		if(!empty($page_where)){
			$Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
		}else{
			$Page = new ZQPage($count, $listrows, 1, 0, 3);
		}
		//===============(总页数,每页显示记录数,css样式 0-9)
		$show = $Page->show();//分页变量
		$status_rs = ($Page->getPage()-1)*$listrows;
		$list = $bonus -> query("select e_date,did,sum(b0) as b0,sum(b1) as b1,sum(b2) as b2,sum(b3) as b3,sum(b4) as b4,sum(b5) as b5,sum(b6) as b6,sum(b7) as b7,sum(b8) as b8,max(type) as type from __TABLE__ ". $sql ." group by did  order by did desc limit ". $status_rs .",". $listrows);
		//=================================================


        $s_p = $listrows*($m_page-1)+1;
        $e_p = $listrows*($m_page);

        $title   =   "奖金查询 第".$s_p."-".$e_p."条 导出时间:".date("Y-m-d   H:i:s");



        echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        //   输出标题
        echo   '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">'   .   $title   .   '</td></tr>';
        //   输出字段名
        echo   '<tr  align=center>';
        echo   "<td>结算时间</td>";
        echo   "<td>".$fee_s7[0]."</td>";
        echo   "<td>".$fee_s7[1]."</td>";
        echo   "<td>".$fee_s7[2]."</td>";
        echo   "<td>".$fee_s7[3]."</td>";
        echo   "<td>".$fee_s7[4]."</td>";
        echo   "<td>".$fee_s7[5]."</td>";
        echo   "<td>".$fee_s7[6]."</td>";
        echo   "<td>合计</td>";
        echo   "<td>实发</td>";
        echo   '</tr>';
        //   输出内容

//		dump($list);exit;

        $i = 0;
        foreach($list as $row)   {
            $i++;
            $mmm = $row['b1']+$row['b2']+$row['b3']+$row['b4']+$row['b5']+$row['b6']+$row['b7'];
            echo   '<tr align=center>';
            echo   '<td>'   .   date("Y-m-d H:i:s",$row['e_date'])   .   '</td>';
            echo   "<td>"   .   $row['b1'].  "</td>";
            echo   "<td>"   .   $row['b2'].  "</td>";
            echo   "<td>"   .   $row['b3'].  "</td>";
            echo   "<td>"   .   $row['b4'].  "</td>";
            echo   "<td>"   .   $row['b5'].  "</td>";
            echo   "<td>"   .   $row['b6'].  "</td>";
            echo   "<td>"   .   $row['b7'].  "</td>";
            echo   "<td>"   .   $mmm.  "</td>";
            echo   "<td>"   .   $row['b0'].  "</td>";
            echo   '</tr>';
        }
        echo   '</table>';
    }

    //会员表
    public function financeDaoChu_MM(){
        //导出excel
		set_time_limit(0);

		header("Content-Type:   application/vnd.ms-excel");
		header("Content-Disposition:   attachment;   filename=Member.xls");
		header("Pragma:   no-cache");
		header("Content-Type:text/html; charset=utf-8");
		header("Expires:   0");



        $fck = M ('fck');  //奖金表

        $map = array();
		$map['id'] = array('gt',0);
        $field   = '*';
		$list = $fck->where($map)->field($field)->order('pdt asc')->select();

        $title   =   "会员表 导出时间:".date("Y-m-d   H:i:s");

        echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        //   输出标题
        echo   '<tr   bgcolor="#cccccc"><td   colspan="10"   align="center">'   .   $title   .   '</td></tr>';
        //   输出字段名
        echo   '<tr  align=center>';
        echo   "<td>序号</td>";
        echo   "<td>会员编号</td>";
        echo   "<td>姓名</td>";
        echo   "<td>银行卡号</td>";
        echo   "<td>开户行地址</td>";
        echo   "<td>联系电话</td>";
        echo   "<td>联系地址</td>";
        echo   "<td>QQ号</td>";
        echo   "<td>身份证号</td>";
        echo   "<td>注册时间</td>";
        echo   "<td>开通时间</td>";
        echo   "<td>总奖金</td>";
        echo   "<td>剩余奖金</td>";
        echo   "<td>剩余电子币</td>";
        echo   '</tr>';
        //   输出内容

//		dump($list);exit;

        $i = 0;
        foreach($list as $row)   {
            $i++;
            $num = strlen($i);
            if ($num == 1){
                $num = '000'.$i;
            }elseif ($num == 2){
                $num = '00'.$i;
            }elseif ($num == 3){
                $num = '0'.$i;
            }else{
            	$num = $i;
            }
            echo   '<tr align=center>';
            echo   '<td>'   .  chr(28).$num   .   '</td>';
            echo   "<td>"   .   $row['user_id'].  "</td>";
            echo   "<td>"   .   $row['user_name'].  "</td>";
            echo   "<td>"   .   sprintf('%s',(string)chr(28).$row['bank_card'].chr(28)).  "</td>";
            echo   "<td>"   .   $row['bank_province'].$row['bank_city'].$row['bank_address'].  "</td>";
            echo   "<td>"   .   $row['user_tel'].  "</td>";
            echo   "<td>"   .   $row['user_address'].  "</td>";
            echo   "<td>"   .   $row['qq'].  "</td>";
            echo   "<td>"   .   sprintf('%s',(string)chr(28).$row['user_code'].chr(28)).  "</td>";
            echo   "<td>"   .   date("Y-m-d H:i:s",$row['rdt']).  "</td>";
            echo   "<td>"   .   date("Y-m-d H:i:s",$row['pdt']).  "</td>";
            echo   "<td>"   .   $row['zjj'].  "</td>";
            echo   "<td>"   .   $row['agent_use'].  "</td>";
            echo   "<td>"   .   $row['agent_cash'].  "</td>";
            echo   '</tr>';
        }
        echo   '</table>';
    }

    //报单中心表
    public function financeDaoChu_BD(){
        //导出excel
		set_time_limit(0);

		header("Content-Type:   application/vnd.ms-excel");
		header("Content-Disposition:   attachment;   filename=Member-Agent.xls");
		header("Pragma:   no-cache");
		header("Content-Type:text/html; charset=utf-8");
		header("Expires:   0");



        $fck = M ('fck');  //奖金表

        $map = array();
		$map['id'] = array('gt',0);
		$map['is_agent'] = array('gt',0);
        $field   = '*';
		$list = $fck->where($map)->field($field)->order('idt asc,adt asc')->select();

        $title   =   "报单中心表 导出时间:".date("Y-m-d   H:i:s");

        echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
        //   输出标题
        echo   '<tr   bgcolor="#cccccc"><td   colspan="9"   align="center">'   .   $title   .   '</td></tr>';
        //   输出字段名
        echo   '<tr  align=center>';
        echo   "<td>序号</td>";
        echo   "<td>会员编号</td>";
        echo   "<td>姓名</td>";
        echo   "<td>联系电话</td>";
        echo   "<td>申请时间</td>";
        echo   "<td>确认时间</td>";
        echo   "<td>类型</td>";
        echo   "<td>报单中心区域</td>";
        echo   "<td>剩余电子币</td>";
        echo   '</tr>';
        //   输出内容

//		dump($list);exit;

        $i = 0;
        foreach($list as $row)   {
            $i++;
            $num = strlen($i);
            if ($num == 1){
                $num = '000'.$i;
            }elseif ($num == 2){
                $num = '00'.$i;
            }elseif ($num == 3){
                $num = '0'.$i;
            }else{
            	$num = $i;
            }
            if($row['shoplx']==1){
            	$nnn = '服务中心';
            }elseif($row['shoplx']==2){
            	$nnn = '县/区代理商';
            }else{
            	$nnn = '市级代理商';
            }


            echo   '<tr align=center>';
            echo   '<td>'   .  chr(28).$num   .   '</td>';
            echo   "<td>"   .   $row['user_id'].  "</td>";
            echo   "<td>"   .   $row['user_name'].  "</td>";
            echo   "<td>"   .   $row['user_tel'].  "</td>";
            echo   "<td>"   .   date("Y-m-d H:i:s",$row['idt']).  "</td>";
            echo   "<td>"   .   date("Y-m-d H:i:s",$row['adt']).  "</td>";
            echo   "<td>"   .   $nnn.  "</td>";
            echo   "<td>"   .   $row['shop_a'].  " / " . $row['shop_b']  .   "</td>";
            echo   "<td>"   .   $row['agent_cash'].  "</td>";
            echo   '</tr>';
        }
        echo   '</table>';
    }


	public function financeDaoChu(){
        //导出excel
//        if ($_SESSION['UrlPTPass'] =='MyssPiPa' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
            $title   =   "数据库名:test,   数据表:test,   备份日期:"   .   date("Y-m-d   H:i:s");
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=test.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            //   输出标题
            echo   '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">'   .   $title   .   '</td></tr>';
            //   输出字段名
            echo   '<tr  align=center>';
            echo   "<td>银行卡号</td>";
            echo   "<td>姓名</td>";
            echo   "<td>银行名称</td>";
            echo   "<td>省份</td>";
            echo   "<td>城市</td>";
            echo   "<td>金额</td>";
            echo   "<td>所有人的排序</td>";
            echo   '</tr>';
            //   输出内容
            $did = (int) $_GET['did'];
            $bonus = M ('bonus');

            $table_a = C('DB_PREFIX')."fck";
			$table_b = C('DB_PREFIX')."bonus";

            $map = ''.$table_b.'.b0>0 and '.$table_b.'.did='.$did;
             //查询字段
            $field   = ''.$table_b.'.id,'.$table_b.'.uid,'.$table_b.'.did,s_date,e_date,'.$table_b.'.b0,'.$table_b.'.b1,'.$table_b.'.b2,'.$table_b.'.b3';
            $field  .= ','.$table_b.'.b4,'.$table_b.'.b5,'.$table_b.'.b6,'.$table_b.'.b7,'.$table_b.'.b8,'.$table_b.'.b9,'.$table_b.'.b10';
            $field  .= ','.$table_a.'.user_id,'.$table_a.'.user_tel,'.$table_a.'.bank_card';
            $field  .= ','.$table_a.'.user_name,'.$table_a.'.user_address,'.$table_a.'.nickname,'.$table_a.'.user_phone,'.$table_a.'.bank_province,'.$table_a.'.user_tel';
            $field  .= ','.$table_a.'.user_code,'.$table_a.'.bank_city,'.$table_a.'.bank_name,'.$table_a.'.bank_address';
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $bonus->where($map)->count();//总页数
            $listrows = 1000000  ;//每页显示的记录数
            $page_where = '';//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $join = 'left join '.$table_a.' ON '.$table_b.'.uid='.$table_a.'.id';//连表查询
            $list = $bonus ->where($map)->field($field)->join($join)->Distinct(true)->order('id asc')->page($Page->getPage().','.$listrows)->select();
            $i = 0;
            foreach($list as $row)   {
                $i++;
                $num = strlen($i);
                if ($num == 1){
                    $num = '000'.$i;
                }elseif ($num == 2){
                    $num = '00'.$i;
                }elseif ($num == 3){
                    $num = '0'.$i;
                }
	            echo   '<tr align=center>';
	            echo   '<td>'   .   sprintf('%s',(string)chr(28).$row['bank_card'].chr(28)).      '</td>';
	            echo   '<td>'   .   $row['user_name']   .   '</td>';
	            echo   "<td>"   .   $row['bank_name'] .  "</td>";
	            echo   '<td>'   .   $row['bank_province']   .   '</td>';
	            echo   '<td>'   .   $row['bank_city']   .   '</td>';
	            echo   '<td>'   .   $row['b0']   .   '</td>';
	            echo   '<td>'   .   chr(28).$num    .   '</td>';
	            echo   '</tr>';
	        }
	        echo   '</table>';
//        }else{
//            $this->error('错误!');
//            exit;
//        }
    }


    public function financeDaoChuTwo1(){
        //导出WPS
        if ($_SESSION['UrlPTPass'] =='MyssGuanPaoYingTao' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
            $title   =   "数据库名:test,   数据表:test,   备份日期:"   .   date("Y-m-d   H:i:s");
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=test.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            //   输出标题
            echo   '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">'   .   $title   .   '</td></tr>';
            //   输出字段名
        echo   '<tr  align=center>';
            echo   "<td>会员编号</td>";
            echo   "<td>开会名</td>";
            echo   "<td>开户银行</td>";
            echo   "<td>银行账户</td>";
            echo   "<td>提现金额</td>";
            echo   "<td>提现时间</td>";
            echo   "<td>所有人的排序</td>";
            echo   '</tr>';
            //   输出内容
            $did = (int) $_GET['did'];
            $bonus = M ('bonus');

            $table_a = C('DB_PREFIX')."fck";
			$table_b = C('DB_PREFIX')."bonus";

            $map = ''.$table_b.'.b0>0 and '.$table_b.'.did='.$did;
             //查询字段
            $field   = ''.$table_b.'.id,'.$table_b.'.uid,'.$table_b.'.did,s_date,e_date,'.$table_b.'.b0,'.$table_b.'.b1,'.$table_b.'.b2,'.$table_b.'.b3';
            $field  .= ','.$table_b.'.b4,'.$table_b.'.b5,'.$table_b.'.b6,'.$table_b.'.b7,'.$table_b.'.b8,'.$table_b.'.b9,'.$table_b.'.b10';
            $field  .= ','.$table_a.'.user_id,'.$table_a.'.user_tel,'.$table_a.'.bank_card';
            $field  .= ','.$table_a.'.user_name,'.$table_a.'.user_address,'.$table_a.'.nickname,'.$table_a.'.user_phone,'.$table_a.'.bank_province,'.$table_a.'.user_tel';
            $field  .= ','.$table_a.'.user_code,'.$table_a.'.bank_city,'.$table_a.'.bank_name,'.$table_a.'.bank_address';
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $bonus->where($map)->count();//总页数
            $listrows = 1000000  ;//每页显示的记录数
            $page_where = '';//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $join = 'left join '.$table_a.' ON '.$table_b.'.uid='.$table_a.'.id';//连表查询
            $list = $bonus ->where($map)->field($field)->join($join)->Distinct(true)->order('id asc')->page($Page->getPage().','.$listrows)->select();
            $i = 0;
            foreach($list as $row)   {
                $i++;
                $num = strlen($i);
                if ($num == 1){
                	$num = '000'.$i;
                }elseif ($num == 2){
                	$num = '00'.$i;
                }elseif ($num == 3){
                    $num = '0'.$i;
                }
                $date = date('Y-m-d H:i:s',$row['rdt']);

                echo   '<tr align=center>';
                echo   "<td>'"   .   $row['user_id'].      '</td>';
                echo   '<td>'   .   $row['user_name']   .   '</td>';
                echo   "<td>"   .   $row['bank_name'] .  "</td>";
                echo   '<td>'   .   $row['bank_card']   .   '</td>';
                echo   '<td>'   .   $row['money']   .   '</td>';
                echo   '<td>'. $date .'</td>';
                echo   "<td>'"   .   $num    .   '</td>';
                echo   '</tr>';
            }
            echo   '</table>';
        }else{
            $this->error('错误!');
            exit;
        }
    }


   public function financeDaoChuTwo(){
        //导出WPS
//        if ($_SESSION['UrlPTPass'] =='MyssGuanPaoYingTao' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
            $title   =   "数据库名:test,   数据表:test,   备份日期:"   .   date("Y-m-d   H:i:s");
            header("Content-Type:   application/vnd.ms-excel");
            header("Content-Disposition:   attachment;   filename=test.xls");
            header("Pragma:   no-cache");
            header("Content-Type:text/html; charset=utf-8");
            header("Expires:   0");
            echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
            //   输出标题
            echo   '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">'   .   $title   .   '</td></tr>';
            //   输出字段名
        	echo   '<tr  align=center>';
            echo   "<td>银行卡号</td>";
            echo   "<td>姓名</td>";
            echo   "<td>银行名称</td>";
            echo   "<td>省份</td>";
            echo   "<td>城市</td>";
            echo   "<td>金额</td>";
            echo   "<td>所有人的排序</td>";
            echo   '</tr>';
            //   输出内容
            $did = (int) $_GET['did'];
            $bonus = M ('bonus');

            $table_a = C('DB_PREFIX')."fck";
			$table_b = C('DB_PREFIX')."bonus";

            $map = ''.$table_b.'.b0>0 and '.$table_b.'.did='.$did;
             //查询字段
            $field   = ''.$table_b.'.id,'.$table_b.'.uid,'.$table_b.'.did,s_date,e_date,'.$table_b.'.b0,'.$table_b.'.b1,'.$table_b.'.b2,'.$table_b.'.b3';
            $field  .= ','.$table_b.'.b4,'.$table_b.'.b5,'.$table_b.'.b6,'.$table_b.'.b7,'.$table_b.'.b8,'.$table_b.'.b9,'.$table_b.'.b10';
            $field  .= ','.$table_a.'.user_id,'.$table_a.'.user_tel,'.$table_a.'.bank_card';
            $field  .= ','.$table_a.'.user_name,'.$table_a.'.user_address,'.$table_a.'.nickname,'.$table_a.'.user_phone,'.$table_a.'.bank_province,'.$table_a.'.user_tel';
            $field  .= ','.$table_a.'.user_code,'.$table_a.'.bank_city,'.$table_a.'.bank_name,'.$table_a.'.bank_address';
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $bonus->where($map)->count();//总页数
            $listrows = 1000000  ;//每页显示的记录数
            $page_where = '';//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $join = 'left join '.$table_a.' ON '.$table_b.'.uid='.$table_a.'.id';//连表查询
            $list = $bonus ->where($map)->field($field)->join($join)->Distinct(true)->order('id asc')->page($Page->getPage().','.$listrows)->select();
            $i = 0;
            foreach($list as $row)   {
                $i++;
                $num = strlen($i);
                if ($num == 1){
                	$num = '000'.$i;
                }elseif ($num == 2){
                	$num = '00'.$i;
                }elseif ($num == 3){
                    $num = '0'.$i;
                }
                echo   '<tr align=center>';
                echo   "<td>'"   .   sprintf('%s',(string)chr(28).$row['bank_card'].chr(28)).      '</td>';
                echo   '<td>'   .   $row['user_name']   .   '</td>';
                echo   "<td>"   .   $row['bank_name'] .  "</td>";
                echo   '<td>'   .   $row['bank_province']   .   '</td>';
                echo   '<td>'   .   $row['bank_city']   .   '</td>';
                echo   '<td>'   .   $row['b0']   .   '</td>';
                echo   "<td>'"   .   $num    .   '</td>';
                echo   '</tr>';
            }
            echo   '</table>';
//        }else{
//            $this->error('错误!');
//            exit;
//        }
    }

   public function financeDaoChuTXT(){
        //导出TXT
        if ($_SESSION['UrlPTPass'] =='MyssPiPa' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
            //   输出内容
            $did = (int) $_GET['did'];
            $bonus = M ('bonus');
            $table_a = C('DB_PREFIX')."fck";
			$table_b = C('DB_PREFIX')."bonus";
            $map = 'xt_bonus.b0>0 and xt_bonus.did='.$did;
             //查询字段
            $field   = 'xt_bonus.id,xt_bonus.uid,xt_bonus.did,s_date,e_date,xt_bonus.b0,xt_bonus.b1,xt_bonus.b2,xt_bonus.b3';
            $field  .= ',xt_bonus.b4,xt_bonus.b5,xt_bonus.b6,xt_bonus.b7,xt_bonus.b8,xt_bonus.b9,xt_bonus.b10';
            $field  .= ',xt_fck.user_id,xt_fck.user_tel,xt_fck.bank_card';
            $field  .= ',xt_fck.user_name,xt_fck.user_address,xt_fck.nickname,xt_fck.user_phone,xt_fck.bank_province,xt_fck.user_tel';
            $field  .= ',xt_fck.user_code,xt_fck.bank_city,xt_fck.bank_name,xt_fck.bank_address';
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $bonus->where($map)->count();//总页数
            $listrows = 1000000  ;//每页显示的记录数
            $page_where = '';//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $join = 'left join xt_fck ON xt_bonus.uid=xt_fck.id';//连表查询
            $list = $bonus ->where($map)->field($field)->join($join)->Distinct(true)->order('id asc')->page($Page->getPage().','.$listrows)->select();
            $i = 0;
			$ko = "";
			$m_ko = 0;
            foreach($list as $row)   {
                $i++;
                $num = strlen($i);
                if ($num == 1){
                	$num = '000'.$i;
                }elseif ($num == 2){
                	$num = '00'.$i;
                }elseif ($num == 3){
                    $num = '0'.$i;
                }
				$ko .= $row['bank_card']."|".$row['user_name']."|".$row['bank_name']."|".$row['bank_province']."|".$row['bank_city']."|".$row['b0']."|".$num."\r\n";
				$m_ko += $row['b0'];
				$e_da = $row['e_date'];
            }
			$m_ko = $this -> _2Mal($m_ko,2);
			$content = $num."|".$m_ko."\r\n".$ko;

			header('Content-Type: text/x-delimtext;');
			header("Content-Disposition: attachment; filename=xt_".date('Y-m-d H:i:s',$e_da).".txt");
			header("Pragma: no-cache");
			header("Content-Type:text/html; charset=utf-8");
			header("Expires: 0");
			echo $content;
			exit;

        }else{
            $this->error('错误!');
            exit;
        }
    }

	public function adminFinanceTableList(){
		//奖金明细
		if ($_SESSION['UrlPTPass'] == 'MyssPiPa'|| $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){  //MyssShiLiu
			$times   = M('times');
			$history = M('history');

			$UID = (int) $_GET['uid'];
			$did = (int) $_REQUEST['did'];

			$where = array();
			if (!empty($did)){
				$rs = $times -> find($did);
				if($rs){
					$rs_day = $rs['benqi'];
					$where['pdt'] = array(array('gt',$rs['shangqi']),array('elt',$rs_day));  //大于上期,小于等于本期
				}else{
					$this->error('错误!');
					exit;
				}
			}
			$where['uid'] = $UID;
			$where['type'] = 1;

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $history->where($where)->count();//总页数
//            dump($history);exit;
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'did=' . (int) $_REQUEST['did'];//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $history->where($where)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

            $fee   = M ('fee');    //参数表
			$fee_rs = $fee->field('s18')->find();
			$fee_s7 = explode('|',$fee_rs['s18']);
			$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组

			$this->display ('adminFinanceTableList');
			}else{
			$this->error ('错误!');
			exit;
		}
	}


//	//奖金明细
//	public function adminFinanceTableList($Urlsz=0){
//		if ($_SESSION['UrlPTPass'] == 'MyssPiPa' || $_SESSION['UrlPTPass'] == 'MyssMiHouTao'){
//			$history = M('history');
//			$UID = (int) $_REQUEST['UID'];
//			$RDT = (int) $_REQUEST['RDT'];
//			$PDT = (int) $_REQUEST['PDT'];
//			if (!empty($RDT) && !empty($PDT)){
//				//$map['pdt'] = $PDT;
//				$map['pdt'] = array(array('gt',$RDT),array('elt',$PDT));
//			}
//			if (!empty($UID)){
//				$map['uid'] = $UID;
//			}
//			$map['allp'] = 0;
//
//            $field  = '*';
//            //=====================分页开始==============================================
//            import ( "@.ORG.ZQPage" );  //导入分页类
//            $count = $history->where($map)->count();//总页数
//       	  $listrows = C('ONE_PAGE_RE');//每页显示的记录数
//            $page_where = 'UID=' . $UID .'&PDT=' . $PDT;//分页条件
//            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
//            //===============(总页数,每页显示记录数,css样式 0-9)
//            $show = $Page->show();//分页变量
//            $this->assign('page',$show);//分页变量输出到模板
//            $list = $history->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
//            $this->assign('list',$list);//数据输出到模板
//            //=================================================
//
//			$this->display ();
//		}else{
//			$this->error ('错误!');
//			exit;
//		}
//	}

	//===============================================提现管理
	public function adminCurrency(){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanPaoYingTao'){
			$tiqu = M ('tiqu');
			$fck = M('fck');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				$map['user_id'] = array('like',"%".$UserID."%");
			}

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $tiqu->where($map)->count();//总页数
//       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
       		$listrows = 20;	//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $tiqu->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();


            $i=0;
            foreach($list as $vvv){

            	$uuid = $vvv['uid'];
            	$urs = $fck->where('id='.$uuid)->field('bank_address')->find();
            	if($urs){
            		$list[$i]['bank_address'] = $urs['bank_address'];
            	}
            	$i++;
            }
			$this->assign('list',$list);//数据输出到模板
			//=================================================
			$this->display('adminCurrency');
		}else{
			$this->error('错误!');
			exit;
		}
	}
	//处理提现
	public function adminCurrencyAC(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST['tabledb'];
		$fck = M ('fck');
//	    if (!$fck->autoCheckToken($_POST)){
//            $this->error('页面过期，请刷新页面！');
//            exit;
//        }
		if (empty($PTid)){
			$bUrl = __URL__.'/adminCurrency';
			$this->_box(0,'请选择！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '确认':
				$this->_adminCurrencyConfirm($PTid);
				break;
			case '删除':
				$this->_adminCurrencyDel($PTid);
				break;
		default:
			$bUrl = __URL__.'/adminCurrency';
			$this->_box(0, '没有该记录！', $bUrl,1);
			break;
		}
	}
	//导出excel
	public function DaoChu(){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanPaoYingTao'){
			$title   =   "数据库名:test,   数据表:test,   备份日期:"   .   date("Y-m-d   H:i:s");
			header("Content-Type:   application/vnd.ms-excel");
			header("Content-Disposition:   attachment;   filename=Cash.xls");
			header("Pragma:   no-cache");
			header("Content-Type:text/html; charset=utf-8");
			header("Expires:   0");
			echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
			//   输出标题
			echo   '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">'   .   $title   .   '</td></tr>';
			//   输出字段名
			echo   '<tr >';
			echo   "<td>会员编号</td>";
			echo   "<td>开户名</td>";
			echo   "<td>开户银行</td>";
			echo   "<td>银行帐号</td>";
			echo   "<td>提现金额</td>";
			echo   "<td>实发金额</td>";
			echo   "<td>提现时间</td>";
			echo   "<td>状态</td>";
			echo   '</tr>';
			//   输出内容
			$tiqu = M ('tiqu');
			$trs = $tiqu->select();
			foreach($trs as $row)   {

			if ($row['is_pay']==0){
			    $isPay = '未确认';
			}else{
			    $isPay = '已确认';
			}
			echo   '<tr>';
			echo   '<td>'   .   $row['user_id']   .   '</td>';
			echo   '<td>'   .   $row['user_name']   .   '</td>';
			echo   '<td>'   .   $row['bank_name']   .   '</td>';
			echo   "<td>"  .  chr(28).$row['bank_card'] .  "</td>";
			echo   '<td>'   .   $row['money']   .   '</td>';
			echo   '<td>'   .   $row['money_two']   .   '</td>';
			echo   '<td>'   .   date('Y-m-d',$row['rdt'])   .   '</td>';
			echo   '<td>'   .  $isPay    .   '</td>';
			echo   '</tr>';
			}
			echo   '</table>';
			}else{
				$this->error('错误!');
				exit;
			}
	}

	public function DaoChu1(){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanPaoYingTao'){
			$title   =   "数据库名:test,   数据表:test,   备份日期:"   .   date("Y-m-d   H:i:s");
			header("Content-Type:   application/vnd.ms-excel");
			header("Content-Disposition:   attachment;   filename=test.xls");
			header("Pragma:   no-cache");
			header("Content-Type:text/html; charset=utf-8");
			header("Expires:   0");
			echo   '<table   border="1"   cellspacing="2"   cellpadding="2"   width="50%"   align="center">';
			//   输出标题
			echo   '<tr   bgcolor="#cccccc"><td   colspan="3"   align="center">'   .   $title   .   '</td></tr>';
			//   输出字段名
			echo   '<tr >';
			echo   "<td>会员编号</td>";
			echo   "<td>开户名</td>";
			echo   "<td>开户银行</td>";
			echo   "<td>银行帐号</td>";
			echo   "<td>提现金额</td>";
			echo   "<td>实发金额</td>";
			echo   "<td>提现时间</td>";
			echo   "<td>状态</td>";
			echo   '</tr>';
			//   输出内容
			$tiqu = M ('tiqu');
			$trs = $tiqu->select();
			foreach($trs as $row)   {

			if ($row['is_pay']==0){
			    $isPay = '未确认';
			}else{
			    $isPay = '已确认';
			}
			echo   '<tr>';
			echo   '<td>'   .   $row['user_id']   .   '</td>';
			echo   '<td>'   .   $row['user_name']   .   '</td>';
			echo   '<td>'   .   $row['bank_name']   .   '</td>';
			echo   "<td>,"  .  chr(28).$row['bank_card'] .  "</td>";
			echo   '<td>'   .   $row['money']   .   '</td>';
			echo   '<td>'   .   $row['money_two']   .   '</td>';
			echo   '<td>'   .   date('Y-m-d',$row['rdt'])   .   '</td>';
			echo   '<td>'   .  $isPay    .   '</td>';
			echo   '</tr>';
			}
			echo   '</table>';
			}else{
				$this->error('错误!');
				exit;
			}
	}


	//====================================================确认提现
	private function _adminCurrencyConfirm($PTid){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanPaoYingTao'){
			$tiqu = M ('tiqu');
			$fck = M('fck');//
			$where = array();
			$where['is_pay'] = 0;               //未审核的申请
			$where['id'] = array ('in',$PTid);  //所有选中的会员ID
			$rs = $tiqu->where($where)->select();  //tiqu表要通过的申请记录数组
			$history = M('history');
			$data = array();
			$fck_where = array();
			$nowdate = strtotime(date('c'));
			foreach($rs as $rss){
				$fck_where['id'] = $rss['uid'];
				$rsss = $fck->where($fck_where)->field('id,user_id,agent_use')->find();
				if ($rsss){
					$result = $tiqu->execute("UPDATE `xt_tiqu` set `is_pay`=1 where `id`=".$rss['id']);
					if($result){

						//插入历史表
						$data = array();
						$data['uid']			= $rsss['id'];//提现会员ID
						$data['user_id']		= $rsss['user_id'];
						$data['action_type']	= 18;
						$data['pdt']			= mktime();//提现时间
						$data['epoints']		= $rss['money'];//进出金额
						$data['allp']			= $rss['money_two'];
						$data['bz']				= '18';//备注
						$data['type']			= 2;//1 转帐  2 提现
						$history->create();
						$history->add($data);
						unset($data);

						$fck->execute("update __TABLE__ set zsq=zsq+".$rss['money']." where `id`=".$rss['uid']);
					}
				}else{
					$tiqu->execute("UPDATE `xt_tiqu` set `is_pay`=1 where `id`=".$rss['id']);
				}
			}
			unset($tiqu,$fck,$where,$rs,$history,$data,$nowdate,$fck_where);
			$bUrl = __URL__.'/adminCurrency';
			$this->_box(1,'确认提现！',$bUrl,1);
		}else{
			$this->error('错误!');
			exit;
		}
	}
	//删除提现
	private function _adminCurrencyDel($PTid){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanPaoYingTao'){
			$tiqu = M ('tiqu');
			$where = array();
//			$where['is_pay'] = 0;
			$where['id'] = array ('in',$PTid);
			$trs = $tiqu->where($where)->select();
			$fck = M ('fck');
			foreach ($trs as $vo){
				$isok = $vo['is_pay'];
				if($isok==0){
					$fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$vo['money']} WHERE id={$vo['uid']}");
				}
			}
			$rs = $tiqu->where($where)->delete();
			if ($rs){
				$bUrl = __URL__.'/adminCurrency';
				$this->_box(1,'删除成功！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminCurrency';
				$this->_box(0,'删除失败！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误!');
			exit;
		}
	}

//	// ===========================================修改资料
//	public function adminuserDataSave() {
//		if ($_SESSION['UrlPTPass'] == 'MyssGuanShuiPuTao' || $_SESSION['UrlPTPass'] == 'MyssGuanXiGua'){
//			$fck	 =	 M('fck');
//			if(!$fck->autoCheckToken($_POST)) {
//				$this->error('页面过期，请刷新页面！');
//			}
//			$data = array();
//			$data['nickname']         = $_POST['NickName'];
//			$data['bank_name']        = $_POST['BankName'];
//			$data['bank_card']        = $_POST['BankCard'];
//			$data['user_name']        = $_POST['UserName'];
//			$data['bank_province']    = $_POST['BankProvince'];
//			$data['bank_city']        = $_POST['BankCity'];
//			$data['bank_address']     = $_POST['BankAddress'];
//			$data['user_code']        = $_POST['UserCode'];
//			$data['user_address']     = $_POST['UserAddress'];
//			$data['user_post']        = $_POST['UserPost'];
//			$data['user_tel']         = $_POST['UserTel'];
//			$data['is_lock']          = $_POST['isLock'];
//			$data['id']               = $_POST['ID'];
//			//$data['agent_use'] = $_POST['AgentUse'];
//			//$data['agent_cash'] = $_POST['AgentCash'];
//
//			$where_nic['nickname'] = $data['nickname'];
//			$rs = $fck -> where($where_nic) -> find();
//			if($rs){
//				if($rs['id'] != $data['id']){
//					$this->error ('该会员名已经存在!');
//					exit;
//				}
//			}
////			echo 3;
////			exit;
//			$where = array();
//			$id = $_SESSION[C('USER_AUTH_KEY')];
//			$where['id'] = $data['id'];
//			$frs = $fck->where($where)->field('id,user_id,password,passopen')->find();
//			if ($frs){
//				if ($_POST['Password']!= $frs['password']){
//					$data['password']   = md5($_POST['Password']);
//					$data['additional'] = trim($_POST['Password']);  //一级密码不加密
//					if ($id == $data['id']){
//						$_SESSION['login_sf_list_u'] = md5($frs['user_id'].'wodetp_new_1012!@#'.$data['password'].$_SERVER['HTTP_USER_AGENT']);
//					}
//				}
//				if ($_POST['PassOpen'] != $frs['passopen']){
//					$data['passopen']  = md5($_POST['PassOpen']);
//					$data['encourage'] = trim($_POST['PassOpen']);  //二级密码不加密
//				}
//			}
//
//			$result	=	$fck->save($data);
//			if($result) {
//				$bUrl = __URL__.'/adminMenber';
//				$this->_box(1,'资料修改！',$bUrl,1);
//				exit;
//			}else{
//				$bUrl = __URL__.'/adminMenber';
//				$this->_box(1,'资料修改！',$bUrl,1);
//			}
//		}else{
//			$bUrl = __URL__.'/adminMenber';
//			$this->_box(0,'资料修改！',$bUrl,1);
//			exit;
//		}
//	}
	//==========================================比例设置
	public function adminParameter(){
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuo'){
			//set_time_limit(0);//是页面不过期
            //$this->_upFck();
			$User = M ('fee');
			$where = array();
			$where['id'] = 1;
			$field = '*';
			$rs = $User->where($where)->field($field)->find();
			if ($rs){
				$this->assign('vo',$rs);
				$this->display('adminParameter');
			}else{
				$this->error('错误!');
				exit;
			}
		}else{
			$this->error('错误!');
			exit;
		}
	}
	//===================================保存比例设置
	public function adminParameterSave(){
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuo'){
			$fee = M ('fee');
			$fee -> create();
			$fee -> save();

			$bUrl = __URL__.'/adminParameter';
			$this->_box(1,'比例设置！',$bUrl,1);
			exit;
		}else{
			$this->error('错误');
			exit;
		}
	}
	//参数设置
	public function setParameter(){
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCP'){
			$fee = M ('fee');
			$fee_rs = $fee -> find();
			$fee_s1  = $fee_rs['s1'];
			$fee_s2  = $fee_rs['s2'];
			$fee_s3  = $fee_rs['s3'];
			$fee_s4  = $fee_rs['s4'];
			$fee_s5  = $fee_rs['s5'];
			$fee_s6  = $fee_rs['s6'];
			$fee_s7  = $fee_rs['s7'];
			$fee_s8  = $fee_rs['s8'];
			$fee_s9  = $fee_rs['s9'];
			$fee_s10 = $fee_rs['s10'];
			$fee_s11 = $fee_rs['s11'];
			$fee_s12 = $fee_rs['s12'];
			$fee_s13 = $fee_rs['s13'];
			$fee_s14 = $fee_rs['s14'];
			$fee_s15 = $fee_rs['s15'];
			$fee_s16 = $fee_rs['s16'];
			$fee_s17 = $fee_rs['s17'];
			$fee_s18 = $fee_rs['s18'];

			$fee_str2 = $fee_rs['str2'];
			$fee_str3 = $fee_rs['str3'];
			$fee_str4 = $fee_rs['str4'];
			$fee_str5 = $fee_rs['str5'];
			$fee_str6 = $fee_rs['str6'];
			$fee_str7 = $fee_rs['str7'];

			$fee_str21 = $fee_rs['str21'];
			$fee_str22 = $fee_rs['str22'];
			$fee_str23 = $fee_rs['str23'];

			$fee_str28 = $fee_rs['str28'];
			$fee_str29 = $fee_rs['str29'];

			$fee_str99 = $fee_rs['str99'];

//			$fee_s20 = explode('|',$fee_rs['s20']);
			$this -> assign('fee_s1',$fee_s1);
			$this -> assign('fee_s2',$fee_s2);
			$this -> assign('fee_s3',$fee_s3);
			$this -> assign('fee_s4',$fee_s4);
			$this -> assign('fee_s5',$fee_s5);
			$this -> assign('fee_s6',$fee_s6);
			$this -> assign('fee_s7',$fee_s7);
			$this -> assign('fee_s8',$fee_s8);
			$this -> assign('fee_s9',$fee_s9);
			$this -> assign('fee_s10',$fee_s10);
			$this -> assign('fee_s11',$fee_s11);
			$this -> assign('fee_s12',$fee_s12);
			$this -> assign('fee_s13',$fee_s13);
			$this -> assign('fee_s14',$fee_s14);
			$this -> assign('fee_s15',$fee_s15);
			$this -> assign('fee_s16',$fee_s16);
			$this -> assign('fee_s17',$fee_s17);
			$this -> assign('fee_s18',$fee_s18);
//			$this -> assign('fee_s20',$fee_s20);
			$this -> assign('fee_i1',$fee_rs['i1']);
			$this -> assign('fee_i2',$fee_rs['i2']);
			$this -> assign('fee_i3',$fee_rs['i3']);
			$this -> assign('fee_i4',$fee_rs['i4']);
			$this -> assign('fee_id',$fee_rs['id']);  //记录ID

			$this -> assign('fee_str2',$fee_str2);
			$this -> assign('fee_str3',$fee_str3);
			$this -> assign('fee_str4',$fee_str4);
			$this -> assign('fee_str5',$fee_str5);
			$this -> assign('fee_str6',$fee_str6);
			$this -> assign('fee_str7',$fee_str7);

			$this -> assign('fee_str21',$fee_str21);
			$this -> assign('fee_str22',$fee_str22);
			$this -> assign('fee_str23',$fee_str23);

			$this -> assign('fee_str28',$fee_str28);
			$this -> assign('fee_str29',$fee_str29);
			$this -> assign('fee_str99',$fee_str99);

			$this->display('setParameter');
		}else{
			$this->error('错误!');
			exit;
		}
	}
	public function setParameterSave(){
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCP'){
			$fee = M ('fee');
			$fck = M ('fck');
			$rs = $fee -> find();

//			$s18 = (int) trim($_POST['s18']);
//			$i1  = (int) trim($_POST['i1']);
//			if(empty($s18) or empty($i1)){
//				$this->error('请输入完整的参数，否则系统不能正常运行!');
//				exit;
//			}
//
//			$arr = array(3,11,12,13,14,17,20);
//			foreach($arr as $i){
//				$i = 's'. $i;
//				$str  = $_POST[$i];
//				foreach($str as $s){
//					if($i != 's20'){  //s20为各网名称可以不为数字
//						$s = trim($s);
//					}
//					if(empty($s)){
//						$this->error('请输入完整的参数，否则系统不能正常运行 !');
//			}}}

			$i1  = $_POST['i1'];
			$i2  = $_POST['i2'];
			$i3  = $_POST['i3'];
			$i4  = $_POST['i4'];
//			$s2  = $_POST['s2'];
//			$s3  = $_POST['s3'];
//			$s4  = $_POST['s4'];
//			$s5  = $_POST['s5'];
//			$s6  = $_POST['s6'];
//			$s8  = $_POST['s8'];
//			$s9  = $_POST['s9'];
//			$s10 = $_POST['s10'];
//			$s11 = $_POST['s11'];
//			$s12 = $_POST['s12'];
//			$s13 = $_POST['s13'];
//			$s14 = $_POST['s14'];
//			$s15 = $_POST['s15'];
//			$s16 = $_POST['s16'];
//			$s17 = $_POST['s17'];
//			$s18 = $_POST['s18'];
//			$s20 = $_POST['s20'];
//
			$where = array();
			$where['id'] = (int) $_POST['id'];
			$data = array();
			$data['i1']  = trim($i1);
			$data['i2']  = trim($i2);
			$data['i3']  = trim($i3);
			$data['i4']  = trim($i4);
			//$data['s3']  = trim($s3[0]) .'|'. trim($s3[1]) .'|'. trim($s3[2]) .'|'. trim($s3[3]) .'|'. trim($s3[4]) .'|'. trim($s3[5]);
			//$data['s5']  = trim($s5[0]) .'|'. trim($s5[1]) .'|'. trim($s5[2]) ;
//			$data['s2']  =  trim($s2);
//			$data['s3']  =  trim($s3);
//			$data['s4']  =  trim($s4);
//			$data['s5']  =  trim($s5);
//			$data['s6']  =  trim($s6);
//			$data['s8']  =  trim($s8);
//			$data['s9']  =  trim($s9);
//			$data['s10']  =  trim($s10);
//			$data['s11']  =  trim($s11);
//			$data['s12']  =  trim($s12);
//			$data['s13']  =  trim($s13);
//			$data['s14']  =  trim($s14);
//			$data['s15']  =  trim($s15);
//			$data['s16']  =  trim($s16);
//			$data['s17']  =  trim($s17);
			//$data['s10'] = trim($s10[0]) .'|'. trim($s10[0]);
			//$data['s11'] = trim($s11[0]) .'|'. trim($s11[1]) .'|'. trim($s11[2]) .'|'. trim($s11[3]) .'|'. trim($s11[4]) .'|'. trim($s11[5]);
			//$data['s12'] = trim($s12[0]) .'|'. trim($s12[1]) .'|'. trim($s12[2]) .'|'. trim($s12[3]) .'|'. trim($s12[4]) .'|'. trim($s12[5]);
			//$data['s13'] = trim($s13[0]) .'|'. trim($s13[1]) .'|'. trim($s13[2]);
			//$data['s14'] = trim($s14[0]) .'|'. trim($s14[1]) .'|'. trim($s14[2]) .'|'. trim($s14[3]) .'|'. trim($s14[3]) .'|'. trim($s14[3]) .'|'. trim($s14[3]) .'|'. trim($s14[3]) .'|'. trim($s14[3]) .'|'. trim($s14[3]) .'|'. trim($s14[3]);
			//$data['s15'] = trim($s15[0]) .'|'. trim($s15[0]);
			//$data['s16'] = trim($s16[0]) .'|'. trim($s16[0]);
			//$data['s17'] = trim($s17[0]) .'|'. trim($s17[1]);
			//$data['s18'] = '0000|0000|'. trim($s18);
			//$data['s20']  = trim($s20[0]) .'|'. trim($s20[1]) .'|'. trim($s20[2]) .'|'. trim($s20[3]) .'|'. trim($s20[4]) .'|'. trim($s20[5]);

			for($j=1;$j<=10;$j++){
                $arr_rs[$j] = $_POST['i'.$j];
            }

            $s_sql2 = "";
            for($j=1;$j<=10;$j++){
                if ($arr_rs[$j] != ''){
                	if(empty($s_sql2)){
                    	$s_sql2 = 'i'.$j . "='{$arr_rs[$j]}'";
                	}else{
                		$s_sql2 .= ',i'.$j . "='{$arr_rs[$j]}'";
                	}
                }
            }


			for($i=1;$i<=35;$i++){
                $arr_s[$i] = $_POST['s'.$i];
            }

            $s_sql = "";
            for($i=1;$i<=35;$i++){
                if (empty($arr_s[$i])==false||strlen($arr_s[$i])>0){
                	if(empty($s_sql2)){
                    	$s_sql = 's'.$i . "='{$arr_s[$i]}'";
                	}else{
                		$s_sql .= ',s'.$i . "='{$arr_s[$i]}'";
                	}

                }
            }

            for($i=1;$i<=40;$i++){
                $arr_sts[$i] = $_POST['str'.$i];
            }
            $str_sql = "";
            for($i=1;$i<=40;$i++){
                if (strlen(trim($arr_sts[$i]))>0){
                	if(empty($s_sql2)&&empty($s_sql)){
                    	$str_sql = 'str'.$i . "='{$arr_sts[$i]}'";
                	}else{
                		$str_sql .= ',str'.$i . "='{$arr_sts[$i]}'";
                	}
                }
            }

            $str99=trim($_POST['str99']);
			$ttst_sql = ',str99="'.$str99.'"';


			$fee->execute("update __TABLE__ SET ".$s_sql2 . $s_sql. $str_sql. $ttst_sql  ."  where `id`=1");
			$fee -> where($where) -> data($data) -> save();
			$this->success('参数设置！');
			exit;
		}else{
			$this->error('错误!'); //12345678901112131417181920s3
			exit;
		}
	}

//参数设置
	public function setParameter_B(){
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCPB'){
			$fee = M ('fee');
			$fee_rs = $fee -> find();

			$fee_str21 = $fee_rs['str21'];
			$fee_str22 = $fee_rs['str22'];
			$fee_str23 = $fee_rs['str23'];

			$this -> assign('fee_str21',$fee_str21);
			$this -> assign('fee_str22',$fee_str22);
			$this -> assign('fee_str23',$fee_str23);

			$this->display();
		}else{
			$this->error('错误!');
			exit;
		}
	}
	public function setParameterSave_B(){
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCPB'){
			$fee = M ('fee');
			$fck = M ('fck');
			$rs = $fee -> find();

			$where = array();
			$where['id'] = (int) $_POST['id'];
            for($i=1;$i<=40;$i++){
                $arr_sts[$i] = $_POST['str'.$i];
            }
            $str_sql = "";
            for($i=1;$i<=40;$i++){
                if (strlen(trim($arr_sts[$i]))>0){
                	if(empty($str_sql)){
                    	$str_sql = 'str'.$i . "='{$arr_sts[$i]}'";
                	}else{
                		$str_sql .= ',str'.$i . "='{$arr_sts[$i]}'";
                	}
                }
            }


			$fee->execute("update __TABLE__ SET ". $str_sql."  where `id`=1");
			$this->success('首页图片设置！');
			exit;
		}else{
			$this->error('错误!');
			exit;
		}
	}

	public function MenberBonus(){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssPingGuoCP'){
			$fck = M('fck');
			$UserID = trim($_REQUEST['UserID']);
			$ss_type = (int) $_REQUEST['type'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			$map['is_pay'] = 1;
            //查询字段
            $field  = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,f4,cpzj,pdt,u_level,zjj,agent_use,is_lock,f3,b3';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID . '&type=' . $ss_type;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            foreach ($list as $vo){
                $voo[$vo['id']] = $HYJJ[$vo['u_level']];
            }
            $this->assign('voo',$voo);//会员级别
            $this->assign('list',$list);//数据输出到模板
            //=================================================


			$title = '会员管理';
			$this->assign('title',$title);
			$this->display ('MenberBonus');
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function MenberBonusSave(){
		if($_SESSION['UrlPTPass'] == 'MyssPingGuoCP'){
			$fck = M ('fck');
			$fee_rs = M ('fee') -> find();
			$fee_s7 = explode('|',$fee_rs['s7']);

			$date = strtotime($_POST['date']);
			$lz   = (int) $_POST['lz'];
			$lzbz = $_POST['lzbz'];

			$userautoid = (int) $_POST['userautoid'];

			if($lz <= 0){
				$this->error('请录入正确的劳资金额!');
				exit;
			}

			$rs = $fck -> field('user_id,id') -> find($userautoid);
			if($rs){
				$fck -> query("update __TABLE__ set b3=b3+$lz where id=". $userautoid);
				$this -> input_bonus_2($rs['user_id'],$rs['id'],$fee_s7[2],$lz,$lzbz,$date);  //写进明细

				$bUrl = __URL__.'/MenberBonus';
				$this->_box(1,'劳资录入！',$bUrl,1);
			}else{
				$this->error('数据错误!');
				exit;
			}
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function adminJB(){
		//=====================================后台金币中心管理
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaJB'){
			$fck = M('fck');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}

//			$map1['is_jb'] = array('gt',0);
//			$map1['sq_jb'] = array('gt',0);
//			$map1['_logic'] = 'or';
//			$map['_complex']    = $map1;
			$map['_string']='is_jb>0 or sq_jb>0';
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
            $field  = 'id,user_id,is_agent,idt,shoplx,adt,user_name,nickname,agent_use,agent_cash,agent_max,is_del,shop_id,is_jb,sq_jb,jb_idate,jb_sdate';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
//            dump($fck);exit;
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
//            dump($fck);exit;
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ('adminJB');
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function adminJBAC(){  //审核金币中心申请
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$XGid = $_POST['tabledb'];
		$agentmax = $_POST['agentmax'];
		$fck = M ('fck');

        unset($fck);
		if (!isset($XGid) || empty($XGid)){
			$bUrl = __URL__.'/adminJB';
			$this->_box(0,'请选择会员！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '确认';
				$this->_adminJBConfirm($XGid,$agentmax);
				break;
			case '删除';
				$this->_adminJBDel($XGid);
				break;
		default;
			$bUrl = __URL__.'/adminJB';
			$this->_box(0,'没有该会员！',$bUrl,1);
			break;
		}
	}

	private function _adminJBConfirm($XGid=0,$money=0){
		//==========================================确认申请金币中心
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaJB'){
			if (empty($money)){
				$this->error('增加电子币金额错误!');
				exit;
			}
//			dump($money);exit;
			$fck  = M ('fck');
			$where['id'] = array ('in',$XGid);
//			$where['sq_jb'] = 1;
			$rs = $fck->where($where)->field('*')->select();
//			dump($fck);exit;
			$data = array();
			$history = M ('history');
            $rewhere = array();
//          $nowdate = strtotime(date('c'));
            $nowdate = time();
			foreach($rs as $rss){

				$fck ->query("UPDATE __TABLE__ SET is_jb=1,sq_jb=0,jb_idate=$nowdate,jb_sdate=0,agent_max=0,agent_cash=agent_cash+{$money} where id=".$rss['id']);  //开通
			}

			unset($fck,$where,$rs,$history,$data,$rewhere);
			$bUrl = __URL__.'/adminJB';
			$this->_box(1,'确认申请！',$bUrl,1);
			exit;
		}else{
			$this->error('错误！');
			exit;
		}
	}

	private function _adminJBDel($XGid=0){
		//=======================================删除申请金币中心信息
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaJB'){
			$fck = M ('fck');
			$rewhere = array();
			$where['sq_jb'] = array('gt',0);
			$where['id'] = array ('in',$XGid);
			$rs = $fck -> where($where) -> select();
			foreach ($rs as $rss){
				$fck ->query("UPDATE __TABLE__ SET sq_jb=0,jb_sdate=0,agent_max=0 where id = ".$rss['id']);
			}

			unset($fck,$where,$rs,$region,$rewhere);
			$bUrl = __URL__.'/adminJB';
			$this->_box('操作成功','删除申请！',$bUrl,1);
			exit;
		}else{
			$this->error('错误!');
			exit;
		}
	}

	public function adminAgents(){
		//=====================================后台报单中心管理
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGua'){
			$fck = M('fck');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			//$map['is_del'] = array('eq',0);
			$map['is_agent'] = array('gt',0);
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ('adminAgents');
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function adminAgentsShow(){
		//查看详细信息
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGua'){
			$fck = M('fck');
			$ID = (int) $_GET['Sid'];
			$where = array();
			$where['id'] = $ID;
			$srs = $fck->where($where)->field('user_id,verify')->find();
			$this->assign('srs',$srs);
			unset($fck,$where,$srs);
			$this->display ('adminAgentsShow');
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function adminAgentsAC(){  //审核报单中心(报单中心)申请
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$XGid = $_POST['tabledb'];
		$fck = M ('fck');
//	    if (!$fck->autoCheckToken($_POST)){
//            $this->error('页面过期，请刷新页面！');
//            exit;
//        }
        unset($fck);
		if (!isset($XGid) || empty($XGid)){
			$bUrl = __URL__.'/adminAgents';
			$this->_box(0,'请选择会员！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '确认';
				$this->_adminAgentsConfirm($XGid);
				break;
			case '删除';
				$this->_adminAgentsDel($XGid);
				break;
		default;
			$bUrl = __URL__.'/adminAgents';
			$this->_box(0,'没有该会员！',$bUrl,1);
			break;
		}
	}


	private function _adminAgentsConfirm($XGid=0){
		//==========================================确认申请报单中心
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGua'){
			$fck  = D ('Fck');
			$where['id'] = array ('in',$XGid);
			$where['is_agent'] = 1;
			$rs = $fck->where($where)->field('*')->select();

			$data = array();
			$history = M ('history');
            $rewhere = array();
//          $nowdate = strtotime(date('c'));
            $nowdate = time();
            $jiesuan = 0;
			foreach($rs as $rss){

				$myreid = $rss['re_id'];
				$shoplx = $rss['shoplx'];

				$data['user_id'] = $rss['user_id'];
				$data['uid'] = $rss['uid'];
				$data['action_type'] = '申请成为报单中心';
				$data['pdt'] = $nowdate;
				$data['epoints'] = $rss['agent_no'];
				$data['bz'] = '申请成为报单中心';
				$data['did'] = 0;
				$data['allp'] = 0;
				$history ->add($data);

				$fck ->query("UPDATE __TABLE__ SET is_agent=2,adt=$nowdate,agent_max=0 where id=".$rss['id']);  //开通
			}
			unset($fck,$where,$rs,$history,$data,$rewhere);
			$bUrl = __URL__.'/adminAgents';
			$this->_box(1,'确认申请！',$bUrl,1);
			exit;
		}else{
			$this->error('错误！');
			exit;
		}
	}

	private function _adminAgentsDel($XGid=0){
		//=======================================删除申请报单中心信息
		if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGua'){
			$fck = M ('fck');
			$rewhere = array();
			$where['is_agent'] = array('gt',0);
			$where['id'] = array ('in',$XGid);
			$rs = $fck -> where($where) -> select();
			foreach ($rs as $rss){
				$fck ->query("UPDATE __TABLE__ SET is_agent=0,idt=0,adt=0,new_agent=0,shoplx=0,shop_a='',shop_b='' where id>1 and id = ".$rss['id']);
			}

//			$shop->where($where)->delete();
			unset($fck,$where,$rs,$rewhere);
			$bUrl = __URL__.'/adminAgents';
			$this->_box('操作成功','删除申请！',$bUrl,1);
			exit;
		}else{
			$this->error('错误!');
			exit;
		}
	}


	public function admin_zy(){
		//=====================================专营店管理
		//列表过滤器，生成查询Map对象
		if ($_SESSION['UrlPTPass'] == 'MyssGuanzy'){
			$fck = M('fck');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_id'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
				$UserID = urlencode($UserID);
			}
			//$map['is_del'] = array('eq',0);
			$map['is_zy'] = array('gt',0);
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ();
			return;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	public function admin_zyAC(){  //专营店处理
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$XGid = $_POST['tabledb'];
		$fck = M ('fck');
        unset($fck);
		if (!isset($XGid) || empty($XGid)){
			$bUrl = __URL__.'/admin_zy';
			$this->_box(0,'请选择专营店！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '确认';
				$this->_admin_zyConfirm($XGid);
				break;
			case '删除';
				$this->_admin_zyDel($XGid);
				break;
		default;
			$bUrl = __URL__.'/admin_zy';
			$this->_box(0,'没有该专营店！',$bUrl,1);
			break;
		}
	}


	private function _admin_zyConfirm($XGid=0){
		//==========================================确认专营店
		if ($_SESSION['UrlPTPass'] == 'MyssGuanzy'){
			$fck  = D ('Fck');
			$where['id'] = array ('in',$XGid);
			$where['is_zy'] = 1;
			$rs = $fck->where($where)->field('*')->select();

			$data = array();
			$history = M ('history');
            $rewhere = array();
//          $nowdate = strtotime(date('c'));
            $nowdate = mktime();
            $jiesuan = 0;
			foreach($rs as $rss){

				$myreid = $rss['re_id'];
				$shoplx = $rss['shoplx'];

				$fck ->query("UPDATE __TABLE__ SET is_zy=2,zyq_date=$nowdate where id=".$rss['id']);  //开通
			}
			unset($fck,$where,$rs,$history,$data,$rewhere);
			$bUrl = __URL__.'/admin_zy';
			$this->_box(1,'确认专营店申请成功！',$bUrl,1);
			exit;
		}else{
			$this->error('错误！');
			exit;
		}
	}

	private function _admin_zyDel($XGid=0){
		//=======================================删除专营店
		if ($_SESSION['UrlPTPass'] == 'MyssGuanzy'){
			$fck = M ('fck');
			$rewhere = array();
			$where['is_zy'] = array('gt',0);
			$where['id'] = array ('in',$XGid);
			$rs = $fck -> where($where) -> select();
			foreach ($rs as $rss){
				$fck ->query("UPDATE __TABLE__ SET is_zy=0,zyi_date=0,zyq_date=0 where id = ".$rss['id']);
			}
			unset($fck,$where,$rs,$rewhere);
			$bUrl = __URL__.'/admin_zy';
			$this->_box(1,'专营店申请删除成功！',$bUrl,1);
			exit;
		}else{
			$this->error('错误!');
			exit;
		}
	}


	public function delTable(){
		//清空数据库===========================
		$this->display();
	}
	public function delTableExe(){
		$fck = M ('fck');
	    if (!$fck->autoCheckToken($_POST)){
            $this->error('页面过期，请刷新页面！');
            exit;
        }
        unset($fck);
		$this->_delTable();
		exit;
	}

	public function adminClearing(){
		if ($_SESSION['UrlPTPass'] == 'MyssBaiGuoJS'){
			$times = M('times');
			$trs = $times->where('type=0')->order('id desc')->find();
			if (!$trs){
				$trs['benqi'] = strtotime('2010-01-01');
			}
			if ($trs['benqi'] == strtotime(date("Y-m-d"))){
				$isPay = 1;
			}else{
				$isPay = 0;
			}
			$this->assign('is_pay',$isPay);
			$this->assign('trs',$trs);


			$fee = M('fee');
			$fee_rs = $fee->field('s11,s13')->find();
			$s11 = $fee_rs['s11'];
			$s13 = $fee_rs['s13'];
			$this->assign('s11',$s11);
			$this->assign('s13',$s13);

			$xiaof = M('xiaof');
			$all_n = $xiaof->where('is_pay=0 and money_two>0')->sum('money_two');
			if(empty($all_n))$all_n=0;
			$this->assign('all_n',$all_n);
			$all_m = $xiaof->where('is_pay=0 and money>0')->sum('money');
			if(empty($all_m))$all_m=0;
			$this->assign('all_m',$all_m);

			$this->display();
		}else{
			$this->error('错误!');
		}
	}

	public function adminClearingSave(){  //资金结算
		if ($_SESSION['UrlPTPass'] == 'MyssBaiGuoJS'){
			set_time_limit(0);//是页面不过期
		    $times = M('times');
		    $fck = D ('Fck');
		    $ydate = mktime();

			//分红
			$fck->tzfh();

			$this->_clearing();//全部奖金结算
			sleep(1);

			$this->success('结算分红完成！');
//			$bUrl = __URL__.'/adminClearing';
//			$this->_box(1,'结算分红完成！',$bUrl,1);
			exit;
		}else{
			$this->error('错误!');
		}
	}
	//==============================充值管理
	public function adminCurrencyRecharge(){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanMangGuo'){
			$chongzhi = M ('chongzhi');
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				$map['user_id'] = array('like',"%".$UserID."%");
			}

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $chongzhi->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $chongzhi->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$title = '充值管理';
			$this->assign('title',$title);
			$this->display('adminCurrencyRecharge');
			exit();
		}else{
			$this->error('错误!');
			exit;
		}
	}
	public function adminCurrencyRechargeAC(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST['tabledb'];
		$fck = M ('fck');
	    if (!$fck->autoCheckToken($_POST)){
            $this->error('页面过期，请刷新页面！');
            exit;
        }
		if (!isset($PTid) || empty($PTid)){
			$bUrl = __URL__.'/adminCurrencyRecharge';
			$this->_box(1,'请选择！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '确认';
				$this->_adminCurrencyRechargeOpen($PTid);
				break;
			case '删除';
				$this->_adminCurrencyRechargeDel($PTid);
				break;
		default;
			$bUrl = __URL__.'/adminCurrencyRecharge';
			$this->_box(0,'没有该记录！',$bUrl,1);
			break;
		}
	}

	public function adminCurrencyRechargeAdd(){
		//为会员充值
		if ($_SESSION['UrlPTPass'] == 'MyssGuanMangGuo'){
			$fck = M ('fck');
		    if (!$fck->autoCheckToken($_POST)){
                $this->error('页面过期，请刷新页面！');
                exit;
            }
			$UserID = $_POST['UserID'];
			$ePoints = $_POST['ePoints'];
			$content = $_POST['content'];
			$stype = (int)$_POST['stype'];
			if (is_numeric($ePoints) == false){
				$this->error('金额错误，请重新输入！');
				exit;
			}
			if (!empty($UserID) && !empty($ePoints)){
				$where = array();
				$where['user_id'] = $UserID;
				$frs = $fck->where($where)->field('id,nickname,is_agent,user_id')->find();
				if ($frs){
//                    if ($frs['is_agent'] < 2){
//                    	$this->error('该会员不是报单中心，请重新输入!');
//                    	exit;
//                    }
					$chongzhi = M ('chongzhi');
					$data = array();
                    $data['uid']     = $frs['id'];
                    $data['user_id'] = $frs['user_id'];
                    $data['rdt']     = strtotime(date('c'));
                    $data['epoint']  = $ePoints;
                    $data['is_pay']  = 0;
                    $data['stype']  = $stype;
	                $result = $chongzhi->add($data);
	                $rearray[] = $result;
                    unset($data,$chongzhi);
                    $this->_adminCurrencyRechargeOpen($rearray);
				}else{
					$this->error('没有该会员，请重新输入!');
				}
				unset($fck,$frs,$where,$UserID,$ePoints);
			}else{
				$this->error('错误!');
			}
		}else{
			$this->error('错误!');
		}
	}

	private function _adminCurrencyRechargeOpen($PTid){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanMangGuo'){
			$chongzhi = M ('chongzhi');
			$fck = M('fck');//
			$where = array();
			$where['is_pay'] = 0;
			$where['id'] = array ('in',$PTid);
			$rs = $chongzhi->where($where)->select();
			$fck_where = array();
			$nowdate = strtotime(date('c'));
			$history = M('history');
			$data = array();
			foreach($rs as $vo){
				$fck_where['id'] = $vo['uid'];
				$stype = $vo['stype'];
				$rsss = $fck->where($fck_where)->field('id,user_id')->find();
				if ($rsss){
					//开始事务处理
					$fck->startTrans();
					//明细表
					$data['uid']          = $vo['uid'];
					$data['user_id']      = $vo['user_id'];
					$data['action_type']  = 21;
					$data['pdt']          = $nowdate;
					$data['epoints']      = $vo['epoint'];
					$data['did']          = 0;
					$data['allp']         = 0;
					$data['bz']           = '21';
					$history->create();
					$rs1 = $history->add($data);
					if ($rs1){
						//提交事务
						if($stype==0){
							$fck->execute("update __TABLE__ set `agent_cash`=agent_cash+". $vo['epoint']. ",`cz_epoint`=cz_epoint+". $vo['epoint'] ." where `id`=". $vo['uid']);
						}else{
							$fck->execute("update __TABLE__ set `agent_kt`=agent_kt+". $vo['epoint']. ",`cz_epoint`=cz_epoint+". $vo['epoint'] ." where `id`=". $vo['uid']);
						}
						$chongzhi->execute("UPDATE `xt_chongzhi` set `is_pay`=1 ,`pdt`=$nowdate  where `id`=". $vo['id']);
						$fck->commit();
					}else{
						//事务回滚：
						$fck->rollback();
					}
				}
			}
			unset($chongzhi,$fck,$where,$rs,$fck_where,$nowdate,$history,$data);
			$bUrl = __URL__.'/adminCurrencyRecharge';
			$this->_box(1,'确认充值成功！',$bUrl,1);
		}else{
			$this->error('错误!');
			exit;
		}
	}
	private function _adminCurrencyRechargeDel($PTid){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanMangGuo'){
			$User = M ('chongzhi');
			$where = array();
//			$where['is_pay'] = 0;
			$where['id'] = array ('in',$PTid);
			$rs = $User->where($where)->delete();
			if ($rs){
				$bUrl = __URL__.'/adminCurrencyRecharge';
				$this->_box(1,'删除成功！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/adminCurrencyRecharge';
				$this->_box(0,'删除失败！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误!');
			exit;
		}
	}

    public function adminsingle($GPid=0){
        //============================================审核会员加单
        if ($_SESSION['UrlPTPass'] == 'MyssGuansingle'){
            $jiadan = M('jiadan');
            $UserID = $_POST['UserID'];
            if (!empty($UserID)){
                $map['user_id'] = array('like',"%".$UserID."%");
            }

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $jiadan->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $jiadan->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

            $this->display ('adminsingle');
        }else{
            $this->error('数据错误!');
            exit;
        }
    }

    public function adminsingleAC(){
        //处理提交按钮
        $fck = M ('fck');
    	$action = $_POST['action'];
        //获取复选框的值
        $PTid = $_POST['tabledb'];
        if (!$fck->autoCheckToken($_POST)){
            $this->error('页面过期，请刷新页面！');
            exit;
        }
        if (!isset($PTid) || empty($PTid)){
            $bUrl = __URL__.'/adminsingle';
            $this->_box(0,'请选择！',$bUrl,1);
            exit;
        }
        unset($fck);
        switch ($action){
            case '确认';
                $this->_adminsingleConfirm($PTid);
                break;
            case '删除';
                $this->_adminsingleDel($PTid);
                break;
        default;
            $bUrl = __URL__.'/adminsingle';
            $this->_box(0,'没有该注册！',$bUrl,1);
            break;
        }
    }


    private function _adminsingleConfirm($PTid=0){
        //===============================================确认加单
        if ($_SESSION['UrlPTPass'] == 'MyssGuansingle'){
            $fck = D ('Fck');
        	$jiadan = M('jiadan');
        	$fee = M ('fee');
        	$fee_rs = $fee->find(1);
            $where = array();
            $where['id'] = array ('in',$PTid);
            $where['is_pay'] = 0;
            $field = '*';
            $vo = $jiadan ->where($where)->field($field)->select();
            $fck_where = array();
            $nowdate = strtotime(date('c'));
            foreach($vo as $voo){
                $fck->xiangJiao($voo['uid'], $voo['danshu']);//统计单数
                $fck_where['id'] = $voo['uid'];
                $fck_rs = $fck->where($fck_where)->field('user_id,re_id,f5')->find();
                if ($fck_rs){
                    //给推荐人添加推荐人数
                	$fck->query("update __TABLE__ set `re_nums`=re_nums+" . $voo['danshu'] . " where `id`=".$fck_rs['re_id']);
                	$fck->upLevel($fck_rs['re_id']);//晋级
                }
                $fck->userLevel($voo['uid'], $voo['danshu']);//自己晋级

                //加上单数到自身认购字段
                $money = 0;
                $money = $fee_rs['uf1'] * $voo['danshu'];//金额
                $fck->xsjOne($fck_rs['re_id'], $fck_rs['user_id'], $money, $fck_rs['f5']);//销售奖第一部分中的第二部分
                $fck->query("update __TABLE__ set `f4`=f4+" . $voo['danshu'] . ",`cpzj`=cpzj+". $money ." where `id`=".$voo['uid']);
                //改变状态
                $jiadan->query("UPDATE `xt_jiadan` SET `pdt`=$nowdate,`is_pay`=1 where `id`=".$voo['id']);
            }
            unset($jiadan,$where,$field,$vo,$fck,$fck_where);
            $bUrl = __URL__.'/adminsingle';
            $this->_box(1,'确认！',$bUrl,1);
        }else{
            $this->error('错误！');
            exit;
        }
    }

    private function _adminsingleDel($PTid=0){
        //====================================删除加单
        if ($_SESSION['UrlPTPass'] == 'MyssGuansingle'){
            $jdan = M('jiadan');
            //$fck->query("update __TABLE__ SET `single_ispay`=0,`single_money`=0 where `ID` in (".$PTid.")");
            $jwhere['id'] = array('in',$PTid);
            $jwhere['is_pay'] = 0;
            $jdan->where($jwhere)->delete();
            $bUrl = __URL__.'/adminsingle';
            $this->_box(1,'删除！',$bUrl,1);
            exit;
        }else{
            $this->error('错误!');
        }
    }

	private function _delTable(){
		if ($_SESSION['UrlPTPass'] == 'MyssQingKong'){
			//删除指定记录
			//$name=$this->getActionName();
			$model   = M ('fck');
			$model2  = M ('bonus');
			$model3  = M ('history');
			$model4  = M ('msg');
			$model5  = M ('times');
			$model6  = M ('tiqu');
			$model7  = M ('zhuanj');
			$model8  = M ('shop');
			$model9  = M ('jiadan');
			$model10 = M ('chongzhi');
			$model11 = M ('region');
			$model12 = M ('orders');
			$model13 = M ('huikui');
//			$model14 = M ('product');
			$model15 = M ('gouwu');
			$model16 = M ('xiaof');
			$model17 = M ('promo');
			$model18 = M ('fenhong');
			$model19 = M ('peng');
			$model20 = M ('ulevel');
			$model21 = M ('address');


			$model->where('id > 1')->delete();
			$model2->where('id > 0')->delete();
			$model3->where('id > 0')->delete();
			$model4->where('id > 0')->delete();
			$model5->where('id > 0')->delete();
			$model6->where('id > 0')->delete();
			$model7->where('id > 0')->delete();
			$model8->where('id > 0')->delete();
			$model9->where('id > 0')->delete();
			$model10->where('id > 0')->delete();
			$model11->where('id > 0')->delete();
			$model12->where('id > 0')->delete();
			$model13->where('id > 0')->delete();
//			$model14->where('id > 0')->delete();
			$model15->where('ID > 0')->delete();
			$model16->where('ID > 0')->delete();
			$model17->where('ID > 0')->delete();
			$model18->where('id > 0')->delete();
			$model19->where('id > 0')->delete();
			$model20->where('id > 0')->delete();
			$model21->where('id > 1')->delete();
			$nowdate = time();
			//数据清0

			$nowday=strtotime(date('Y-m-d'));
			$nowday=strtotime(date('Y-m-d'));
//			$nowday=strtotime(date('Y-m-d H:i:s'));	//测试 使用

			$sql .= "`l`=0,`r`=0,`shangqi_l`=0,`shangqi_r`=0,`idt`=0,";
			$sql .= "`benqi_l`=0,`benqi_r`=0,`lr`=0,`shangqi_lr`=0,`benqi_lr`=0,";
			$sql .= "`agent_max`=0,`lssq`=0,`agent_use`=0,`is_agent`=2,`agent_cash`=0,";
			$sql .= "`u_level`=4,`zjj`=0,`wlf`=0,`zsq`=0,`re_money`=0,";
			$sql .= "`cz_epoint`=0,b0=0,b1=0,b2=0,b3=0,b4=0,";
			$sql .= "`b5`=0,b6=0,b7=0,b8=0,b9=0,b10=0,b11=0,b12=0,re_nums=0,man_ceng=0,";
			$sql .= "re_peat_money=0,cpzj=0,duipeng=0,_times=0,fanli=0,fanli_time=$nowdate,fanli_num=0,day_feng=0,get_date=$nowday,get_numb=0,";
			$sql .= "get_level=0,is_xf=0,xf_money=0,is_zy=0,zyi_date=0,zyq_date=0,down_num=0,agent_xf=0,agent_kt=0,agent_gp=0,gp_num=0,xy_money=0,";
			$sql .= "peng_num=0,re_f4=0,agent_cf=0,wlf_money=0";

			$model->execute("UPDATE __TABLE__ SET " . $sql );

			for($i=1; $i<=2; $i++){ //fck1 ~ fck5 表 (清空只留公司)
				$fck_other = M ('fck'. $i);
				$fck_other -> where('id > 1') -> delete();
			}

			//fee表,记载清空操作的时间(时间截)
			$fee    = M ('fee');
			$fee_rs = $fee -> field('id') -> find();
			$where = array();
			$data  = array();
			$data['id'] = $fee_rs['id'];
			$data['create_time'] = mktime();
			$rs = $fee -> save($data);


			$bUrl = __URL__.'/delTable';
			$this->_box(1,'清空数据！',$bUrl,1);
			exit;
		}else{
			$bUrl = __URL__.'/delTable';
			$this->_box(0,'清空数据！',$bUrl,1);
			exit;
		}
	}

	public function menber(){

		//列表过滤器，生成查询Map对象
			$fck = M('fck');
			$map = array();
			$id = $PT_id;
			$map['re_id'] = (int) $_GET['PT_id'];
			//$map['is_pay'] = 0;
			$UserID = $_POST['UserID'];
			if (!empty($UserID)){
				$map['user_id'] = array('like',"%".$UserID."%");
			}

            //查询字段
            $field  = 'id,user_id,nickname,bank_name,bank_card,user_name,user_address,user_tel,rdt,f4,cpzj,is_pay';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID='.$UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('rdt desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$where = array();
			$where['id'] = $id;
			$fck_rs = $fck->where($where)->field('agent_cash')->find();
			$this->assign('frs',$fck_rs);//电子币
			$this->display ('menber');
			exit;
	}

    public function adminmoneyflows(){
        //货币流向
        if ($_SESSION['UrlPTPass'] == 'MyssMoneyFlows'){
        	$fck = M('fck');
            $history = M('history');
            $sDate  = $_REQUEST['S_Date'];
            $eDate  = $_REQUEST['E_Date'];
            $UserID = $_REQUEST['UserID'];
            $ss_type = (int) $_REQUEST['tp'];
			$map['_string'] = "1=1";
			$s_Date = 0;
			$e_Date = 0;
			if(!empty($sDate)){
				$s_Date = strtotime($sDate);
			}else{
				$sDate = "2000-01-01";
			}
			if(!empty($eDate)){
				$e_Date = strtotime($eDate);
			}else{
				$eDate = date("Y-m-d");
			}
			if($s_Date>$e_Date&&$e_Date>0){
				$temp_d = $s_Date;
				$s_Date = $e_Date;
				$e_Date = $temp_d;
			}
			if($s_Date>0){
				$map['_string'] .= " and pdt>=".$s_Date;
			}
			if($e_Date>0){
				$e_Date = $e_Date+3600*24-1;
				$map['_string'] .= " and pdt<=".$e_Date;
			}
			if($ss_type>0){
				if($ss_type==15){
					$map['action_type'] = array('lt',12);
				}else{
					$map['action_type'] = array('eq',$ss_type);
				}
			}
			if (!empty($UserID)){
            	import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }

				unset($KuoZhan);
				$where = array();
				$where['user_id'] = array('eq',$UserID);
				$usrs = $fck->where($where)->field('id,user_id')->find();
				if($usrs){
					$usid = $usrs['id'];
					$usuid = $usrs['user_id'];
					$map['_string'] .= " and (uid=".$usid." or user_id='".$usuid."')";
				}else{
					$map['_string'] .= " and id=0";
				}
				unset($where,$usrs);
				$UserID = urlencode($UserID);
			}
			$this->assign('S_Date',$sDate);
			$this->assign('E_Date',$eDate);
			$this->assign('ry',$ss_type);
			$this->assign('UserID',$UserID);
            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $history->where($map)->count();//总页数
            $listrows = 20  ;//每页显示的记录数
            $page_where = 'UserID=' . $UserID .'&S_Date='. $sDate .'&E_Date='. $eDate . '&tp=' . $ss_type ;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $history->where($map)->field($field)->order('pdt desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================
//            dump($history);

            $fee   = M ('fee');    //参数表
			$fee_rs = $fee->field('s18')->find();
			$fee_s7 = explode('|',$fee_rs['s18']);
			$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组

            $this->display ();
        }else{
            $this->error('数据错误!');
            exit;
        }
    }


    private function _upFck(){
    	$fee_rs = M ('fee') -> find();
        $fck = M ('fck');
        $f_where = array();
        $f_where['is_pay'] = array('egt',1);
        $frs = $fck->where($f_where)->field('id,u_level')->select();
        //检测注册等级
        $fee_s3 = explode('|',$fee_rs['s3']);  //注册金额数组
        $fee_s4 = explode('|',$fee_rs['s4']);  //注册单数
        $fee_s5 = explode('|',$fee_rs['s5']);  //注册配送虚拟业绩
        foreach ($frs as $vo){
            $uLevel = $vo['u_level'];
                switch ($uLevel){
                    case 1:
                        $cpzj = $fee_s3['0'];
                        $f4   = $fee_s4['0'];
                        $f2   = $fee_s5['0'];
                        break;
                    case 2:
                        $cpzj = $fee_s3['1'];
                        $f4   = $fee_s4['1'];
                        $f2   = $fee_s5['1'];
                        break;
                    case 3:
                        $cpzj = $fee_s3['2'];
                        $f4   = $fee_s4['2'];
                        $f2   = $fee_s5['2'];
                        break;
                    case 4:
                        $cpzj = $fee_s3['3'];
                        $f4   = $fee_s4['3'];
                        $f2   = $fee_s5['3'];
                        break;
                    case 5:
                        $cpzj = $fee_s3['4'];
                        $f4   = $fee_s4['4'];
                        $f2   = $fee_s5['4'];
                        break;
                    default:
                        $cpzj = $fee_s3['0'];
                        $f4   = $fee_s4['0'];
                        $f2   = $fee_s5['0'];
                        break;
                }
                $sql  = "agent_use=0,agent_cash=0,zjj=0,l=0,r=0,shangqi_l=0,shangqi_r=0,benqi_l=0,benqi_r=0";
                $sql .= ",f4={$f4},f3=0,m_money={$cpzj},cpzj={$cpzj},re_peat_money={$cpzj}";
                //$sql .= ",f4={$f4},f2={$f2},f3=0,m_money={$cpzj},cpzj={$cpzj},re_peat_money={$cpzj}";

                $fck->execute("UPDATE __TABLE__ SET {$sql} WHERE id={$vo['id']}");
        }

    }


	public function pro_index(){      //产品表查询
		if ($_SESSION['UrlPTPass'] == 'MyssGuanChanPin'){
			$product = M('product');
			$title = $_REQUEST['stitle'];
			$map = array();
			if(strlen($title)>0){
				$map['name'] = array('like','%'. $title .'%');
			}
			$map['id'] = array('gt',0);
			$orderBy = 'create_time desc,id desc';
			$field  = '*';
	        //=====================分页开始==============================================
	        import ( "@.ORG.ZQPage" );  //导入分页类
	        $count = $product->where($map)->count();//总页数
	   		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
	   		$listrows = 10;//每页显示的记录数
	        $page_where = 'stitle=' . $title ;//分页条件
	        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
	        //===============(总页数,每页显示记录数,css样式 0-9)
	        $show = $Page->show();//分页变量
	        $this->assign('page',$show);//分页变量输出到模板
	        $list = $product->where($map)->field($field)->order($orderBy)->page($Page->getPage().','.$listrows)->select();
	        $this->assign('list',$list);//数据输出到模板
	        //=================================================

	        $this->display();
		}else{
            $this->error('错误!');
        }
	}

	public function pro_edit(){      //产品表显示修改
		$EDid = $_GET['EDid'];
		$field = '*';
		$product = M ('product');
		$where = array();
		$where['id'] = $EDid;
		$rs = $product->where($where)->field($field)->find();
		if ($rs){
			$this->assign('rs',$rs);
			$this->us_fckeditor('content',$rs['content'],400,"96%");

			$cptype = M('cptype');
			$list = $cptype->where('status=0')->order('id asc')->select();
			$this->assign('list',$list);

			$this->display();
		}else{
			$this->error('没有该信息！');
			exit;
		}
	}

	public function pro_edit_save(){      //产品表修改保存
		$product = M ('product');
		$data = array();
		//h 函数转换成安全html
		$money = trim($_POST['money']);
		$a_money = $_POST['a_money'];
		$b_money = $_POST['b_money'];
		$content = stripslashes($_POST['content']);
		$title = trim($_POST['title']);
		$cid = trim($_POST['cid']);
		$image = $_POST['image'];
		$ctime = trim($_POST['ctime']);
		$ccname = $_POST['ccname'];
		$xhname = $_POST['xhname'];
		$cptype = trim($_POST['cptype']);
		$cptype = (int)$cptype;
		$ctime = strtotime($ctime);
		if (empty($title)){
			$this->error('标题不能为空!');
			exit;
		}
		if (empty($cid)){
			$this->error('商品编号不能为空!');
			exit;
		}
//		if (empty($ccname)){
//			$this->error('商品尺寸不能为空!');
//			exit;
//		}
//		if (empty($xhname)){
//			$this->error('商品型号不能为空!');
//			exit;
//		}
		if (empty($money)||!is_numeric($money)){
			$this->error('价格不能为空!');
			exit;
		}
		if($money <= 0){
			$this->error('输入的价格有误!');
			exit;
		}

		if(!empty($ctime)){
			$data['create_time'] = $ctime;
		}
		$data['cid'] = $cid;
		$data['ccname'] = $ccname;
		$data['xhname'] = $xhname;
		$data['money'] = $money;
		$data['a_money'] = $a_money;
		$data['b_money'] = $b_money;
		$data['name'] = $title;
		$data['content'] = $content;
		$data['cptype'] = $cptype;

		$data['img'] = $image;

		$data['id'] = $_POST['ID'];

		$rs = $product->save($data);
		if (!$rs){
			$this->error('编辑失败！');
			exit;
		}
		$bUrl = __URL__.'/pro_index';
		$this->_box(1,'操作成功',$bUrl,1);
		exit;
	}

	public function pro_zz(){      //产品表操作（启用禁用删除）
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST["checkbox"];
		if ($action=='添加'){

			$cptype = M('cptype');
			$list = $cptype->where('status=0')->order('id asc')->select();
			$this->assign('list',$list);

			$this->us_fckeditor('content',"",400,"96%");

			$this->display('pro_add');
			exit;
		}
		$product = M ('product');
		switch ($action){
			case '删除';
				$wherea=array();
				$wherea['id'] = array('in',$PTid);
				$rs = $product->where($wherea)->delete();
				if ($rs){
					$bUrl = __URL__.'/pro_index';
					$this->_box(1,'操作成功',$bUrl,1);
					exit;
				}else{
					$bUrl = __URL__.'/pro_index';
					$this->_box(0,'操作失败',$bUrl,1);
				}
				break;
			case '屏蔽产品';
				$wherea=array();
				$wherea['id'] = array('in',$PTid);
				$rs = $product->where($wherea)->setField('yc_cp',1);
				if ($rs){
					$bUrl = __URL__.'/pro_index';
					$this->_box(1,'屏蔽产品成功',$bUrl,1);
					exit;
				}else{
					$bUrl = __URL__.'/pro_index';
					$this->_box(0,'屏蔽产品失败',$bUrl,1);
				}
				break;
			case '解除屏蔽';
				$wherea=array();
				$wherea['id'] = array('in',$PTid);
				$rs = $product->where($wherea)->setField('yc_cp',0);
				if ($rs){
					$bUrl = __URL__.'/pro_index';
					$this->_box(1,'解除屏蔽成功',$bUrl,1);
					exit;
				}else{
					$bUrl = __URL__.'/pro_index';
					$this->_box(0,'解除屏蔽失败',$bUrl,1);
				}
				break;
			default;
				$bUrl = __URL__.'/pro_index';
				$this->_box(0,'操作失败',$bUrl,1);
				break;
		}
	}

	public function pro_inserts(){      //产品表添加保存
		$product = M('product');

		$data = array();
		//h 函数转换成安全html
		$content = trim($_POST['content']);
		$title = trim($_POST['title']);
		$cid = trim($_POST['cid']);
		$image = trim($_POST['image']);
		$money = $_POST['money'];
		$a_money = $_POST['a_money'];
		$b_money = $_POST['b_money'];
		$ccname = $_POST['ccname'];
		$xhname = $_POST['xhname'];
		$cptype = (int)$_POST['cptype'];
		if (empty($title)){
			$this->error('商品名称不能为空!');
			exit;
		}
		if (empty($cid)){
			$this->error('商品编号不能为空!');
			exit;
		}
//		if (empty($ccname)){
//			$this->error('商品尺寸不能为空!');
//			exit;
//		}
//		if (empty($xhname)){
//			$this->error('商品型号不能为空!');
//			exit;
//		}

		if (empty($money)||!is_numeric($money)){
			$this->error('价格不能为空!');
			exit;
		}
		if($money <= 0){
			$this->error('输入的价格有误!');
			exit;
		}

		$data['name'] = $title;
		$data['cid'] = $cid;
		$data['content'] = stripslashes($content);
		$data['img'] = $image;
		$data['create_time'] = mktime();
		$data['money'] = $money;
		$data['a_money'] = $a_money;
		$data['b_money'] = $b_money;
		$data['ccname'] = $ccname;
		$data['xhname'] = $xhname;
		$data['cptype'] = $cptype;
		$form_rs = $product->add($data);
		if (!$form_rs){
			$this->error('添加失败');
			exit;
		}
		$bUrl = __URL__.'/pro_index';
		$this->_box(1,'操作成功',$bUrl,1);
		exit;
	}

	public function cptype_index(){
		if ($_SESSION['UrlPTPass'] == 'MyssGuanChanPin'){
			$product = M('cptype');
			$map = array();
			$map['id'] = array('gt',0);
			$orderBy = 'id asc';
			$field  = '*';
	        //=====================分页开始==============================================
	        import ( "@.ORG.ZQPage" );  //导入分页类
	        $count = $product->where($map)->count();//总页数
	   		$listrows = 20;//每页显示的记录数
	        $page_where = "" ;//分页条件
	        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
	        //===============(总页数,每页显示记录数,css样式 0-9)
	        $show = $Page->show();//分页变量
	        $this->assign('page',$show);//分页变量输出到模板
	        $list = $product->where($map)->field($field)->order($orderBy)->page($Page->getPage().','.$listrows)->select();
	        $this->assign('list',$list);//数据输出到模板
	        //=================================================

	        $this->display();
		}else{
            $this->error('错误!');
        }
	}

	public function cptype_edit(){
		$EDid = $_GET['EDid'];
		$field = '*';
		$product = M ('cptype');
		$where = array();
		$where['id'] = $EDid;
		$rs = $product->where($where)->field($field)->find();
		if ($rs){
			$this->assign('rs',$rs);
			$this->display();
		}else{
			$this->error('没有该信息！');
			exit;
		}
	}

	public function cptype_edit_save(){
		$cptype = M ('cptype');
		$title = trim($_POST['title']);
		if (empty($title)){
			$this->error('分类名不能为空!');
			exit;
		}
		$data = array();
		$data['tpname'] = $title;
		$data['id'] = $_POST['id'];
		$rs = $cptype->save($data);
		if (!$rs){
			$this->error('编辑失败！');
			exit;
		}
		$bUrl = __URL__.'/cptype_index';
		$this->_box(1,'操作成功',$bUrl,1);
		exit;
	}

	public function cptype_zz(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$PTid = $_POST["checkbox"];
		if ($action=='添加'){
			$this->display('cptype_add');
			exit;
		}
		$product = M ('cptype');
		switch ($action){
			case '删除';
				$wherea=array();
				$wherea['id'] = array('in',$PTid);
				$rs = $product->where($wherea)->delete();
				if ($rs){
					$bUrl = __URL__.'/cptype_index';
					$this->_box(1,'操作成功',$bUrl,1);
					exit;
				}else{
					$bUrl = __URL__.'/cptype_index';
					$this->_box(0,'操作失败',$bUrl,1);
				}
				break;
			default;
			$bUrl = __URL__.'/cptype_index';
			$this->_box(0,'操作失败',$bUrl,1);
			break;
		}
	}

	public function cptype_inserts(){      //产品表添加保存
		$product = M('cptype');
		$title = trim($_POST['title']);
		if (empty($title)){
			$this->error('分类名不能为空!');
			exit;
		}
		$data = array();
		$data['tpname'] = $title;
		$form_rs = $product->add($data);
		if (!$form_rs){
			$this->error('添加失败');
			exit;
		}
		$bUrl = __URL__.'/cptype_index';
		$this->_box(1,'操作成功',$bUrl,1);
		exit;
	}

	public function adminLogistics(){
		//物流管理
		if ($_SESSION['UrlPTPass'] == 'MyssWuliuList'){
			$shopping = M ('gouwu');
			$product = M('product');
            $UserID = $_REQUEST['UserID'];
            $ss_type = (int) $_REQUEST['type'];
            $map = array();
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
				$map['user_id'] = array('like',"%".$UserID."%");
				$UserID = urlencode($UserID);
			}
			if($ss_type==0){
				$map['ispay'] = array('egt',0);
			}elseif($ss_type==1){
				$map['ispay'] = array('eq',0);
				$map['isfh'] = array('eq',0);
			}elseif($ss_type==2){
				$map['ispay'] = array('eq',0);
				$map['isfh'] = array('eq',1);
			}elseif($ss_type==3){
				$map['ispay'] = array('eq',1);
			}
			$map['lx'] = array('eq',1);
            //查询字段
            $field   = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $shopping->where($map)->count();//总页数
            $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
		    $page_where = 'UserID='.$UserID.'&type='.$ss_type;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $shopping ->where($map)->field($field)->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================
            foreach($list as $vv){
            	$ttid = $vv['did'];
            	$trs = $product->where('id='.$ttid)->find();
            	$voo[$ttid] = $trs['name'];
            }
            $this->assign('voo',$voo);
            $title = '物流管理';
            $this->assign('title',$title);
            $this->display('adminLogistics');
		}else{
			$this->error('错误!');
			exit;
		}
	}

    public function adminLogisticsAC(){
        //处理提交按钮
        $action = $_POST['action'];
        //获取复选框的值
        $XGid = $_POST['tabledb'];
        if (!isset($XGid) || empty($XGid)){
            $bUrl = __URL__.'/adminLogistics';
            $this->_box(0,'请选择货物！',$bUrl,1);
            exit;
        }
        switch ($action){
            case '确认发货';
                $this->_adminLogisticsOK($XGid);
                break;
            case '确定收货';
                $this->_adminLogisticsDone($XGid);
                break;
            case '删除';
                $this->_adminLogisticsDel($XGid);
                break;
	        default;
	            $bUrl = __URL__.'/adminLogistics';
	            $this->_box(0,'没有该货物！',$bUrl,1);
	            break;
        }
    }

    private function _adminLogisticsOK($XGid){
    	//确定发货
        if ($_SESSION['UrlPTPass'] == 'MyssWuliuList'){
            $shopping = M ('gouwu');
            $where = array();
            $where['id'] = array ('in',$XGid);
            $where['isfh'] = array ('eq',0);

            $valuearray = array(
            	'isfh' => '1',
            	'fhdt' => mktime()
            );
            $shopping->where($where)->setField($valuearray);
            unset($shopping,$where);

            $bUrl = __URL__.'/adminLogistics';
            $this->_box(1,'发货成功！',$bUrl,1);
            exit;
        }else{
            $this->error('错误!');
        }
    }

    private function _adminLogisticsDone($XGid){
    	//确定发货
        if ($_SESSION['UrlPTPass'] == 'MyssWuliuList'){
            $shopping = M ('gouwu');

            $where1 = array();
			$where1['id'] = array ('in',$XGid);
            $where1['isfh'] = array ('eq',0);

            $where = array();
            $where['id'] = array ('in',$XGid);
            $where['ispay'] = array ('eq',0);



            $valuearray1 = array(
            	'isfh' => '1',
            	'fhdt' => mktime()
            );

            $valuearray = array(
            	'ispay' => '1',
            	'okdt' => mktime()
            );

            $shopping->where($where1)->setField($valuearray1);
            $shopping->where($where)->setField($valuearray);
            unset($shopping,$where1,$where);

            $bUrl = __URL__.'/adminLogistics';
            $this->_box(1,'确认收货成功！',$bUrl,1);
            exit;
        }else{
            $this->error('错误!');
            exit;
        }
    }

    private function _adminLogisticsDel($XGid){
    	//确定发货
        if ($_SESSION['UrlPTPass'] == 'MyssWuliuList'){
            $shopping = M ('gouwu');
            $where = array();
            $where['id'] = array ('in',$XGid);
            $shopping->where($where)->delete();
            unset($shopping,$where);

            $bUrl = __URL__.'/adminLogistics';
            $this->_box(1,'删除成功！',$bUrl,1);
            exit;
        }else{
            $this->error('错误!');
        }
    }

	public function upload_fengcai_pp() {
        if(!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload_fengcai_pp();
        }
    }

    protected function _upload_fengcai_pp()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数

        //载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize  = 1048576 * 2 ;// TODO 50M   3M 3292200 1M 1048576

        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');

        //设置附件上传目录
        $upload->savePath =  './Public/Uploads/image/';

        //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  false;

       //设置需要生成缩略图的文件前缀
        $upload->thumbPrefix   =  'm_';  //生产2张缩略图

       //设置缩略图最大宽度
        $upload->thumbMaxWidth =  '800';

       //设置缩略图最大高度
        $upload->thumbMaxHeight = '600';

       //设置上传文件规则
//		$upload->saveRule = uniqid;
		$upload->saveRule = date("Y").date("m").date("d").date("H").date("i").date("s").rand(1,100);

       //删除原图
       $upload->thumbRemoveOrigin = true;

        if(!$upload->upload()) {
            //捕获上传异常
            $error_p=$upload->getErrorMsg();
            echo "<script>alert('".$error_p."');history.back();</script>";
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path=$uploadList[0]['savepath'];
            $U_nname=$uploadList[0]['savename'];
            $U_inpath=(str_replace('./Public/','__PUBLIC__/',$U_path)).$U_nname;

            echo "<script>window.parent.form1.image.value='".$U_inpath."';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit;

        }
    }

    //会员升级
    public function adminUserUp($GPid=0){
    	//列表过滤器，生成查询Map对象
    	if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp'){
    		$ulevel = M('ulevel');
    		$UserID = $_POST['UserID'];
    		if (!empty($UserID)){
    			$map['user_id'] = array('like',"%".$UserID."%");
    		}

    		$field  = '*';
    		//=====================分页开始==============================================
    		import ( "@.ORG.ZQPage" );  //导入分页类
    		$count = $ulevel->where($map)->count();//总页数
    		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
    		$page_where = 'UserID=' . $UserID;//分页条件
    		$Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
    		//===============(总页数,每页显示记录数,css样式 0-9)
    		$show = $Page->show();//分页变量
    		$this->assign('page',$show);//分页变量输出到模板
    		$list = $ulevel->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();

    		$HYJJ = '';
    		$this->_levelConfirm($HYJJ,1);
    		$this->assign('voo',$HYJJ);//会员级别

    		$this->assign('list',$list);//数据输出到模板
    		//=================================================


    		$title = '会员升级管理';
    		$this->display ('adminuserUp');
    		return;
    	}else{
    		$this->error('数据错误!');
    		exit;
    	}
    }

    public function adminUserUpAC($GPid=0){
    	//列表过滤器，生成查询Map对象
    	if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp'){
    		//处理提交按钮
    		$action = $_POST['action'];
    		//获取复选框的值
    		$PTid = $_POST['tabledb'];
    		if (!isset($PTid) || empty($PTid)){
    			$bUrl = __URL__.'/adminUserUp';
    			$this->_box(0,'请选择会员！',$bUrl,1);
    			exit;
    		}
    		switch ($action){
    			case '确认升级';
    			$this->_adminUserUpOK($PTid);
    			break;
    			case '删除';
    			$this->_adminUserUpDel($PTid);
    			break;
    			default;
    			$bUrl = __URL__.'/adminUserUp';
    			$this->_box(0,'没有该会员！',$bUrl,1);
    			break;
    		}
    	}else{
    		$this->error('数据错误!');
    		exit;
    	}
    }

    private function _adminUserUpOK($PTid=0){
    	if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp'){
    		$fck = D ('Fck');
    		$ulevel = M('ulevel');
    		$where = array();
    		$where['id'] = array ('in',$PTid);
    		$where['is_pay'] = 0;
    		$field = '*';
    		$vo = $ulevel ->where($where)->field($field)->select();
    		$fck_where = array();
    		$nowdate = strtotime(date('c'));
    		foreach($vo as $voo){
    			$ulevel->query("UPDATE `xt_ulevel` SET `pdt`=$nowdate,`is_pay`=1 where `id`=".$voo['id']);
    			$money = 0;
    			$money = $voo['money'];//金额
    			$fck->query("update __TABLE__ set `cpzj`=cpzj+". $money .",u_level=".$voo['up_level']."  where `id`=".$voo['uid']);

    		}
    		unset($fck,$where,$field,$vo);
    		$bUrl = __URL__.'/adminUserUp';
    		$this->_box(1,'升级会员成功！',$bUrl,1);
    		exit;

    	}else{
    		$this->error('错误！');
    		exit;
    	}
    }
    private function _adminUserUpDel($PTid=0){
    	//删除会员
    	if ($_SESSION['UrlPTPass'] == 'MyssGuanXiGuaUp'){
    		$fck = M ('fck');
    		$ispay = M('ispay');
    		$ulevle = M('ulevel');
    		$where['id'] =array('in',$PTid);
    		$where['is_pay'] =array ('eq',0);
    		$rss1= $ulevle->where($where)->delete();

    		if ($rss1){
    			$bUrl = __URL__.'/adminUserUp';
    			$this->_box(1,'删除升级申请成功！',$bUrl,1);
    			exit;
    		}else{
    			$bUrl = __URL__.'/adminUserUp';
    			$this->_box(0,'删除升级申请失败！',$bUrl,1);
    			exit;
    		}

    	}else{
    		$this->error('错误!');
    	}
    }

	public function upload_fengcai_aa() {
        if(!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload_fengcai_aa();
        }
    }

    protected function _upload_fengcai_aa()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数

        //载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize  = 1048576 * 2 ;// TODO 50M   3M 3292200 1M 1048576

        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');

        //设置附件上传目录
        $upload->savePath =  './Public/Uploads/';

        //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  false;

       //设置需要生成缩略图的文件前缀
        $upload->thumbPrefix   =  'm_';  //生产2张缩略图

       //设置缩略图最大宽度
        $upload->thumbMaxWidth =  '800';

       //设置缩略图最大高度
        $upload->thumbMaxHeight = '600';

       //设置上传文件规则
       $upload->saveRule = date("Y").date("m").date("d").date("H").date("i").date("s").rand(1,100);

       //删除原图
       $upload->thumbRemoveOrigin = true;

        if(!$upload->upload()) {
            //捕获上传异常
            $error_p=$upload->getErrorMsg();
            echo "<script>alert('".$error_p."');history.back();</script>";
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path=$uploadList[0]['savepath'];
            $U_nname=$uploadList[0]['savename'];
            $U_inpath=(str_replace('./Public/','__PUBLIC__/',$U_path)).$U_nname;

            echo "<script>window.parent.myform.str21.value='".$U_inpath."';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit;

        }
    }

	public function upload_fengcai_bb() {
        if(!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload_fengcai_bb();
        }
    }

    protected function _upload_fengcai_bb()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数

        //载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize  = 1048576 * 2 ;// TODO 50M   3M 3292200 1M 1048576

        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');

        //设置附件上传目录
        $upload->savePath =  './Public/Uploads/';

        //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  false;

       //设置需要生成缩略图的文件前缀
        $upload->thumbPrefix   =  'm_';  //生产2张缩略图

       //设置缩略图最大宽度
        $upload->thumbMaxWidth =  '800';

       //设置缩略图最大高度
        $upload->thumbMaxHeight = '600';

       //设置上传文件规则
       $upload->saveRule = date("Y").date("m").date("d").date("H").date("i").date("s").rand(1,100);

       //删除原图
       $upload->thumbRemoveOrigin = true;

        if(!$upload->upload()) {
            //捕获上传异常
            $error_p=$upload->getErrorMsg();
            echo "<script>alert('".$error_p."');history.back();</script>";
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path=$uploadList[0]['savepath'];
            $U_nname=$uploadList[0]['savename'];
            $U_inpath=(str_replace('./Public/','__PUBLIC__/',$U_path)).$U_nname;

            echo "<script>window.parent.myform.str22.value='".$U_inpath."';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit;

        }
    }

	public function upload_fengcai_cc() {
        if(!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload_fengcai_cc();
        }
    }

    protected function _upload_fengcai_cc()
    {
        header("content-type:text/html;charset=utf-8");
        // 文件上传处理函数

        //载入文件上传类
        import("@.ORG.UploadFile");
        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize  = 1048576 * 2 ;// TODO 50M   3M 3292200 1M 1048576

        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');

        //设置附件上传目录
        $upload->savePath =  './Public/Uploads/';

        //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  false;

       //设置需要生成缩略图的文件前缀
        $upload->thumbPrefix   =  'm_';  //生产2张缩略图

       //设置缩略图最大宽度
        $upload->thumbMaxWidth =  '800';

       //设置缩略图最大高度
        $upload->thumbMaxHeight = '600';

       //设置上传文件规则
       $upload->saveRule = date("Y").date("m").date("d").date("H").date("i").date("s").rand(1,100);

       //删除原图
       $upload->thumbRemoveOrigin = true;

        if(!$upload->upload()) {
            //捕获上传异常
            $error_p=$upload->getErrorMsg();
            echo "<script>alert('".$error_p."');history.back();</script>";
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $U_path=$uploadList[0]['savepath'];
            $U_nname=$uploadList[0]['savename'];
            $U_inpath=(str_replace('./Public/','__PUBLIC__/',$U_path)).$U_nname;

            echo "<script>window.parent.myform.str23.value='".$U_inpath."';</script>";
            echo "<span style='font-size:12px;'>上传完成！</span>";
            exit;

        }
    }

}
?>