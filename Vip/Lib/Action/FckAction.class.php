<?php
class FckAction extends CommonAction {

	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		$this->_Config_name();//调用参数
		$this->_checkUser();
//		$this->_inject_check(1);//调用过滤函数


	}

	//会员资金查询(显示会员每一期的各奖奖金)
	public function financeTable($cs=0){
		$bonus = M ('bonus');  //奖金表
		$where = array();
		$myid = $_SESSION[C('USER_AUTH_KEY')];
		$ckid = (int)$_REQUEST['cid'];
		if($myid==1){
			if(empty($ckid)){
				$where['uid'] = $myid;
			}else{
				$where['uid'] = $ckid;
			}
		}else{
			$where['uid'] = $myid;
		}
		$this->assign('cid',$ckid);

		if(!empty($_REQUEST['FanNowDate'])){  //日期查询
			$time1 = strtotime($_REQUEST['FanNowDate']);                // 这天 00:00:00
			$time2 = strtotime($_REQUEST['FanNowDate']) + 3600*24 -1;   // 这天 23:59:59
			$where['e_date'] = array(array('egt',$time1),array('elt',$time2));
			//$where['e_date'] = array('eq',$time1);
		}

        $field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $bonus->where($where)->count();//总页数
        $listrows = C('ONE_PAGE_RE');//每页显示的记录数
        if(!empty($ckid)){
        	$page_where = 'cid='.$ckid.'&FanNowDate=' . $_REQUEST['FanNowDate'];//分页条件
        }else{
        	$page_where = 'FanNowDate=' . $_REQUEST['FanNowDate'];//分页条件
        }
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page', $show);//分页变量输出到模板
        $list = $bonus->where($where)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
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

		$fee   = M ('fee');    //参数表
		$fee_rs = $fee->field('s18')->find();
		$fee_s7 = explode('|',$fee_rs['s18']);
		$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组

		$this -> assign('b_b',$b_b);
		$this -> assign('c_b',$c_b);
		$this->assign('count',$count);
		$this->display('financeTable');
	}

	//显示回馈
	public function huikuiShow(){
		$history                = M ('history');
		$fck = M('fck');
		$id = $_SESSION[C('USER_AUTH_KEY')];

		$nowdate = strtotime(date('Y-m'));

		$list = $fck->where('re_id ='.$id )->field('id,rdt,cpzj')->order('id asc')->select();

		$con = array();
 		$k = 0;
 		$ms = 24 * 3600 * 30;
		foreach($list as $vo){
			if($k == 0){
				$con[$vo['id']] = '合格';
			}else{
				$frs = $fck->where('re_id ='.$id .' and id < '.$vo['id'])->field('id,rdt,cpzj')->order('id desc')->find();
				if($vo['rdt'] - $frs['rdt'] > $ms){
					$con[$vo['id']] = '不合格';
				}else{
					$con[$vo['id']] = '合格';
				}

			}

			$k++;

		}

		$this->assign('con',$con);
		$this->assign('list',$list);

		$arr[0] = 200;
		$arr[1] = 400;
		$arr[2] = 800;
		$arr[3] = 1500;
		$arr[4] = 2500;
		$arr[5] = 3000;
		$arr[6] = 3000;
		$arr[7] = 3000;
		$a_where['uid']=$id;
		$a_where['action_type'] = 4;
		$money = 0 ;
		for ($i=0;$i<=7;$i++){
			$a_where['epoints'] = $arr[$i];
			$a_rs = $history->where($a_where)->field('pdt,epoints')->find();
			if($a_rs['epoints'] == $arr[$i]){
				$a_time[$i] = date('Y-m-d H:i:s',$a_rs['pdt']);;
				$arr[$i] = "<font color='#FF0000'>{$arr[$i]}</font>";
				$money += $a_rs['epoints'];
			}else{
				$frs = $fck->where('id ='.$id )->field('fanli_num,fanli_time')->find();
				$m_num = 6 - $frs['fanli_num']+$i;
				$endtime = date('Y-m-d',$frs['fanli_time']);
				$endtime = date('Y-m-d', strtotime($endtime.$m_num.' month'));
				$a_time[$i] = $endtime;
			}
		}
		$this->assign('money',$money);
		$this->assign('a_time',$a_time);
		$this->assign('arr',$arr);

		$this->display('huikuiTable');
	}
	//组织业绩
	public function yejiShow(){

		$fck    = M ('fck');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$field = '*';
		$fck_rs = $fck -> field($field) -> find($id);

		$this->assign('l',$fck_rs['l']);
		$this->assign('r',$fck_rs['r']);

		$where = array();
		$where['p_path'] = array('like','%,'.$id .',%');

		//左边单数
		$wh=array();
		$wh['father_id'] = $id;
		$wh['treeplace'] = 0 ;

		$li = $fck->where($wh)->field($field)->find();

		//右边单数
		$wh['treeplace'] = 1 ;
		$li2 = $fck->where($wh)->field($field)->find();

		if(!empty($_REQUEST['FanNowDate']) && !empty($_REQUEST['FanNowDate2'])){  //日期查询
			$time1 = strtotime($_REQUEST['FanNowDate']);                // 这天 00:00:00
			$time2 = strtotime($_REQUEST['FanNowDate2']) + 3600*24 -1;   // 这天 23:59:59
			$where['rdt'] = array(array('egt',$time1),array('elt',$time2));
		}else{
			$nowdate = date('Y-m-d');
			$time1 = strtotime($nowdate);                // 这天 00:00:00
			$time2 = strtotime($nowdate) + 3600*24 -1;   // 这天 23:59:59
			$where['rdt'] = array(array('egt',$time1),array('elt',$time2));
		}

        $field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $fck->where($where)->count();//总页数
        $listrows = C('ONE_PAGE_RE');//每页显示的记录数
        $page_where = 'FanNowDate=' . $_REQUEST['FanNowDate'];//分页条件
        $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page', $show);//分页变量输出到模板
        $list = $fck->where($where)->field($field)->order('pdt asc')->group('pdt')->select();
        $this->assign('list',$list);//数据输出到模板
		$l = array();
		$r = array();
		foreach($list as $vo){
			$fwhere =array();
			$twhere =array();
			$pdt = $vo['pdt'] + 24*3600-1;
			//左边单数汇总
			if($li){
				$fwhere['p_path']=  array('like','%,'.$id.','.$li['id'] .',%');
				$fwhere['rdt'] = array(array('egt',$vo['pdt']),array('elt',$pdt));
				$l[$vo['id']] = $fck->where($fwhere)->count();

			}
			//右边单数汇总
			if($li2){
				$twhere['p_path']=  array('like','%,'.$id.','.$li2['id'] .',%');
				$twhere['rdt'] = array(array('egt',$vo['pdt']),array('elt',$pdt));
				$r[$vo['id']] = $fck->where($twhere)->count();

			}

			$fwhere =array();
			$fwhere['father_id']=  $id;
			$fwhere['treeplace'] = 0 ;
			$fwhere['rdt'] = array(array('egt',$vo['pdt']),array('elt',$pdt));
			$l[$vo['id']] += $fck->where($fwhere)->count();
			$fwhere['treeplace'] = 1 ;
			$r[$vo['id']] += $fck->where($fwhere)->count();
		}
		$this->assign('ll',$l);
		$this->assign('rr',$r);
		$this->display('yejiTable');
	}


	//下订单页面
	public function DownOrders(){
		$pro = M ('product');

		$list = $pro->select();
		$this->assign('list',$list);
		$this->display('DownOrders');
	}

	//下订单保存
	public function Orders_save(){
		$orders = M ('orders');  //订单表
		$pro = M ('product');     //产品表

		$cpzj = 0;
		$proarr = $_POST['tabledb'];
		if(empty($proarr)){
			$this->error('请选择产品！');
			exit;
		}

		$app = 0;
		foreach($proarr as $vo){
			$where = array();
			$where['id'] = $vo;
			$rs = $pro -> where($where) -> find();
			if($rs){
				$pronum = 'num'.$rs['id'];
				if((int) $_POST[$pronum]){
					$pro_data = array();
					$pro_data['uid']     = $_SESSION[C('USER_AUTH_KEY')];  //下订单用户ID
					$pro_data['pname']   = $rs['name'];
					$pro_data['pnum']    = (int) $_POST[$pronum];
					$pro_data['pmoney']  = $rs['money'];
					$pro_data['status']  = 0;
			  	    $pro_data['create_time']  = time();
					$orders -> add($pro_data);
					$app = 1;
				}
			}
		}

		if($app == 1){
			$bUrl = __URL__.'/DownOrders';
			$this->_box(1,'订单提交成功！',$bUrl,1);
		}else{
			$this->error ('请输入产品数量!');
			exit;
		}
	}


	//显示未发货列表页
	public function OrdersShow(){
		$orders = M ('orders');  //订单表


		$where = array();
		$page_where = '';
		if(isset($_REQUEST['title']) and !empty($_REQUEST['title'])){
			$where['pname'] = array('like','%'. trim($_REQUEST['title'] .'%'));
			$page_where = 'title='. trim($_REQUEST['title']);
		}

		$where['uid']    = array('eq',$_SESSION[C('USER_AUTH_KEY')]);
		$where['status'] = array('lt',2);  //0未发货,1无货,2已发货

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

		$this->display('OrdersShow');
	}

	//显示未发货列表页
	public function OrdersList(){
		$orders = M ('orders');  //订单表

		$where = array();
		$page_where = '';
		if(isset($_REQUEST['title']) and !empty($_REQUEST['title'])){
			$where['pname'] = array('like','%'. trim($_REQUEST['title'] .'%'));
			$page_where = 'title='. trim($_REQUEST['title']);
		}

		$where['uid']    = array('eq',$_SESSION[C('USER_AUTH_KEY')]);
		$where['status'] = array('eq',2);  //0未发货,1无货,2已发货

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

		$this->display('OrdersList');
	}

	//删除订单
	public function DelOrders(){
		$orders = M ('orders');

		$where = array();
		$where['uid']  = array('eq',$_SESSION[C('USER_AUTH_KEY')]);
		$where['id']   = array('eq',(int) $_GET['id']);

		$rs = $orders -> where($where) -> delete();
		if($rs){
			$bUrl = __URL__.'/OrdersShow';
			$this->_box(1,'订单已取消！',$bUrl,1);
		}else{
			$this->error ('数据错误!');
			exit;
		}
	}


	//等级升级页面显示
	public function slevel() {
			$fck = M ( 'fck' );
			$where = array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field ='*';
			$vo = $fck ->where($where)->field($field)->find();

			if ($vo){
				$this->assign ( 'vo', $vo );
				$this->display ();
			}else{
				$this->error ('数据错误!');
				exit;
			}
	}


	//升级数据保存(提交申请,待管理员审核)
	public function slevelsave() {  //升级保存数据
			//查看会员详细信息
			$fck = D ('Fck');
			$fee = M ('fee');
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$slevel = (int) $_POST['slevel'];  //升级等级

			$fee_rs = $fee -> field('s5') -> find();
			$fee_s1 = explode('|',$fee_rs['s5']);
			if( $slevel <= -1 or $slevel >= count($fee_s1)){
				$this->error('升级等级错误！');
				exit;
			}

			$data = array();
			$data['id']      = $ID;       //升级AutoId
			$data['sel_level'] = $slevel;   // 升级等级
			$fck -> data($data) -> save();

			$bUrl = __URL__.'/slevel';
			$this->_box(1,'会员升级！',$bUrl,1);
			exit;
	}



	//=========================过滤查询字段
	function _filter(&$map){
		    // ===============================注册时检查帐号
//		if(!preg_match('/^(\w){1,10}$/',$_POST['UserID'])) {
//            $this->error('(会员编号必须为1-10位的字母,数字和下划线！)');
//        }
		$fck = M('fck');
        $mapp            =   array();
		// ===================================支持使用绑定帐号登录
        $NickName = $_POST['UserID'];
        if(!preg_match("/^[A-Za-z0-9]+$/", $NickName)){
            $this->error('(会员编号只支持小写的字母 与 数字！)');
            exit;
        }
		//$mapp['nickname']	= array(array('eq',strtolower($_POST['UserID'])),array('eq',strtoupper($_POST['UserID'])),'or');//转换成小写
		$mapp2['user_id']	=  array(array('eq',strtolower($_POST['UserID'])),array('eq',strtoupper($_POST['UserID'])),'or');//转换成小写
		$field = 'id';
		//$authInfoo  = $fck->where($mapp)  -> field('id')->find();
		$authInfoo2 = $fck->where($mapp2) -> field('id')->find();
//		if($authInfoo) {
//			$this->error('(该会员编号已存在！)');
//			exit;
//		}
		if($authInfoo2) {
			$this->error('(该会员编号已存在！)');
			exit;
		}else{
			$this->success('(该会员编号可以使用！)');
		}






//		$map['user_id'] = array('like',"%".$_POST['UserID']."%");
//	}
//
//	public function checkAccount() {
//		$fck = M('fck');
//        $mapp            =   array();
//		$mapp['nickname']	= trim($_POST['NickName']);
//		$rs  = $fck->where($mapp)  -> field('id')->find();
//		if($rs){
//			$this->error('(该会员昵称已存在！)');
//			exit;
//		}else{
//			$this->success('(该会员昵称可以使用！)');
//		}
    }


	//检测报单中心是否存在
	public function check_shopid() {
		$fck = M('fck');
        $mapp               = array();
		$mapp['user_id']	= trim($_POST['shopid']);
		$rs  = $fck->where($mapp)  -> field('id')->find();
		if($rs) {
			$this->success(' ');
			exit;
		}else{
			$this->error('没有此报单中心！');
			exit;
		}

    }
	//检测推荐人是否存在
	public function check_reid() {
		$fck = M('fck');
        $mapp               = array();
		$mapp['user_id']	= trim($_POST['reid']);
		$rs  = $fck->where($mapp)  -> field('id')->find();
		if($rs) {
			$this->success(' ');
			exit;
		}else{
			$this->error('没有此推荐人！');
			exit;
		}

    }
	//检测接点人是否存在
	public function check_fid() {
		$fck = M('fck');
        $mapp               = array();
		$mapp['user_id']	= trim($_POST['fid']);
		$rs  = $fck->where($mapp)  -> field('id')->find();
		if($rs) {
			$this->success(' ');
			exit;
		}else{
			$this->error('没有此接点人！');
			exit;
		}

    }

	//检测用户名(会员名)是否已经存在
	public function check_userid() {
		$fck = M('fck');
        $mapp               = array();
		$mapp['user_id']	= trim($_POST['userid']);
		$rs  = $fck->where($mapp)  -> field('id')->find();
		if($rs) {
			$this->error('会员编号已被使用！');
			exit;
		}else{
			$this->success('会员编号可使用！');
			exit;
		}

    }

	//检测用户名(会员名)是否已经存在
	public function check_CCuser() {
		$fck = M('fck');
        $mapp               = array();
		$mapp['user_id']	= trim($_POST['userid']);
		$rs  = $fck->where($mapp)  -> field('id')->find();
		if($rs) {
			$this->success(' ');
			exit;
		}else{
			$this->error('会员编号输入错误！');
			exit;
		}

    }

	public function cody(){
		//===================================二级验证
		$UrlID = (int) $_GET['c_id'];
		if (empty($UrlID)){
			$this->error('二级密码错误!');
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

	public function codys(){
		//=============================二级验证后调转页面
		$Urlsz = (int) $_POST['Urlsz'];
		if(empty($_SESSION['user_pwd2'])){
			$pass  = $_POST['oldpassword'];
			$fck   =  M ('fck');
			if (!$fck->autoCheckToken($_POST)){
				$this->error('页面过期请刷新页面!');
	            exit();
			}
			if (empty($pass)){
				$this->error('二级密码错误!');
				exit();
			}

			$where = array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['passopen'] = md5($pass);
			$list = $fck->where($where)->field('id,is_agent')->find();
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
				$_SESSION['Urlszpass'] = 'MyssShuiPuTao';
				$bUrl = __URL__.'/menber';//未开通会员
				$this->_boxx($bUrl);
				break;
			case 2;
				$this->users(2);//会员注册
				$_SESSION['Urlszpass'] = 'MyssBoLuo';
				break;
			case 3;
				$_SESSION['Urlszpass'] = 'MyssHuoLongGuo';
				$bUrl = __URL__.'/relations';//直接推荐
				$this->_boxx($bUrl);
				break;
			case 4;
				if($list['is_agent'] >=2){
					$this->error('您已经是报单中心!');
            		exit();
				}
				$_SESSION['Urlszpass'] = 'MyssXiGua';
				$bUrl = __URL__.'/agents';//申请代理
                $this->_boxx($bUrl);
				break;
			case 5;
				$_SESSION['Urlszpass'] = 'MyssShiLiu';
				$bUrl = __URL__.'/finance';//财务明细表
				$this->_boxx($bUrl);
				break;
			case 6;
				$_SESSION['Urlszpass'] = 'MyssFenYingTao';
				$bUrl = __URL__.'/transferMoney';//奖金币转账
				$this->_boxx($bUrl);
				break;
			case 7;

				$_SESSION['Urlszpass'] = 'MyssPaoYingTao';
				$bUrl = __URL__.'/frontCurrency';//奖金币提现
				$this->_boxx($bUrl);
				break;
			case 8;
				$_SESSION['Urlszpass'] = 'MyssDaShuiPuTao';
				$bUrl = __URL__.'/frontMenber';//已开通会员
				$this->_boxx($bUrl);
				break;
			case 9;
				$_SESSION['Urlszpass'] = 'MyssShiLiu';
				$bUrl = __URL__.'/financeTable';//会员财务表
				$this->_boxx($bUrl);
				break;
			case 10;
				$_SESSION['Urlszpass'] = 'MyssMangGuo';
				$bUrl = __URL__.'/currencyRecharge';//充值
				$this->_boxx($bUrl);
				break;
			case 11;
                $_SESSION['Urlszpass'] = 'Mysssingle';
                $bUrl = __URL__.'/single';//申请加单
                $this->_boxx($bUrl);
                break;
            case 12;
                $_SESSION['Urlszpass'] = 'Myssbusiness';
                $bUrl = __URL__.'/business';//业务总统计
                $this->_boxx($bUrl);
                break;
            case 13;
				$_SESSION['Urlszpass'] = 'MyssXiGuaJb';
				$bUrl = __URL__.'/sq_jb';//申请金币中心
                $this->_boxx($bUrl);
				break;
			case 15;
				$_SESSION['Urlszpass'] = 'MyssXiGuaMsg';
				$bUrl = __URL__.'/messages';//短信留言
                $this->_boxx($bUrl);
				break;
			case 17;
                $_SESSION['Urlszpass'] = 'Myssxiaofei';
                $bUrl = __URL__.'/frontxiaofei';//消费申请
                $this->_boxx($bUrl);
                break;
            case 18;
                $_SESSION['Urlszpass'] = 'Myssjinji';
                $bUrl = __URL__.'/MenberJinji';//升级
                $this->_boxx($bUrl);
                break;
			case 19;
				$_SESSION['Urlszpass'] = 'Mysssqzy';
				$bUrl = __URL__.'/sq_zy';//短信留言
                $this->_boxx($bUrl);
				break;
			case 16;
				$_SESSION['Urlszpass'] = 'Mysskai';
				$bUrl = __URL__.'/user_level';//会员升级
				$this->_boxx($bUrl);
				break;
			default;
				$this->error('二级密码错误!');
				exit;
		}
	}
	// =======================================会员注册插入数据
	public function usersAdd() {
//		if ($_SESSION['Urlszpass'] == 'MyssBoLuo'){
			$id = $_SESSION[C('USER_AUTH_KEY')];
			$fck    = M ('fck');  //注册表


			$rs = $fck -> field('is_pay,agent_cash') -> find($id);
			$m = $rs['agent_cash'];
			if($rs['is_pay'] == 0){
				$this->error('临时会员不能注册会员！');
				exit;
			}
			if (strlen($_POST['UserID'])<1){
				$this->error('会员编号不能少！');
				exit;
			}

//			if (strlen($_POST['UserID'])<6){
//				$this->error('会员编号不能少于六位！');
//				exit;
//			}


//$ttt='AAA333';
//if(preg_match("/^[A-Z0-9]+$/", $ttt)){
//echo '正确';
//}else{
//echo '错误';
//}

			$data = array();  //创建数据对象
			//检测报单中心
			$shopid = trim($_POST['shopid']);  //所属报单中心帐号
			if (empty($shopid)){
				$this->error('请输入报单中心编号！');
				exit;
			}
			$smap = array();
			$smap['user_id'] = $shopid;
			$smap['is_agent'] = array('gt',1);
			$shop_rs = $fck->where($smap)->field('id,user_id')->find();
			if (!$shop_rs){
				$this->error('没有该报单中心！');
				exit;
			}else{
				$data['shop_id']   = $shop_rs['id'];      //隶属会员中心编号
				$data['shop_name'] = $shop_rs['user_id']; //隶属会员中心帐号
			}
			unset($smap,$shop_rs,$shopid);

			//检测推荐人
			$RID = trim($_POST['RID']);  //获取推荐会员帐号
			$mapp  = array();
			$mapp['user_id']	= $RID;
			$mapp['is_pay']	    = array('gt',0);
			$authInfoo = $fck->where($mapp)->field('id,user_id,re_level,re_path')->find();
			if ($authInfoo){
				$data['re_path'] = $authInfoo['re_path'].$authInfoo['id'].',';  //推荐路径
				$data['re_id'] = $authInfoo['id'];                              //推荐人ID
				$data['re_name'] = $authInfoo['user_id'];                       //推荐人帐号
				$data['re_level'] = $authInfoo['re_level'] + 1;                 //代数(绝对层数)
			}else{
				$this->error('推荐人不存在！');
				exit;
			}
			unset($authInfoo,$mapp);

			//检测上节点人
			$FID = trim($_POST['FID']);  //上节点帐号
			$mappp  = array();
			$mappp['user_id'] = $FID;
//			$mappp['is_pay']  = array('gt',0);
			$authInfoo = $fck->where($mappp)->field('id,p_path,p_level,user_id,is_pay')->find();
			if ($authInfoo){
				$fatherispay = $authInfoo['is_pay'];
				$data['p_path'] = $authInfoo['p_path'].$authInfoo['id'].',';  //绝对路径
				$data['father_id'] = $authInfoo['id'];                        //上节点ID
				$data['father_name'] = $authInfoo['user_id'];                 //上节点帐号
				$data['p_level'] = $authInfoo['p_level'] + 1;                 //上节点ID
			}else{
				$this->error('上级会员不存在！');
				exit;
			}
			unset($authInfoo,$mappp);
	 		$TPL = $_POST['TPL'];
			$where = array();
			$where['father_id'] = $data['father_id'];
			$where['treeplace'] = $TPL;
			$rs = $fck->where($where)->field('id')->find();
			if ($rs){
				$this->error('该位置已经注册！');
				exit;
			}else{
				$data['treeplace'] = $TPL;
			}


			if($fatherispay==0&&(int)$TPL>0){
				$this->error('接点人开通后才能在此位置注册！');
				exit;
			}

//			$fann = $fck->where('father_id='.$data['father_id'].' and is_pay>0')->count();
//			if($fann==0&&$TPL>0){
//				$this->error('上级左区开通后才能注册右区！');
//				exit;
//			}

//			$renn = $fck->where('re_id='.$data['re_id'].' and is_pay>0')->count();
//			if($renn<1){
//				$tjnn = $renn+1;
//				if($renn==0){
//					$oktp = 0;
//					$errtp = "A部门";
//				}
//				$zz_id = $this->pd_left_us($data['re_id'],$oktp);
//				$zz_rs = $fck->where('id='.$zz_id)->field('id,user_id')->find();
//				if($zz_id!=$data['father_id']){
//					$this->error('推荐第'.$tjnn.'人必须放在'.$zz_rs['user_id'].'的'.$errtp.'！');
//					exit;
//				}
//				if($TPL!=$oktp){
//					$this->error('推荐第'.$tjnn.'人必须放在'.$zz_rs['user_id'].'的'.$errtp.'！');
//					exit;
//				}
//			}
//			unset($rs,$where,$TPL);

			$fwhere = array();//检测帐号是否存在
			$fwhere['user_id'] = trim($_POST['UserID']);
			$frs = $fck->where($fwhere)->field('id')->find();
			if ($frs){
				$this->error('该会员编号已存在！');
				exit;
			}
			$kk = stripos($fwhere['user_id'],'-');
			if($kk){
				$this->error('会员编号中不能有扛(-)符号！');
				exit;
			}
			unset($fwhere,$frs);

			$errmsg="";
			if(empty($_POST['wenti_dan'])){
				$errmsg.="<li>密保答案不能为空！</li>";
//				$this->error('');
//				exit;
			}
			if(empty($_POST['BankCard'])){
				$errmsg.="<li>银行卡号不能为空！</li>";
//				$this->error('');
//				exit;
			}
			$huhu=trim($_POST['UserName']);
			if(empty($huhu)){
				$errmsg.="<li>请填写开户姓名！</li>";
//				$this->error('请填写开户姓名！');
//				exit;
			}

//			$cardd=trim($_POST['BankCard']);
//			if(strlen($cardd)!=19){
//				$this->error('请填写正确的19位银行卡号！');
//				exit;
//			}

//			if(empty($_POST['BankProvince'])||$_POST['BankProvince']=='请选择'){
//				$this->error('请选择开户省份！');
//				exit;
//			}
//			if(empty($_POST['BankCity'])||$_POST['BankCity']=='请选择'){
//				$this->error('请选择开户城市！');
//				exit;
//			}

//			$ZZZ=trim($_POST['BankAddress']);
//			if(empty($ZZZ)){
//				$errmsg.="<li>请填写详细开户地址！</li>";
////				$this->error('请填写支行地址！');
////				exit;
//			}
			if(empty($_POST['UserCode'])){
				$errmsg.="<li>请填写身份证号码！</li>";
//				$this->error('请填写身份证号码！');
//				exit;
			}
//			if(strlen($_POST['UserCode']) != 18){
//				$this->error('身份证号码必须为18位！');
//				exit;
//			}
			if(empty($_POST['UserTel'])){
				$errmsg.="<li>请填写电话号码！</li>";
//				$this->error('请填写电话号码！');
//				exit;
			}
//			if(strlen($_POST['UserTel']) != 11){
//				$this->error('11位电话号码输入不正确！');
//				exit;
//			}
			if(empty($_POST['UserEmail'])){
				$errmsg.="<li>请填写您的邮箱地址，找回密码时需使用！</li>";
//				$this->error('请填写您的邮箱地址，找回密码时需使用！');
//				exit;
			}

			$usercc=trim($_POST['UserCode']);
//			$fwhere = array();
//			$fwhere['user_code'] = $usercc;
//			$frs = $fck->where($fwhere)->field('id')->find();
//			if ($frs){
//				$this->error('该身份证号码已经被注册！');
//				exit;
//			}
//			unset($frs,$fwhere);




//				$fwhere = array();//检测昵称是否存在
//				$fwhere['nickname'] = trim($_POST['NickName']);
//				$frs = $fck->where($fwhere)->field('id')->find();
//				if ($frs){
//					$this->error('该会员名已存在！');
//					exit;
//				}
//				$nickname = $fwhere['nickname'];
//				unset($fwhere,$frs);


//			if(strlen($_POST['Password']) < 6 or strlen($_POST['Password']) > 16 or strlen($_POST['PassOpen']) < 6 or strlen($_POST['PassOpen']) > 16){
//				$this->error('密码应该是6-16位！');
//				exit;
//			}
			if(strlen($_POST['Password']) < 1 or strlen($_POST['Password']) > 16 or strlen($_POST['PassOpen']) < 1 or strlen($_POST['PassOpen']) > 16){
				$this->error('密码应该是1-16位！');
				exit;
			}
			if($_POST['Password'] != $_POST['rePassword']){  //一级密码
				$this->error('一级密码两次输入不一致！');
				exit;
			}
			if($_POST['PassOpen'] != $_POST['rePassOpen']){  //二级密码
				$this->error('二级密码两次输入不一致！');
				exit;
			}
			if($_POST['Password'] == $_POST['PassOpen']){  //二级密码
				$this->error('一级密码与二级密码不能相同！');
				exit;
			}
//				if($_POST['Password3'] != $_POST['rePassword3']){  //三级密码
//					$this->error('三级密码两次输入不一致！');
//					exit;
//				}

			$us_name = $_POST['us_name'];
			$us_address = $_POST['us_address'];
			$us_tel = $_POST['us_tel'];
			if(empty($us_name)){
				$errmsg.="<li>请输入收货人姓名！</li>";
			}
			if(empty($us_address)){
				$errmsg.="<li>请输入收货地址！</li>";
			}
			if(empty($us_tel)){
				$errmsg.="<li>请输入收货人电话！</li>";
			}

			$s_err = "<ul>";
			$e_err = "</ul>";
			if(!empty($errmsg)){
				$out_err = $s_err.$errmsg.$e_err;
				$this->error($out_err);
				exit;
			}


			$uLevel = $_POST['u_level'];
			$fee  = M ('fee') -> find();
			$s    = $fee['s9'];
			$s2 = explode('|',$fee['s2']);
			$s9 = explode('|',$fee['s9']);

			$product = M ('product');
			$gouwu = M ('gouwu');
			$ydate = mktime();
			$cpid = $_POST['uid'];//所以产品的ID
			if (empty($cpid)){
				$this->error('请选择产品！');
				exit;
			}

			$pro_where = array();
			$pro_where['id'] = array ('in',$cpid);
			$pro_rs = $product->where($pro_where)->select();
			$cpmoney = 0;//产品总价
			$txt = "";
			foreach ($pro_rs as $pvo){
				$aa = "shu".$pvo['id'];
				$cc = $_POST[$aa];
				if ($cc != 0) {
					$cpmoney = $cpmoney + $pvo['money'] * $cc;
					$txt .= $pvo['id'] .',';
				}
			}
			unset($pro_rs);

			$F4     = $s2[$uLevel];//认购单数
			$ul     = $s9[$uLevel];

			if($cpmoney!=$ul){
				$this->error('产品金额和级别对不上，请重新选择！');
				exit;
			}

//			$lvp	= $s4[$uLevel]/100;
//			if($m <$ul){
//				$this->error('电子币不足！');
//				exit;
//			}
			//检测注册等级


			//$history->execute("INSERT INTO __TABLE__ (uid,action_type,pdt,epoints,bz,zong,qubie) VALUES ($cid,100,$ydate,'-$p2','购物',$Zong,3) ");
			$Money = explode('|',C('Member_Money'));  //注册金额数组

			$new_userid = $_POST['UserID'];

			$data['user_id']             = $new_userid;
			$data['bind_account']        = '3333';
			$data['last_login_ip']       = '';                            //最后登录IP
			$data['verify']              = '0';
			$data['status']              = 1;                             //状态(?)
			$data['type_id']             = '0';
			$data['last_login_time']     = time();                        //最后登录时间
			$data['login_count']         = 0;                             //登录次数
			$data['info']                = '信息';
			$data['name']                = '名称';
			$data['password']            = md5(trim($_POST['Password']));  //一级密码加密
			$data['passopen']            = md5(trim($_POST['PassOpen']));  //二级密码加密
			$data['pwd1']                = trim($_POST['Password']);       //一级密码不加密
			$data['pwd2']                = trim($_POST['PassOpen']);       //二级密码不加密

			$data['wenti']				= trim($_POST['wenti']);  //密保问题
			$data['wenti_dan']			= trim($_POST['wenti_dan']);  //密保答案

			$data['bank_name']           = $_POST['BankName'];             //银行名称
			$data['bank_card']           = $_POST['BankCard'];             //帐户卡号
			$data['user_name']           = $_POST['UserName'];             //姓名
			$data['nickname']			  = $_POST['UserID'];//$_POST['nickname'];  //昵称
			$data['bank_province']       = $_POST['BankProvince'];  //省份
			$data['bank_city']           = $_POST['BankCity'];      //城市
			$data['bank_address']        = $_POST['BankAddress'];          //开户地址
			//$data['user_post']           = $_POST['UserPost']; 		   //
			$data['user_code']           = $_POST['UserCode'];             //身份证号码
			$data['user_address']        = $_POST['UserAddress'];          //联系地址
			$data['email']               = $_POST['UserEmail'];            //电子邮箱
			$data['qq']              	 = $_POST['qq'];            	   //qq
			$data['user_tel']            = $_POST['UserTel'];              //联系电话
			$data['is_pay']              = 0;                              //是否开通
			$data['rdt']                 = time();                         //注册时间
//			$data['pdt']                 = strtotime(date('Y-m-d'));
			$data['u_level']             = $uLevel+1;                      //注册等级
			$data['cpzj']                = $ul;                          //注册金额
			$data['f4']                  = $F4;							//单量
			$data['wlf']                 = 0;                              //网络费

//			$data['wang_j']				= 1;						//网络图
//			$data['wang_t']				= 1;						//推荐图
			//$fck->create($data);
//			dump($data);exit;
			$result = $fck->add($data);

			unset($data,$fck);
			if($result) {
				$where1['id'] = array ('in',$txt.'0');
				$rs1 = $product->where($where1)->select();
				$i=0;
				$p=array();
				foreach ($rs1 as $b) {
					$id = $b['id'];
					$cpid = $b['id'];
					$aa1 = "shu" . $b['id'];
					$cc1 = $_POST[$aa1];
					if ($cc1 != 0) {
						$hy1 = $b['money'];

						$p[$i] = $hy1 * $cc1;
						$p1 = $hy1 * $cc1;
						$i++;

						$gwd = array();
						$gwd['uid'] = $result;
						$gwd['user_id'] = $new_userid;
						$gwd['did'] = $cpid;
						$gwd['lx'] = 0;
						$gwd['ispay'] = 0;
						$gwd['pdt'] = mktime();
						$gwd['money'] = $hy1;
						$gwd['shu'] = $cc1;
						$gwd['cprice'] = $p1;
						$gwd['us_name'] = $us_name;
						$gwd['us_address'] = $us_address;
						$gwd['us_tel'] = $us_tel;
						$gouwu->add($gwd);
					}
				}
				unset($product,$gouwu,$rs1);
				$_SESSION['new_user_reg_id'] = $result;

				echo "<script>window.location='".__URL__."/users_ok/';</script>";
				exit;

//				$bUrl = __URL__.'/users/FID/'.$FID.'/RID='.$RID;
//				$this->_box(1,'会员注册成功！',$bUrl,1);
			}else{
				$this->error('会员注册失败！');
				exit;
			}
//		}else{
//			$this->error('会员注册失败！');
//			exit;
//		}
	}

	public function users_ok(){

		$gourl = __APP__."/Tree/cody/c_id/5";
		if(!empty($_SESSION['new_user_reg_id'])){

			$fck = M('fck');
			$fee_rs = M ('fee') -> find();

			$this -> assign('s8',$fee_rs['s8']);
			$this -> assign('alert_msg',$fee_rs['str28']);
			$this -> assign('s17',$fee_rs['s17']);

			$myrs = $fck->where('id='.$_SESSION['new_user_reg_id'])->find();
			$this->assign('myrs',$myrs);

			$this->assign('gourl',$gourl);
			unset($fck,$fee_rs);
			$this->display();
		}else{
			echo "<script>window.location='".$gourl."';</script>";
			exit;
		}
	}


	public function users($Urlsz=0){
	//==========================================================会员注册
//		if ($_SESSION['Urlszpass'] == 'MyssBoLuo' ){
			$fck = M ('fck');
			$fee = M ('fee');
			$RID = (int) $_GET['RID'];
			$FID = (int) $_GET['FID'];
			$TP = (int) $_GET['TPL'];
			if (empty($TPL))$TPL = 0;
			$TPL = array();
			for($i=0;$i<5;$i++){
				$TPL[$i] = '';
			}
			$TPL[$TP] = 'selected="selected"';

			//===报单中心
			$zzc = array();
			$where = array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field ='user_id,is_agent,agent_cash';
			$rs = $fck ->where($where)->field($field)->find();
			$money = $rs['agent_cash'];
			$mmuserid = $rs['user_id'];
			if ($rs['is_agent'] >= 2){
				$zzc[1] = $rs['user_id'];
			}else{
				$zzc[1] = '100000';
			}
			$this->assign('myid',$_SESSION[C('USER_AUTH_KEY')]);

			//===推荐人
			$where['id'] = $RID;
			$field ='user_id,is_agent';
			$rs = $fck ->where($where)->field($field)->find();
			if ($rs){
				$zzc[2] = $rs['user_id'];
			}else{
				$zzc[2] = $mmuserid;
			}
			$zzc[2] = $mmuserid;
			//===接点人
			$where['id'] = $FID;
			$field ='user_id,is_agent';
			$rs = $fck ->where($where)->field($field)->find();
			if ($rs){
				$zzc[3] = $rs['user_id'];
			}else{
				$zzc[3] = '';
			}

			//生成编号
//			$data_temp = array();
//			//$fck_rs = $fck -> field('user_id') -> find();
//			for($gooduser = 0;$gooduser == 0;){
//				//$data_temp['user_id'] =  date('ymd',strtotime(date('c'))) . mt_rand(1000,9999);
//				$data_temp['user_id'] =  mt_rand(100000,999999);
//				$temp_rs = $fck -> where($data_temp) -> find();
//				if(!$temp_rs){
//					$gooduser = 1;
//				}
//			}
//			$this->assign('user_id',$data_temp['user_id']);
			$arr = array();
            for ($i=1;$i<=10;$i++){
                $arr[$i]['UserID'] = $this->_getUserID($arr,$i);
            }
            $this->assign('flist',$arr);

			$pwhere = array();
			$product = M ('product');
			$prs = $product->where($pwhere)->select();
			$this->assign('plist',$prs);



			$fee_s = $fee->field('s2,s9,i4,str29,str99')->find();
			$s9 = $fee_s['s9'];
			$s9 = explode('|',$s9);

			$i4 = $fee_s['i4'];
			if ($i4==0){
				$openm=1;
			}else{
				$openm=0;
			}
//			dump($i4);exit;
			//输出银行
			$bank = explode('|',$fee_s['str29']);
			//输出级别名称
            $Level = explode('|',C('Member_Level'));
			//输出注册单数
			$Single = explode('|',C('Member_Single'));
			//输出一单的金额
//			$Money = explode('|',C('Member_Money'));

			$wentilist = explode('|',$fee_s['str99']);

			$this->assign('s9',$s9);
			$this->assign('openm',$openm);
			$this->assign('bank',$bank);
            $this->assign('Level',$Level);
			$this->assign('Single',$Single);
			$this->assign('Money',$fee_s['s2']);
			$this->assign('Money1',$money);
			$this->assign('wentilist',$wentilist);

			unset($bank,$Level,$$Level);


		    $this->assign('TPL',$TPL);
			$this->assign('zzc',$zzc);

			unset($fck,$TPL,$where,$field,$rs,$data_temp,$temp_rs,$rs);
			$this->display('users');
//		}else{
//			$this->error('数据错误!');
//			exit;
//		}
	}

	//判断最左区
	public function pd_left_us($uid,&$tp){

		$fck = M('fck');

		$c_l = $fck->where('father_id='.$uid.' and treeplace='.$tp.'')->field('id')->find();
		if($c_l){
			$n_id = $c_l['id'];
			$tp = 0;
			$ren_id = $this->pd_left_us($n_id,$tp);
		}else{
			$ren_id = $uid;
		}
		return $ren_id;
	}


	private function _getUserID(){
		//生成会员编号
		$fck = M('fck');
		$uUserID = ''.rand(100000,999999);
		$fwhere['UserID'] = $uUserID;
		$frss = $fck->where($fwhere)->field('ID')->find();
		if ($frss){
			$this->_getUserID();
		}else{
			return $uUserID;
		}
	}

//会员晋级
	public function MenberJinji(){
		if ($_SESSION['Urlszpass'] == 'Myssjinji'){
			$where = array();
			$fck = M('fck');

	    	$uid = $_SESSION[C('USER_AUTH_KEY')];

			$frs = $fck->find($uid);
			$voo = 0;
			$this->_levelConfirm($voo);

			$level = array();
			for($i=1;$i<=count($voo) ;$i++){
				$level[$i] = $voo[$i];
			}
			$this->assign('level',$level);


			$fee = M ('fee');
			$fee_rs =$fee->field('s1,s2,s9,s4,s5')->find();
			$s1 =explode('|',$fee_rs['s1']);
			$s2 =explode('|',$fee_rs['s2']);
			$s3 =explode('|',$fee_rs['s9']);
			$s4 =$fee_rs['s4'];

			$this->assign('sx1',$s3);

//			$sx2 = $sx1 - $frs['xxxx1'];

			$promo = M('promo');
			$field  = '*';
			$map['uid'] =$uid;
            $list = $promo->where($map)->field($field)->order('id desc')->select();


            $this->assign('list',$list);//数据输出到模板
            //=================================================

//			$this->assign('ls',$s);
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

	//
	public function jinjiConfirm(){
			$ulevel = $_POST['uLevel'];
			$uid = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $uid;
			$promo = M('promo');
			$fck = D('Fck');
			$fck_rs = $fck->where($where)->find();
			$fee = M ('fee');
			$fee_rs =$fee->field('s1,s2,s9,s4,s5')->find();
			$s1 =explode('|',$fee_rs['s1']);
			$s2 =explode('|',$fee_rs['s2']);
			$s3 =explode('|',$fee_rs['s9']);//金额
			$s4 =explode('|',$fee_rs['s4']);
			$s5 =explode('|',$fee_rs['s5']);

			$ulevel = $ulevel;
			$needlv = $ulevel-1;
			$oldlv  = $fck_rs['u_level']-1;
//			$needmo = $s3[$needlv];
			$oldpv = $s3[$oldlv];
			$newpv = $s3[$needlv];
			$needpv = $newpv-$oldpv;

			$needdl = $s2[$needlv];
			$olddl  = $s2[$oldlv];
			$okdanl = $needdl-$olddl;

			$ok = $fck_rs['agent_cash']-$needpv;
			if($fck_rs['u_level'] >=$ulevel){
				$this->error('升级参数不正确！');
			}

 			if($fck_rs['u_level'] ==4){
				$this->error('已经是最高级，无法再升级！');
			}

			$content = $_POST['content'];		//备注
			if (empty($content)){
				$this->error('备注不能为空!');
				exit;
			}

			if ($ok < 0){
				$this->error('您的电子货币金额不足!');
				exit;
			}

			$promo->startTrans();
			// 写入帐号数据
			$data['money']				= $needpv;	//注册金额
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

				$fck->execute("UPDATE __TABLE__ set agent_cash=agent_cash-{$needpv} where `id`=".$uid);
				$fck->commit();//提交事务

				$ss = $fck->where('id ='.$uid)->field('re_id,user_id')->find();

				$oldxf = $s5[$oldlv];
				$newxf = $s5[$needlv];
				$xiaof = $newxf-$oldxf;

				$oldjf = $s4[$oldlv];
				$newjf = $s4[$needlv];
				$gpjf = $newjf-$oldjf;

				$fck->query("update __TABLE__ set u_level=".$ulevel.",cpzj=".$newpv.",f4=".$needdl." where `id`=".$uid);

				$bUrl = __URL__.'/MenberJinji';
				$this->_box(1,'您晋级申请成功！',$bUrl,3);
			}else{
				//事务回滚：
                $fck->rollback();
				$this->error('晋级申请失败！');
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

	//=============================================电子币转帐(会员之间的电子币转帐)
	public function transferMoney($Urlsz=0){
		if ($_SESSION['Urlszpass'] == 'MyssFenYingTao'){
			$zhuanj = M('zhuanj');
			$map['in_uid'] = $_SESSION[C('USER_AUTH_KEY')];
			$map['out_uid'] = $_SESSION[C('USER_AUTH_KEY')];
			$map['_logic'] = 'or';



//			$id = $_SESSION[C('USER_AUTH_KEY')];
//			$sql = "in_uid =".$id ." or out_uid = ".$id;
			$field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $zhuanj->where($map)->count();//总页数
            $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $Page = new ZQPage($count,$listrows,1);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $zhuanj->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$fck = M ('fck');
			$where = array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field = 'agent_use,agent_cash,is_agent';
			$rs = $fck->where($where)->field($field)->find();
			$this->assign('rs',$rs);
			$this->display('transferMoney');
			return;
		}else{
			$this->error ('错误!');
			exit;
		}
	}


	public function transferMoneyAC(){
		$UserID = $_POST['UserID'];    //转入会员帐号(进帐的用户帐号)
	//	$ePoints = (int) $_POST['ePoints'];
		$ePoints = $_POST['ePoints'];  //转入金额
		$content = $_POST['content'];  //转帐说明
		$select = $_POST['select'];  //转帐类型

		$fck = M ('fck');
		$where = array();
		$where['id']= $_SESSION[C('USER_AUTH_KEY')];

		$f = $fck->where($where )->field('user_id')->find();

		if ($select==2||$select==3||$select==4) $UserID = $_SESSION['loginUseracc'];

		$fck = M ('fck');
	    if (!$fck->autoCheckToken($_POST)){
            $this->error('页面过期，请刷新页面！');
            exit;
        }
		if (empty($ePoints) || !is_numeric($ePoints) || empty($UserID)){
			$this->error('输入不能为空!');
			exit;
		}
		if($ePoints <= 0){
			$this->error('输入的金额有误!');
			exit;
		}
//		if($UserID == $f['user_id']){
//			$this->error('不能转给自己!');
//			exit;
//		}
		$this->_transferMoneyConfirm($UserID,$ePoints,$content,$select);
	}

	private function _transferMoneyConfirm($UserID='0',$ePoints=0,$content=null,$select=0){
		if ($_SESSION['Urlszpass'] == 'MyssFenYingTao'){  //转帐权限session认证
			$fck = M ('fck');
			$where = array();
			$ID = $_SESSION[C('USER_AUTH_KEY')];     //登录会员AutoId
			$inUserID =  $_SESSION['loginUseracc'];  //登录的会员帐号 user_id
			//转出
			$history = M ('history');  //明细表
			$zhuanj  = M ('zhuanj');   //转帐表

			$myww = array();
			$myww['id'] = array('eq',$ID);
			$mmrs = $fck->where($myww)->find();
			$mmid = $mmrs['id'];
			$mmisagent = $mmrs['is_agent'];
			if($mmid==1){
				$mmisagent = 0;
			}

			//查询条件
			$where['user_id'] = $inUserID;  //登录的会员帐号 user_id
			$field ='id,user_id,agent_use,is_agent,agent_cash';
			$vo2 = $fck ->where($where)->field($field)->find();

			//转入会员
			$fck_where = array();
			$fck_where['user_id'] = $UserID;
			$vo = $fck ->where($fck_where)->field($field)->find();  //找出转入会员记录
			if (!$vo){
				$this->error('转入会员不存在!');
				exit;
			}
			$fee_rs = M ('fee') -> find();
			$str3 = $fee_rs['str3'];

			$hB = 10;//最低额
			$mmB = $str3;//最低额
			if($select==1){
				if($ePoints<$mmB){
					$this->error ('转账最低额度必须为 '.$mmB.' ！');
					exit;
				}
			}
			if ($ePoints % $mmB){
				$this->error ('额度必须为 '.$mmB.' 的整数倍!');
				exit;
			}
//			if($select<3){
//				if ($ePoints % $hB){
//					$this->error ('额度必须为 '.$hB.' 的整数倍!');
//					exit;
//				}
//			}

			$AgentUse = $vo2['agent_cash'];
			if($select==1||$select==4){
				if ($AgentUse < $ePoints){            //判断电子币余额
				$this->error('电子币余额不足!');
				exit;
			}
			}

			$AgentUseTwo = $vo2['agent_use'];
			if($select==2||$select==3){
				if ($AgentUseTwo < $ePoints){            //判断奖金余额
					$this->error('奖金币余额不足!');
					exit;
				}
			}
			unset($vo2);
			$history->startTrans();//开始事物处理
			if($select==1){
				$fck->execute("update __TABLE__ Set `agent_cash`=agent_cash-".$ePoints." where `id`=".$ID);
				$fck->execute("update __TABLE__ Set `agent_cash`=agent_cash+".$ePoints." where `id`=".$vo['id']);
			}
			if($select==2){
				$fck->execute("update __TABLE__ Set `agent_use`=agent_use-".$ePoints." where `id`=".$ID);
				$fck->execute("update __TABLE__ Set `agent_cash`=agent_cash+".$ePoints." where `id`=".$vo['id']);
			}
			if($select==3){
				$fck->execute("update __TABLE__ Set `agent_use`=agent_use-".$ePoints." where `id`=".$ID);
				$fck->execute("update __TABLE__ Set `fanli_time`=fanli_time+".$ePoints." where `id`=".$vo['id']);
			}
			if($select==4){
				$fck->execute("update __TABLE__ Set `agent_cash`=agent_cash-".$ePoints." where `id`=".$ID);
				$fck->execute("update __TABLE__ Set `fanli_time`=fanli_time+".$ePoints." where `id`=".$vo['id']);
			}



			$nowdate = time();
			$data = array();
			$data['uid']           = $ID;          //转出会员ID
			$data['user_id']       = $UserID;
			$data['did']           = $vo['id'];    //转入会员ID
			$data['user_did']      = $vo['user_id'];
			$data['action_type']   = 20;    //转入还是转出
			$data['pdt']           = $nowdate;     //转帐时间
			$data['epoints']       = $ePoints;     //进出金额
			$data['allp']          = 0;
			$data['bz']            = $content;     //备注
			$data['type']          = 1;   		   //1转帐
			$history->create();
			$rs2=$history->add($data);
			unset($data);
			//转账表
			$data = array();
			$data['in_uid']        = $vo['id'];           //转入会员ID
			$data['out_uid']       = $ID;
			$data['in_userid']     = $vo['user_id'];      //转入会员的登录帐号
			$data['out_userid']    = $inUserID;
			$data['epoint']        = $ePoints;            //进出金额
			$data['rdt']           = $nowdate;            //转帐时间
			$data['sm']            = $content;            //转帐说明
			//$data['type']          = 0;                 // 3为货币转为货币
			$data['type']   = $select;
			//dump($select);exit;
			$zhuanj->create();
			$rs4=$zhuanj->add($data);
			unset($data);

			//无错误提交数据
			if ($rs2 && $rs4){
				$history->commit();//提交事务
				$bUrl = __URL__.'/transferMoney';
				$this->_box(1,'操作成功！',$bUrl,1);
			    exit;
			}else{
				$history->rollback();//事务回滚：
				$this->error('输入数据错误！');
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}



	public function menber($Urlsz=0){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['Urlszpass'] == 'MyssShuiPuTao'|| $_SESSION['UrlPTPass'] = 'MyssGuanXiGua'){
			$fck = M('fck');
			$map = array();
			$id = $_SESSION[C('USER_AUTH_KEY')];
			$gid = (int) $_GET['bj_id'];
//			$map['shop_id'] = (int) $_GET['bj_id'];
//			if($gid==0){
				$map['shop_id'] = $id;
//			}
//			$map['_string'] = 'shop_id='.$id.' or re_id='.$id.'';
////			$map['re_id'] = (int) $_GET['bj_id'];
//			if($gid==0){
//				$map['re_id'] = $id;
//			}
//			$map['is_pay'] = array('gt',0);
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


            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
    	    $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID='.$UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('is_pay asc,pdt desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================
			$HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
			$where = array();
			$where['id'] = $id;
			$fck_rs = $fck->where($where)->field('*')->find();
			$this->assign('frs',$fck_rs);//电子币
			$this->display ('menber');
			exit;
		}else{
			$this->error('数据错误!');
			exit;
		}
	}

	//推荐列表
	public function frontMenber($Urlsz=0){
		//列表过滤器，生成查询Map对象
		if ($_SESSION['Urlszpass'] == 'MyssDaShuiPuTao'){
			$fck = M('fck');
			$id = $_SESSION[C('USER_AUTH_KEY')];
			$map = array();
			$map['re_id'] = $id;
			//$map['is_pay'] = array('egt',0);
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

			//if (! empty ( $fck )) {
			//	$this->_list ( $fck, $map,'id',0 );
			//}

            //查询字段
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
    	    $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID='.$UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc')->page($Page->getPage().','.$listrows)->select();

            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ('frontMenber');
			exit;
		}else{
			$this->error('数据错误2!');
			exit;
		}
	}

	//=====================================已开通会员-查看会员信息
	public function menberData() {
		if ($_SESSION['Urlszpass'] == 'MyssShuiPuTao' || $_SESSION['Urlszpass'] == 'MyssHuoLongGuo' || $_SESSION['Urlszpass'] == 'MyssDaShuiPuTao' || $_SESSION['UrlPTPass'] = 'MyssGuanXiGua'){
			//查看会员详细信息
			$fck = M ( 'fck' );
			$id = (int) $_GET['bj_id'];
			//判断获取数据的真实性 是否为数字 长度
//			if (strlen($id) > 11){
//				$this->error ('数据错误1!');
//				exit;
//			}
			$where = array();
			//查询条件
			//$where['shop_id'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $id;
			$field = '*';
			$vo = $fck ->where($where)->field($field)->find();
			if ($vo){
				$this->assign ( 'vo', $vo );
				if($_GET["eth"] == 2){
					$this->assign ( 'methods', __APP__.'/YouZi/adminAgents');  //返回到这个链接
				}else{
					$this->assign ( 'methods', __URL__.'/'.$_GET["eth"]);  //返回到这个链接
				}
				$this->display('public:profile');
			}else{
				$this->error ('数据错误2!');
				exit;
			}
		}else{
			$this->error ('数据错误3!');
			exit;
		}
	}
	public function relations($Urlsz=0){
		//推荐关系
		if ($_SESSION['Urlszpass'] == 'MyssHuoLongGuo'){
			$fck = M('fck');
			$UserID = $_REQUEST['UserID'];
			if (!empty($UserID)){
				$map['user_id'] = array('like',"%".$UserID."%");
			}
//			if (!empty($_GET['bj_id'])){
//				$map['re_id'] = (int) $_GET['bj_id'];
//			}else{
//				$map['re_id'] = $_SESSION[C('USER_AUTH_KEY')];//自身推荐
//			}
			$map['re_id'] = $_SESSION[C('USER_AUTH_KEY')];//自身推荐
			$map['is_pay'] = 1;

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $fck->where($map)->count();//总页数
    	    $listrows = C('ONE_PAGE_RE');//每页显示的记录数
			$page_where = 'UserID='.$UserID;//分页条件
//            $page_where ='';
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $fck->where($map)->field($field)->order('pdt desc')->page($Page->getPage().','.$listrows)->select();
            $HYJJ = '';
            $this->_levelConfirm($HYJJ,1);
            $this->assign('voo',$HYJJ);//会员级别
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ('relations');
			return;
		}else{
			$this->error('数据错误2!');
			exit;
		}
	}
	public function menberAC(){
		//处理提交按钮
		$action = $_POST['action'];
		//获取复选框的值
		$OpID = $_POST['tabledb'];
		if (!isset($OpID) || empty($OpID)){
			$bUrl = __URL__.'/menber';
			$this->_box(0,'没有该会员！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '开通会员':
				$this->_menberOpenUse($OpID);
				break;
			case '删除会员':
				$this->_menberDelUse($OpID);
				break;
			default:
			$bUrl = __URL__.'/menber';
			$this->_box(0,'没有该会员！',$bUrl,1);
			break;
		}
	}



//	private function _menberOpenUse($OpID=0){
	private function _menberOpenUse($OpID=0,$reg_money=0){
		//=============================================开通会员
		if ($_SESSION['Urlszpass'] == 'MyssShuiPuTao'){
			$fck = D ('Fck');
			$fee = M ('fee');
			$gouwu = M ('gouwu');
		    if (!$fck->autoCheckToken($_POST)){
                $this->error('页面过期，请刷新页面！');
                exit;
            }

			//被开通会员参数
			$where = array();
			$where['id'] = array ('in',$OpID);  //被开通会员id数组
			$where['is_pay'] = 0;  //未开通的
			$field = '*';
			$vo = $fck ->where($where)->field($field)->select();
			$fee_rs = $fee -> find();
			$ssl = $fee_rs['s6']/100;

			//报单中心参数
			$where_two =array();
			$field_two = '*';
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$where_two['id'] = $ID;
//			$where_two['is_agent'] = array('gt',1);
			$nowdate = strtotime(date('c'));
			$nowday=strtotime(date('Y-m-d'));
			$fck->emptyTime();
			foreach($vo as $voo){  //找出所有待开通会员记录

				$rs = $fck->where($where_two)->field($field_two)->find();  //找出登录会员(必须为报单中心并且已经登录)
				if (!$rs){
					$this->error('会员错误！');
					exit;
				}

				$ppath=$voo['p_path'];
				//上级未开通不能开通下级员工
				$frs_where['is_pay'] = array('eq',0);
				$frs_where['id'] = $voo['father_id'];
				$frs = $fck -> where($frs_where) -> find();
				if($frs){
					$this->error('开通失败，上级未开通');
					exit;
				}

				$money_a = $voo['cpzj'];	//百分之
//				$money_b = $voo['cpzj']-$money_a;	//百分之30
				$money_b = 0;

				if($reg_money == 0){
					if ($rs['agent_cash'] < $money_a){
						$bUrl = __URL__.'/menber';
						$this->_box(0,'电子币余额不足！',$bUrl,1);
						exit;
					}

//					if ($rs['agent_kt'] < $money_b){
//						$bUrl = __URL__.'/menber';
//						$this->_box(0,'开通币余额不足！',$bUrl,1);
//						exit;
//					}
				}

				$shop_id = $voo['shop_id'];

				$fck->query("update __TABLE__ set `re_nums`=re_nums+1,re_f4=re_f4+".$voo['f4']." where `id`=".$voo['re_id']);

				//生成发货单
				M('gouwu')->query("update __TABLE__ set lx=1 where uid=".$voo['id']);

				$fck->query("update __TABLE__ set `is_pay`=1,`pdt`=".$nowdate.",open=0,get_date=".$nowday." where `id`=".$voo['id']);

				//统计单数
				$fck->xiangJiao($voo['id'], $voo['f4']);

				$money = $voo['cpzj'];  //找出该会员的注册金额
				$fck->query("update __TABLE__ set `agent_cash`=agent_cash-". $money_a .",`agent_kt`=agent_kt-". $money_b ." where `id`=".$ID);
				$fck->addencAdd($rs['id'], $voo['user_id'], -$money, 19);//历史记录

				//算出奖金
				$fck->getusjj($voo['id'],1);

				//全部奖金结算
				$this->_clearing();

			}
//			$this->_clearing();
			unset($fck,$where,$where_two,$rs);
			if ($vo){
				unset($vo);
				$bUrl = __URL__.'/menber';
				$this->_box(1,'开通会员成功！',$bUrl,2);
				exit;
			}else{
				unset($vo);
				$bUrl = __URL__.'/menber';
				$this->_box(0,'开通会员失败！',$bUrl,1);
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}

	private function _menberDelUse($OpID=0){
		//=========================================删除会员
		if ($_SESSION['Urlszpass'] == 'MyssShuiPuTao'){
			$fck = M ('fck');
			$gouwu = M('gouwu');
			$where['is_pay'] = 0;
		//	$where['id'] = array ('in',$OpID);

			foreach($OpID as $voo){
				$rs = $fck -> find($voo);
				if($rs){
					$whe['father_name'] = $rs['user_id'];
					$rss = $fck -> where($whe) -> find();
					if($rss){
						$bUrl = __URL__.'/menber';
						$this -> error('该 '. $rs['user_id'] .' 会员有下级会员，不能删除！');
						exit;
					}else{
						$gouwu->where('uid='.$voo)->delete();

		            	$where['id'] = $voo;
			            $fck -> where($where) -> delete();
					}
				}else{
					$this->error('错误!');
				}
			}
			$bUrl = __URL__.'/menber';
			$this->_box(1,'删除会员成功！',$bUrl,1);
			exit;

	//		father_name
//			$rs = $fck->where($where)->delete();
//			if ($rs){
//				$bUrl = __URL__.'/menber';
//				$this->_box(1,'删除会员！',$bUrl,1);
//				exit;
//			}else{
//				$bUrl = __URL__.'/menber';
//				$this->_box(0,'删除会员！',$bUrl,1);
//				exit;
//			}
		}else{
			$this->error('错误!');
		}
	}


	public function agents($Urlsz=0){
		//======================================申请会员中心/代理中心/报单中心
		if ($_SESSION['Urlszpass'] == 'MyssXiGua'){
			$fee_rs = M ('fee') -> find();

			$fck = M ('fck');
			$where = array();
			//查询条件
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field ='*';
			$fck_rs = $fck ->where($where)->field($field)->find();

			if ($fck_rs){
				//会员级别
				switch($fck_rs['is_agent']){
					case 0:
						$agent_status = '未申请报单中心!';
						break;
					case 1:
						$agent_status = '申请正在审核中!';
						break;
					case 2:
						$agent_status = '报单中心已开通!';
						break;
				}

				$this->assign ( 'fee_s6',$fee_rs['i1']);
				$this->assign ( 'agent_level',0);
				$this->assign ( 'agent_status',$agent_status);
				$this->assign ( 'fck_rs', $fck_rs);
				$this->display ('agents');
			}else{
				$this->error ('操作失败!');
				exit;
			}
		}else{
			$this->error ('错误!');
			exit;
		}
	}


	public function agentsAC(){
		//================================申请会员中心中转函数
		$content  = $_POST['content'];
		$agentMax = $_POST['agentMax'];
		$shoplx  = (int)$_POST['shoplx'];
		$shop_a  = $_POST['shop_a'];
		$shop_b  = $_POST['shop_b'];
		$fck = M ('fck');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$where = array();
		$where['id'] = $id;

		$fck_rs = $fck->where($where)->field('id,is_agent,u_level,is_pay,user_id,user_name,agent_max,is_agent')->find();

		if($fck_rs){
			if($fck_rs['is_pay']  == 0){
				$this->error ('临时会员不能申请!');
				exit;
			}
			if($fck_rs['is_agent']  == 1){
				$this->error('上次申请还没通过审核!');
				exit;
			}
			if(empty($content)){
				$this->error ('请输入备注!');
				exit;
			}
			if($shoplx==0){
				$this->error('上次申请还没通过审核!');
				exit;
			}

//			if($fck_rs['u_level']  < 2){
//				$this->error('您未达成报单中心的申请条件！');
//				exit;
//			}

//			$countp = $fck->where('re_id='.$id.' and re_nums>=7 and is_pay>0')->count();
//			if($countp<3){
//				$this->error('您尚未达成报单中心的申请条件！');
//				exit;
//			}

//			if($shop_a=='请选择'||$shop_a==''){
//				$this->error('请选择代理 省（市）区域！');
//				exit;
//			}
//			if($shoplx==3){
//				$awhere=array();
//				$awhere['is_agent'] = array('gt',0);
//				$awhere['shoplx'] = 3;
//				$awhere['shop_a'] = $shop_a;
//				$count=$fck->where($awhere)->count();
//				if($count>0){
//					$this->error('该区域 已存在 市级代理！');
//					exit;
//				}
//				unset($awhere,$count);
//			}
//			if($shoplx!=3){
//				if($shop_b=='请选择'||$shop_b==''){
//					$this->error('请选择代理 县（市）区域！');
//					exit;
//				}
//				if($shoplx==2){
//					$awhere=array();
//					$awhere['is_agent'] = array('gt',0);
//					$awhere['shoplx'] = 2;
//					$awhere['shop_a'] = $shop_a;
//					$awhere['shop_b'] = $shop_b;
//					$count=$fck->where($awhere)->count();
//					if($count>0){
//						$this->error('该区域 已存在 县/区级代理！');
//						exit;
//					}
//					unset($awhere,$count);
//				}
//			}

			if($fck_rs['is_agent'] == 0){
			$nowdate = time();
			$result = $fck -> query("update __TABLE__ set verify='".$content."',is_agent=1,shoplx=".$shoplx.",shop_a='".$shop_a."',shop_b='".$shop_b."',idt=$nowdate where id=".$id);
			}

			$bUrl = __URL__ .'/agents';
			$this->_box(1,'申请成功！',$bUrl,2);

		}else{
			$this->error('非法操作');
			exit;
		}
	}


	public function sq_zy($Urlsz=0){
		//======================================申请专营店
		if ($_SESSION['Urlszpass'] == 'Mysssqzy'){
			$fee_rs = M ('fee') -> find();

			$fck = M ('fck');
			$where = array();
			//查询条件
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field ='*';
			$fck_rs = $fck ->where($where)->field($field)->find();

			if ($fck_rs){
				//会员级别
				switch($fck_rs['is_zy']){
					case 0:
						$agent_status = '未申请专营店!';
						break;
					case 1:
						$agent_status = '申请正在审核中!';
						break;
					case 2:
						$agent_status = '专营店已开通!';
						break;
				}

				$this->assign ( 'fee_s6',$fee_rs['i1']);
				$this->assign ( 'agent_level',0);
				$this->assign ( 'agent_status',$agent_status);
				$this->assign ( 'fck_rs', $fck_rs);
				$this->display ();
			}else{
				$this->error ('操作失败!');
				exit;
			}
		}else{
			$this->error ('错误!');
			exit;
		}
	}


	public function sq_zyAC(){
		//================================专营店处理
		$content  = $_POST['content'];
		$agentMax = $_POST['agentMax'];
		$shoplx  = (int)$_POST['shoplx'];
		$shop_a  = $_POST['shop_a'];
		$shop_b  = $_POST['shop_b'];
		$fck = M ('fck');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$where = array();
		$where['id'] = $id;

		$fck_rs = $fck->where($where)->field('id,is_zy,is_pay,user_id,user_name,agent_max,is_agent')->find();

		if($fck_rs){
			if($fck_rs['is_pay']  == 0){
				$this->error ('临时会员不能申请!');
				exit;
			}
			if($fck_rs['is_zy']  == 1){
				$this->error('上次申请还没通过审核!');
				exit;
			}
			if($fck_rs['is_zy']  == 2){
				$this->error('您已经是专营店，无需再次申请!');
				exit;
			}
			if($fck_rs['is_zy'] == 0){
				$nowdate = time();
				$result = $fck -> query("update __TABLE__ set is_zy=1,zyi_date=$nowdate where id=".$id);
			}

			$bUrl = __URL__ .'/sq_zy';
			$this->_box(1,'申请专营店成功！',$bUrl,2);

		}else{
			$this->error('非法操作');
			exit;
		}
	}

	public function sq_jb($Urlsz=0){
		//======================================申请金币中心
		if ($_SESSION['Urlszpass'] == 'MyssXiGuaJb'){
			$fee_rs = M ('fee') -> find();

			$fck = M ('fck');
			$where = array();
			//查询条件
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field ='user_id,user_name,agent_max,agent_use,is_agent,agent_cash,u_level,nickname,shoplx,idt,adt,is_boss,is_pay,is_jb,jb_idate,jb_sdate,sq_jb';
			$fck_rs = $fck ->where($where)->field($field)->find();

			if ($fck_rs){
				//会员级别
				if ($fck_rs['sq_jb']==0){
					switch($fck_rs['is_jb']){
						case 0:
							$agent_status = '未申请金币中心!';
							break;
						case 1:
							$agent_status = '金币中心已开通!';
							break;
					}
				}else{
					$agent_status = '申请正在审核中!';
				}

				$plan=M('plan');
				$crs=$plan->where('id=4')->find();
				if ($crs==false){
					$planc='暂无信息';
				}else{
					$planc=$crs['content'];
				}

				$this->assign ( 'planc',$planc);
				$this->assign ( 'fee_s6',$fee_rs['i1']);
				$this->assign ( 'agent_level',0);
				$this->assign ( 'agent_status',$agent_status);
				$this->assign ( 'fck_rs', $fck_rs);
				$this->display ('sq_jb');
			}else{
				$this->error ('操作失败!');
				exit;
			}
		}else{
			$this->error ('错误!');
			exit;
		}
	}

	public function sq_jbAC(){
		//================================申请会员中心中转函数
		$content  = $_POST['content'];
		$agentMax = $_POST['agentMax'];
		$fck = M ('fck');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$where = array();
		$where['id'] = $id;

		$fck_rs = $fck->where($where)->field('id,is_agent,is_pay,user_id,user_name,agent_max,is_agent,sq_jb,is_jb')->find();

		if($fck_rs){
			if($fck_rs['is_pay']  == 0){
				$this->error ('临时会员不能申请!');
				exit;
			}
			if($fck_rs['sq_jb']  == 1){
				$this->error('上次申请还没通过审核!');
				exit;
			}
			if(empty($agentMax)){
				$this->error ('请输入申购金额!');
				exit;
			}
			if(empty($content)){
				$this->error ('请输入备注!');
				exit;
			}
			$is_jb=$fck_rs['is_jb'];
			if ($is_jb==0){
				if ($agentMax<10000){
					$this->error ('首次申购金币最少为1万！');
					exit;
				}
			}else{
				if ($agentMax<3000){
					$this->error ('每次申购金币最低为3千！');
					exit;
				}
			}

			$nowdate = time();
			$result = $fck -> query("update __TABLE__ set verify='".$content."',sq_jb=1,jb_sdate=$nowdate,agent_max={$agentMax} where id=".$id);

			$bUrl = __URL__ .'/sq_jb';
			$this->_box(1,'申请成功！',$bUrl,2);

		}else{
			$this->error('非法操作');
			exit;
		}
	}

	//===========================================处理申请代理中心
	private function _agentsConfirm($shopLevel=0,$content=null){
		if ($_SESSION['Urlszpass'] == 'MyssXiGua'){
			$fck = M ('fck');
			$where = array();
			//查询条件
			$id = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $id;
			$field ='id,user_id,user_name,agent_max,is_agent,lssq';
			$rs = $fck ->where($where)->field($field)->find();

			if (!$rs){
				$bUrl = __URL__ .'/agents';
				$this->_box(0,'申请！',$bUrl,2);
				exit;
			}

//			if($rs['f2'] <= 2){
//				$bUrl = __URL__ .'/agents';
//				$this->_box(0,'只有加盟达到3单或3单以上才可以申请！',$bUrl,2);
//				exit;
//			}

			$nowdate = time();

			//$result = $fck -> query("update __TABLE__ set is_agent=1,idt=$nowdate,agent_no=$AgentMax,shoplx=$shopLevel,verify='$content',is_del=0 where id=".$id);

			$fee_rs = M ('fee') -> find();
			$result = $fck -> query("update __TABLE__ set is_agent=1,idt=$nowdate,verify='$content',is_del=0,f0=". $fee_rs['s6'] ." where id=".$id);
			                                            //待开通    //申请时间  //申请说明                      //申请金额

			$bUrl = __URL__ .'/agents';
			$this->_box(1,'申请！',$bUrl,2);
		}else{
			$bUrl = __URL__ .'/agents';
			$this->_box(0,'申请！',$bUrl,2);
			exit;
		}
	}


	//===================================================货币提现
	public function frontCurrency($Urlsz=0){
		if ($_SESSION['Urlszpass'] == 'MyssPaoYingTao'){
			$tiqu = M('tiqu');
			$fck = M('fck');
			$map['uid'] = $_SESSION[C('USER_AUTH_KEY')];

			$field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $tiqu->where($map)->count();//总页数
    	    $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $Page = new ZQPage($count,$listrows,1);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $tiqu->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$where = array();
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $ID;
			$field = 'user_id,agent_use,agent_cash,bank_name,bank_card,user_name,zsq';
			$rs = $fck ->where($where)->field($field)->find();

			$fee_rs = M ('fee') -> find();
			$this -> assign('menber',$fee_rs['s8']);
			$this -> assign('minn',$fee_rs['s16']);
			$this->assign('type',$ID);
			$this->assign('rs',$rs);
			unset($tiqu,$fck,$where,$ID,$field,$rs);
			$this->display ('frontCurrency');
			return;
		}else{
			$this->error ('错误!');
			exit;
		}
	}

	//=================================================提现处理
	public function frontCurrencyConfirm(){
		if ($_SESSION['Urlszpass'] == 'MyssPaoYingTao'){  //提现权限session认证
			$ePoints = (int) trim($_POST['ePoints']);
			$fck = M ('fck');
			$history = M ('history');
//			if (!$fck->autoCheckToken($_POST)){
//                $this->error('页面过期，请刷新页面！');
//                exit;
//            }
//			$weekday=date('w',time());
//			if ($weekday!=1){
//				$this->error('每周星期一才提供提现操作!');
//				exit;
//			}
//			dump($weekday);exit;


			$day_now = date("d");
//			if($day_now!=5&&$day_now!=15&&$day_now!=25){
//				$this->error('今日不能提现!');
//				exit;
//			}
//			if($day_now!=5&&$day_now!=15&&$day_now!=25){
//				$this->error('今日不能提现!');
//				exit;
//			}

			if (empty($ePoints) || !is_numeric($ePoints)){
				$this->error('金额不能为空!');
				exit;
			}
			if (strlen($ePoints) > 12){
				$this->error ('金额太大!');
				exit;
			}
			if ($ePoints <= 0){
				$this->error ('金额输入不正确!');
				exit;
			}
//			$newWeek = date(w);
//			if($newWeek != 1 ){
//				$this->error('只能星期一才能提现');
//				exit;
//			}

			$where = array();
			$ID = $_SESSION[C('USER_AUTH_KEY')];

			if($ID == 1){
				$inUserID =  $_POST['UserID'];           //要提现的会员帐号 100000登录时可以帮其它会员申请提现
			}else{
				$inUserID =  $_SESSION['loginUseracc'];  //登录的会员帐号 user_id
			}
			$tiqu = M ('tiqu');                      //转帐表
			//查询条件
			$where['user_id'] = $inUserID;
			$field ='id,user_id,agent_use,bank_name,bank_card,user_name,bank_address,user_tel,is_agent';
			$fck_rs = $fck ->where($where)->field($field)->find();
			if (!$fck_rs){
				$this->error('没有该会员!');
				exit;
			}
//			$is_agent = $fck_rs['is_agent'];
//			if($fck_rs['id']==1){
//				$is_agent = 0;
//			}
//			if($is_agent==2){
//				$this->error('报单中心不能申请提现！');
//				exit;
//			}

			$AgentUse = $fck_rs['agent_use'];
			if ($AgentUse < $ePoints){
				$this->error('金额不足!');
				exit;
			}

			$s_nowd = strtotime(date("Y-m-d"));
			$e_nowd = $s_nowd+3600*24;

//			$where2 = array();
//			$where2['uid'] = $fck_rs['id'];   //申请提现会员ID
//			$where2['rdt'] = array(array('egt',$s_nowd),array('lt',$e_nowd));
//			$field1 = 'id';
//			$vo5 = $tiqu ->where($where2)->count();
//			if ($vo5>0){
//				$this->error('每天限制提现一次!');
//				exit;
//			}

			$where1 = array();
			$where1['uid'] = $fck_rs['id'];   //申请提现会员ID
			$where1['is_pay'] = 0;            //申请提现是否通过
			$field1 = 'id';
			$vo3 = $tiqu ->where($where1)->field($field1)->find();
			if ($vo3){
				$this->error('上次提现还没通过审核!');
				exit;
			}

			$fee_rs = M ('fee') -> find();

			$s16 = $fee_rs['s16'];
			$ks_m = $fee_rs['s8'];

			$hB = $s16;//最低提现额
			$bs = 100;//倍数
			if ($ePoints < $hB){
				$this->error ('提现金额最低额度为 '.$hB.' ');
				exit;
			}
//			if($ks_m>$s16){
//				$this->error ('提现失败，提现手续费大于最低提现额度，请联系管理员！');
//				exit;
//			}

			if ($ePoints % $bs){
				$this->error ('提现金额必须为 '.$bs.' 的倍数!');
				exit;
			}


			$bank_name = $fck_rs['bank_name'];  //开户银行
			$bank_card = $fck_rs['bank_card'];  //银行卡号
			$user_name = $fck_rs['user_name'];   //开户姓名
			$bank_address = $fck_rs['bank_address'];   //开户姓名
			$user_tel = $fck_rs['user_tel'];   //开户姓名
//			$qq        = $fck_rs['qq'];   //财富通QQ
//			if(empty($user_name) or empty($bank_card) or empty($bank_name)){
//				$this->error ('请输入完整的开户信息!');
//				exit;
//			}



			$ePoints_two = $ePoints - ($ePoints * $fee_rs['s8'] / 100);  //提现扣税
//			$ePoints_two = $ePoints - ($ks_m);  //提现扣税

			$nowdate = strtotime(date('c'));
			//开始事务处理
			$tiqu->startTrans();

//			//插入历史表
//			$data = array();
//			$data['uid']           = $fck_rs['id'];//提现会员ID
//			$data['user_id']       = $inUserID;
////			$data['action_type']   = '货币提现';    //
//			$data['action_type']   = 18;    //
//			$data['pdt']           = $nowdate;     //提现时间
//			$data['epoints']       = $ePoints;     //进出金额
//			$data['allp']          = $ePoints_two;
//			$data['bz']            = '';    	   //备注
//			$data['type']          = 2;   		   //1 转帐  2 提现
//			$history->create();
//			$rs2=$history->add($data);
//			unset($data);

			//插入提现表
			$data                 = array();
			$data['uid']          = $fck_rs['id'];
			$data['user_id']      = $inUserID;
			$data['rdt']          = $nowdate;
			$data['money']        = $ePoints;
			$data['money_two']    = $ePoints_two;
			$data['epoint']       = $ePoints;
			$data['is_pay']       = 0;
			$data['bank_name']    = $bank_name;  //银行名称
			$data['bank_card']    = $bank_card;  //银行地址
			$data['user_name']    = $user_name;  //开户名称
			$data['bank_address'] = $bank_address;
			$data['user_tel'] = $user_tel;
			//$data['x1']           = $qq;
			$rs2 = $tiqu->add($data);
			unset($data,$vo3,$where1);
			if ($rs2){
				//提交事务
				$fck->execute("UPDATE __TABLE__ SET agent_use=agent_use-{$ePoints} WHERE id={$fck_rs['id']}");
				$tiqu->commit();
				$bUrl = __URL__.'/frontCurrency';
				$this->_box(1,'货币提现成功！',$bUrl,1);
				exit;
			}else{
				//事务回滚：
				$tiqu->rollback();
				$this->error('货币提现失败！');
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}

	//=============撤销提现
	public function revocAtionMoney(){
	    if ($_SESSION['Urlszpass'] == 'MyssPaoYingTao'){  //提现权限session认证
			$tiqu = M ('tiqu');
			$uid = $_SESSION[C('USER_AUTH_KEY')];
			$id = (int) $_GET['id'];
	    	$where = array();
	    	$where['id']  = $id;
	        $where['uid'] = $uid;   //申请提现会员ID
	        $where['is_pay'] = 0;            //申请提现是否通过
	        $field = 'id,money,uid';
	        $trs = $tiqu ->where($where)->field($field)->find();
	        if ($trs){
	            $fck = M ('fck');
	            $fck->execute("UPDATE __TABLE__ SET agent_use=agent_use+{$trs['money']} WHERE id={$trs['uid']}");
	            $tiqu->where($where)->delete();
	            $bUrl = __URL__.'/frontCurrency';
                $this->_box(1,'撤销提现！',$bUrl,1);
                exit;
	        }else{
	        	$this->error('没有该记录!');
                exit;
	        }
	    }else{
            $this->error('错误!');
            exit;
        }
	}

	public function financeInfo(){
		$fck = M('fck');
		$id = (int) $_GET['id'];
		$rs = $fck -> find($id);
		if(!$rs){
			$id = $_SESSION[C('USER_AUTH_KEY')];
			$rs = $fck -> find($id);
		}

		$bid = $_SESSION[C('USER_AUTH_KEY')];

		$k = 0;
		if($id != $bid){
			$arr = explode(',',$rs['p_path']);
			foreach($arr as $voo){
				if($voo == $bid){
					$k = 1;
					break;
				}
			}
			if($k == 0){
				$this->error ('错误!');
				exit;
			}
		}

		$this -> assign('rs',$rs);
		$this->display('financeInfo');
	}


	public function finance($Urlsz=0){
		//===========================================财务明细
	//	if ($_SESSION['Urlszpass'] == 'MyssShiLiu'){
			$history = M('history');

			$id = $_SESSION[C('USER_AUTH_KEY')];
			$where = array();
			$where['uid'] = $id;
			$where['type'] = 1;

			if(!empty($_REQUEST['PDT'])){  //日期查询
				$time1 = strtotime($_REQUEST['PDT']);                // 这天 00:00:00
				$time2 = strtotime($_REQUEST['PDT']) + 3600*24 -1;   // 这天 23:59:59
				$where['pdt'] = array(array('egt',$time1),array('elt',$time2));
			}

            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $history->where($where)->count();//总页数
            $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'PDT=' . $_REQUEST['PDT'];//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $history->where($where)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$this->display ('finance');
			return;
//		}else{
//			$this->error ('错误!');
//			exit;
//		}
	}

	public function financeShow(){
		//奖金明细
		//if ($_SESSION['Urlszpass'] == 'MyssShiLiu'){
			$history = M('history');
			$fee = M ('fee');
			$fee_rs = $fee->field('s13')->find();
			$date = $fee_rs['s13'];
			$myid = $_SESSION[C('USER_AUTH_KEY')];
			$ckid = (int)$_REQUEST['cid'];
			if($myid==1){
				if(empty($ckid)){
					$UID = $myid;
				}else{
					$UID = $ckid;
				}
			}else{
				$UID = $myid;
			}

			$RDT = (int) $_REQUEST['RDT'];
			$PDT = (int)$_REQUEST['PDT'];
			$cPDT = $PDT + 24 * 3600 - 1;
			$lastdate = mktime(0, 0, 0, date("m"), date("d")-$date,   date("Y"));
			//$map['pdt'] = array(array('egt',$PDT),array('elt',$cPDT));
			//$map['uid'] = $UID;
			//$map['allp'] = 0;

			$map = "pdt >={$RDT} and pdt <={$PDT} and uid={$UID} and action_type+0>0 and action_type+0<10";
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $history->where($map)->count();//总页数
            $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
            if(!empty($ckid)){
            	$page_where = 'cid='.$ckid.'&PDT=' . $PDT;//分页条件
            }else{
            	$page_where = 'PDT=' . $PDT;//分页条件
            }
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $history->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

//            $fee = M ('fee');
//            $fee_rs = $fee->field('s18')->find();
//            $s18 = explode('|',$fee_rs['s18']);
//            $this->assign('s18',$s18);

			$fee   = M ('fee');    //参数表
			$fee_rs = $fee->field('s18')->find();
			$fee_s7 = explode('|',$fee_rs['s18']);
			$this->assign('fee_s7',$fee_s7);        //输出奖项名称数组

			$this->display ('financeShow');
//			}else{
//			$this->error ('错误!');
//			exit;
//		}
	}


	//===================================================货币消费
	public function frontxiaofei($Urlsz=0){
		if ($_SESSION['Urlszpass'] == 'Myssxiaofei'){
			$tiqu = M('xiaof');
			$fck = M('fck');
			$map['uid'] = $_SESSION[C('USER_AUTH_KEY')];

			$field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $tiqu->where($map)->count();//总页数
    	    $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $Page = new ZQPage($count,$listrows,1);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $tiqu->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$where = array();
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$where['id'] = $ID;
			$field = '*';
			$rs = $fck ->where($where)->field($field)->find();
			$this->assign('type',$ID);
			$this->assign('rs',$rs);

			$fee = M('fee');
			$fee_rs = $fee->find();
			$this->assign('fee_rs',$fee_rs);

			unset($tiqu,$fck,$where,$ID,$field,$rs);
			$this->display ();
			return;
		}else{
			$this->error ('错误!');
			exit;
		}
	}

	//=================================================消费处理
	public function frontxiaofeiAC(){
		if ($_SESSION['Urlszpass'] == 'Myssxiaofei'){  //提现权限session认证
			$ePoints = trim($_POST['ePoints']);
			$dz = trim($_POST['dz']);
			$xm = trim($_POST['xm']);
			$dh = trim($_POST['dh']);
			$fck = D ('Fck');
			$history = M ('history');
			if (empty($ePoints) || !is_numeric($ePoints)){
				$this->error('金额不能为空!');
				exit;
			}
			if (strlen($ePoints) > 12){
				$this->error ('金额太大!');
				exit;
			}
			if ($ePoints <= 0){
				$this->error ('金额输入不正确!');
				exit;
			}

			$fee = M('fee');
			$fee_rs = $fee->field('s11')->find();
			$one_m = $fee_rs['s11'];
			if($ePoints<$one_m){
				$this->error ('投资额度为 '.$one_m.' 的整数倍！');
				exit;
			}
			if($ePoints%$one_m>0){
				$this->error ('投资额度为 '.$one_m.' 的整数倍！');
				exit;
			}
			if(empty($dz)||empty($xm)||empty($dh)){
				$this->error ('地址、姓名、电话均不能为空！');
				exit;
			}

			$where = array();
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$tiqu = M ('xiaof');                      //消费表
			//查询条件
			$where['id'] = $ID;
			$field ='*';
			$fck_rs = $fck ->where($where)->field($field)->find();
			if (!$fck_rs){
				$this->error('没有该会员!');
				exit;
			}

			$inUserID = $fck_rs['user_id'];
			$AgentUse = $fck_rs['agent_cf'];
			if ($AgentUse < $ePoints){
				$this->error('您的重复消费不足!');
				exit;
			}

			$ePoints_two = (int)($ePoints/$one_m);

			$nowdate = strtotime(date('c'));

			$data				= array();
			$data['uid']		= $fck_rs['id'];
			$data['user_id']	= $inUserID;
			$data['rdt']		= $nowdate;
			$data['money']		= $ePoints;
			$data['money_two']	= $ePoints_two;
			$data['epoint']	= $ePoints;
			$data['is_pay']	= 0;
			$data['bank_name']	= $dz;
			$data['user_name']	= $xm;
			$data['bank_card']	= $dh;
			$rs2 = $tiqu->add($data);
			unset($data);
			if ($rs2){

				$fck->execute("UPDATE __TABLE__ SET agent_cf=agent_cf-".$ePoints.",gp_num=gp_num+".$ePoints_two." WHERE id={$fck_rs['id']}");

				$bUrl = __URL__.'/frontxiaofei';
				$this->_box(1,'兑换成功！',$bUrl,1);
				exit;
			}else{
				$this->error('兑换失败！');
				exit;
			}
		}else{
			$this->error('错误！');
			exit;
		}
	}

	//=============撤销消费
	public function frontxiaofeiDEL(){
	    if ($_SESSION['Urlszpass'] == 'Myssxiaofei'){  //提现权限session认证
			$tiqu = M ('xiaof');
			$uid = $_SESSION[C('USER_AUTH_KEY')];
			$id = (int) $_GET['id'];
	    	$where = array();
	    	$where['id']  = $id;
	        $where['uid'] = $uid;   //申请提现会员ID
	        $where['is_pay'] = 0;            //申请提现是否通过
	        $field = 'id,money,uid';
	        $trs = $tiqu ->where($where)->field($field)->find();
	        if ($trs){
	            $fck = M ('fck');
	            $fck->execute("UPDATE __TABLE__ SET agent_cash=agent_cash+{$trs['money']} WHERE id={$trs['uid']}");
	            $tiqu->where($where)->delete();
	            $bUrl = __URL__.'/frontxiaofei';
                $this->_box(1,'撤销消费成功！',$bUrl,1);
                exit;
	        }else{
	        	$this->error('没有该记录!');
                exit;
	        }
	    }else{
            $this->error('错误!');
            exit;
        }
	}


		//==========================货币充值
	public function currencyRecharge(){
		if ($_SESSION['Urlszpass'] == 'MyssMangGuo'){
			$chongzhi = M('chongzhi');
			$fck = M('fck');
			$map['uid'] = $_SESSION[C('USER_AUTH_KEY')];

			$field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $chongzhi->where($map)->count();//总页数
            $listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $Page = new ZQPage($count,$listrows,1);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $chongzhi->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

			$where = array();
			$fwhere = array();
			$where['id'] = 1;
			$fwhere['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$field = '*';
			$rs = $fck ->where($where)->field($field)->find();
			$frs = $fck ->where($fwhere)->field($field)->find();
			$this->assign('rs',$rs);
			$this->assign('frs',$frs);

			$nowdate[] =array();
			$nowdate[0] = date('Y');
			$nowdate[1] =date('m');
			$nowdate[2] =date('d');

			$this->assign('nowdate',$nowdate);

			$fee_rs = M ('fee') -> find();
			$this -> assign('s8',$fee_rs['s8']);
			$this -> assign('s9',$fee_rs['s9']);
			$this -> assign('s17',$fee_rs['s17']);
			$this->display ('currencyRecharge');
			return;
		}else{
			$this->error ('错误!');
			exit;
		}
	}
	public function currencyRechargeAC(){
		if ($_SESSION['Urlszpass'] == 'MyssMangGuo'){
			$fck = M ('fck');
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$rs = $fck -> field('is_pay,user_id') -> find($ID);
			if($rs['is_pay'] == 0){
				$this->error('临时会员不能充值！');
                exit;
			}
			$inUserID=$rs['user_id'];

			$ePoints = trim($_POST['ePoints']);
			$stype = (int) trim($_POST['stype']);
			$chongzhi = M('chongzhi');
			if (!$chongzhi->autoCheckToken($_POST)){
				$this->error('页面过期，请刷新页面！');
                exit;
			}
			if (empty($ePoints) || !is_numeric($ePoints)){
				$this->error('金额不能为空!');
				exit;
			}
			if (strlen($ePoints)>9){
				$this->error ('金额太大!');
				exit;
			}
			if ($ePoints<=0){
				$this->error ('金额格式不对!');
				exit;
			}
			if($stype>1){
				$stype=1;
			}

			$id =  $_SESSION[C('USER_AUTH_KEY')];
			$where = array();
			$where['uid'] = $id;
			$where['is_pay'] = 0;
			$field1 = 'id';
			$vo3 = $chongzhi ->where($where)->field($field1)->find();
			if ($vo3){
				$this->error('上次充值还没通过审核!');
				exit;
			}
			//开始事务处理
			$chongzhi->startTrans();

			//充值表
			$_money = trim($_POST['_money']);  //已汇款数额
			$_num = trim($_POST['_num']);  // 汇款到账号
			$_year = trim($_POST['_year']); // 年
			$_month = trim($_POST['_month']);  //月
			$_date = trim($_POST['_date']);  //日
			$_hour = trim($_POST['_hour']);  //小时


			if (empty($_money) || !is_numeric($_money)){
				$this->error('请输入数字或金额不能为空!');
				exit;
			}
			if (empty($_num) || !is_numeric($_num)){
				$this->error('请输入数字或账号不能为空!');
				exit;
			}
			if (empty($_year) || !is_numeric($_year)){
				$this->error('请输入数字或年不能为空!');
				exit;
			}
			if (empty($_month) || !is_numeric($_month)){
				$this->error('请输入数字或月不能为空!');
				exit;
			}
			if (empty($_date) || !is_numeric($_date)){
				$this->error('请输入数字或日不能为空!');
				exit;
			}
			if (empty($_hour) || !is_numeric($_hour)){
				$this->error('请输入数字或小时不能为空!');
				exit;
			}


			//$nowdate = strtotime(date('c'));
			$nowdate = strtotime(date($_year.'-'. $_month.'-'.$_date.' '. $_hour.':00:00'));

			$data = array();
			$data['uid']     = $id;
			$data['user_id'] = $inUserID;
			$data['huikuan'] = $_money;
			$data['zhuanghao'] = $_num;
			$data['rdt']     = $nowdate;
			$data['epoint']  = $ePoints;
			$data['is_pay']  = 0;
			$data['stype']  = $stype;

			$rs2 = $chongzhi->add($data);
			unset($data,$id);
			if ($rs2){
				//提交事务
				$chongzhi->commit();
				$bUrl = __URL__.'/currencyRecharge';
				$this->_box(1,'电子币充值成功，请等待后台审核！',$bUrl,1);
				exit;
			}else{
				//事务回滚：
				$chongzhi->rollback();
				$this->error('货币充值失败');
				exit;
			}
		}else{
			$this->error ('错误!');
			exit;
		}
	}

	public function currencyRechargeAC2(){/////////////////////////////////报单中心申请金额
		if ($_SESSION['Urlszpass'] == 'MyssXiGua'){
			$fck = M ('fck');
			$ID = $_SESSION[C('USER_AUTH_KEY')];
			$rs = $fck -> field('is_pay') -> find($ID);
			if($rs['is_pay'] == 0){
				$this->error('临时会员不能充值！');
                exit;
			}


			$ePoints = 3000;
			$chongzhi = M('chongzhi');
			if (!$chongzhi->autoCheckToken($_POST)){
				$this->error('页面过期，请刷新页面！');
                exit;
			}
			if (empty($ePoints) || !is_numeric($ePoints)){
				$this->error('金额不能为空!');
				exit;
			}
			if (strlen($ePoints)>9){
				$this->error ('金额太大!');
				exit;
			}
			if ($ePoints<=0){
				$this->error ('金额格式不对!');
				exit;
			}
			$id =  $_SESSION[C('USER_AUTH_KEY')];
			$inUserID =  $_SESSION['loginUseracc'];
			$where = array();
			$where['uid'] = $id;
			$where['is_pay'] = 0;
			$field1 = 'id';
			$vo3 = $chongzhi ->where($where)->field($field1)->find();
			if ($vo3){
				$this->error('上次加单还没通过审核!');
				exit;
			}
			//开始事务处理
			$chongzhi->startTrans();
			//充值表
			$nowdate = strtotime(date('c'));
			$data = array();
			$data['uid'] = $id;
			$data['user_id'] = $inUserID;
			$data['rdt'] = $nowdate;
			$data['epoint'] = $ePoints;
			$data['is_pay'] = 0;
			$rs2 = $chongzhi->add($data);
			unset($data,$id);
			if ($rs2){
				//提交事务
				$chongzhi->commit();
				$bUrl = __URL__.'/currencyRecharge';
				$this->_box(1,'加单申请操作成功！',$bUrl,1);
				exit;
			}else{
				//事务回滚：
				$chongzhi->rollback();
				$this->error('申请失败');
				exit;
			}
		}else{
		}
	}

    public function business(){
    	//业务总统计
    	if ($_SESSION['Urlszpass'] == 'Myssbusiness'){
			$id =  $_SESSION[C('USER_AUTH_KEY')];
    		$fck = M ('fck');
			$where = array();
			$where['id'] = $id;
			$field1 = 'user_id,zjj,agent_use,agent_cash';
			$rs = $fck ->where($where)->field($field1)->find();
			if($rs){

				//=========奖金提现
				$tiqu = M ('tiqu');
				$t_where = array();
				$t_where['uid'] = $id;
				$t_where['is_pay'] = 1;
				$rs1 = $tiqu ->where($t_where)->sum('money');
				$rs2 = $tiqu ->where($t_where)->sum('money_two');
				$rs2 = $rs1-$rs2;

				//=========电子币充值
				$chongzhi = M ('chongzhi');
				$c_where = array();
				$c_where['uid'] = $id;
				$c_where['is_pay'] = 1;
				$rs3 = $chongzhi ->where($c_where)->sum('epoint');

				//=========注册划出
				$z_where = array();
				$z_where['shop_id'] = $id;
				$z_where['is_pay'] = 1;
				$rs4 = $fck ->where($z_where)->sum('cpzj');

				//=========货币借出
				$zhuanj = M ('zhuanj');
				$z_where = array();
				$z_where['out_uid'] = $id;
				$rs5 = $zhuanj ->where($z_where)->sum('epoint');

				//=========货币借入
				$z_where = array();
				$z_where['in_uid'] = $id;
				$rs6 = $zhuanj ->where($z_where)->sum('epoint');

				$Zong = array();
				$Zong[0] = $rs['user_id'];  				  //会员名
				$Zong[1] = $this->_2Mal($rs['zjj'],2);        //总奖金
				$Zong[2] = $this->_2Mal($rs1,2);      		  //总提现
				$Zong[3] = $this->_2Mal($rs2,2);     		  //提现手续
				$Zong[4] = $this->_2Mal($rs3,2);     		  //电子币充值
				$Zong[5] = $this->_2Mal($rs4,2);     		  //注册划出
				$Zong[6] = $this->_2Mal($rs5,2);     		  //货币借出
				$Zong[7] = $this->_2Mal($rs6,2);     		  //货币借入
				$Zong[8] = $this->_2Mal($rs['agent_use'],2);  //剩余奖金
				$Zong[9] = $this->_2Mal($rs['agent_cash'],2); //剩余电子币

				$history = M('history');
				$Userid=$_REQUEST['UserID'];
				$type=$_REQUEST['type'];
				if(empty($Userid)){
					$Userid=$_SESSION['loginUseracc'];
				}

				$where = "(user_id='{$Userid}' or user_did='{$Userid}')";
				if ($type > 0 ) {
					$where .= " and type=".$type;
				}
				$field = '*';
				//=====================分页开始==============================================
				import ( "@.ORG.ZQPage" );  //导入分页类
				$count = $history->where($where)->count();//总页数
				$listrows = C('ONE_PAGE_RE');//每页显示的记录数
				$page_where = 'UserID='.$Userid.'&type='.$type;//分页条件
				$Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
				//===============(总页数,每页显示记录数,css样式 0-9)
				$show = $Page->show();//分页变量
				$this->assign('page',$show);//分页变量输出到模板
				$list = $history->where($where)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();

				$this->assign('list',$list);//数据输出到模板
				//=================================================

				$this->assign('Zong',$Zong);
    			$this->display();
			}else{
				$this->error ('错误!');
				exit;
			}
    	}else{
            $this->error ('错误!');
            exit;
        }
    }


	//==================================发件箱
	public function outMessages(){
		$msg              = M('msg');
		$map['isid']       = $_SESSION[C('USER_AUTH_KEY')];
		$map['r_uid']      = $_SESSION[C('USER_AUTH_KEY')];

        $field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $msg->where($map)->count();//总页数
        $listrows = C('ONE_PAGE_RE');//每页显示的记录数
        $Page = new ZQPage($count,$listrows,1);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $list = $msg->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
        $this->assign('list',$list);//数据输出到模板
        //=================================================

		$this->display('outMessages');
	}
	//====================================收件箱
	public function inMessages(){
		$msg           = M('msg');

		$map['isid']    = $_SESSION[C('USER_AUTH_KEY')];
		$map['s_uid']   = $_SESSION[C('USER_AUTH_KEY')];

        $field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $msg->where($map)->count();//总页数
        $listrows = C('ONE_PAGE_RE');//每页显示的记录数
        $Page = new ZQPage($count,$listrows,1);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $list = $msg->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
        $this->assign('list',$list);//数据输出到模板
        //=================================================

		$this->display('inMessages');
	}
	//=============================我要留言
	public function messages(){
		$ID = $_SESSION[C('USER_AUTH_KEY')];
		$fck=M('fck');
		$mrs=$fck->where('id='.$ID)->find();
		$this->assign('mrs',$mrs);
		$this->display();
	}
	//==================================留言
	public function messagesAC(){
		$UserID   = trim($_POST['UserID']);
		$Title    = trim($_POST['Title']);
		$Msg      = trim($_POST['Msg']);
		$level      = (int)$_POST['level'];
		if($level==1){
			$UserID='100000';
		}
		$fck = M ('fck');
	    if (!$fck->autoCheckToken($_POST)){
            //$this->error('页面过期，请刷新页面！');
            //exit;
        }
		if (empty($UserID)){
			$this->error('数据错误!');
			exit;
		}
		if (strlen($Title) > 200){
			$this->error ('主题太长!');
			exit;
		}
		$this->_messagesAdd($UserID,$Title,$Msg);
	}
	private function _messagesAdd($UserID='0',$Title='',$Msg=''){
		$fck = M ('fck');
		$Users = M ('Msg');
		$where = array();
		$ID = $_SESSION[C('USER_AUTH_KEY')];
		$inUserID =  $_SESSION['loginUseracc'];

		//收件人
		$where1 = array();
		$where1['user_id'] = $UserID;
		if($UserID == '公司'){
			$where1['user_id'] = 100000;
		}

		$field = 'id,user_id';
		$vo = $fck->where($where1)->field($field)->find();
		if (!$vo){
			$bUrl = __URL__.'/messages';
			$this->_box(0,'收件人不存在！',$bUrl,1);
			exit;
		}
		if($ID>1){
			if($ID == $vo['id']){
				$bUrl = __URL__.'/messages';
				$this->_box(0,'不能给自已发邮件！',$bUrl,1);
				exit;
			}
		}

		//查询条件
		$where['id'] = $ID;
		$vo2 = $fck->where($where)->field($field)->find();
		if (!$vo2){
			$this->error('没有该记录!');
			exit;
		}
		//开始事务处理
		$Users->startTrans();
        $nowdate = strtotime(date('c'));
		//留言表
		$data = array();
		$data['isid']       = $ID;
		$data['title']      = $Title;
		$data['msg']        = $Msg;
		$data['r_uid']      = $vo2['id'];
		$data['r_user_id']  = $vo2['user_id'];
		$data['s_uid']      = $vo['id'];
		$data['s_user_id']  = $vo['user_id'];
		$data['pdt']        = $nowdate;
		$data['is_type']    = 0;
		$data['is_read']    = 0;
		$Users->create();
		$rs1 = $Users->add($data);
		unset($data);
		//
		$data = array();
		$data['isid']      = $vo['id'];
		$data['title']     = $Title;
		$data['msg']       = $Msg;
		$data['r_uid']     = $vo2['id'];
		$data['r_user_id'] = $vo2['user_id'];
		$data['s_uid']     = $vo['id'];
		$data['s_user_id'] = $vo['user_id'];
		$data['pdt']       = $nowdate;
		$data['is_type']   = 1;
		$data['is_read']   = 0;
		$Users->create();
		$rs2 = $Users->add($data);
		unset($data);
		if ($rs1 && $rs2){
			//提交事务
			$Users->commit();
			$bUrl = __URL__.'/messages';
			$this->_box(1,'留言成功！',$bUrl,1);
			exit;
		}else{
			//事务回滚：
			$Users->rollback();
			$this->error('操作失败');
			exit;
		}
	}
	//=============================================删除信件中转
	public function messagesDelAC($bUrl='outMessages'){
		//获取复选框的值
		$boxID = $_POST['tabledb'];
		$bUrl = $_GET['bUrl'];
		if (empty($boxID)){
			$this->success('请选择要删除的信件！');
			exit;
		}
		$this->_messagesDel($boxID,$bUrl);
	}
	//===================================================删除信件
	private function _messagesDel($boxID,$bUrl='outMessages'){
		$msg          = M ('msg');
		$where        = array();
		$where['id']  = array('in ',$boxID);
		$rs = $msg->where($where)->delete();
		if ($rs){
			$this->_box(1,'删除留言！',__URL__.'/'.$bUrl,1);
			exit;
		}else{
			$this->_box(0,'删除留言！',__URL__.'/'.$bUrl,1);
			exit;
		}
	}
	//========================================查看已收到的邮件
	public function messagesShow(){
		$Pid = (int) $_GET['Pid'];
		if (!is_numeric($Pid)){
			$this->error('数据错误!');
			exit;
		}
		if (strlen($Pid)>9){
			$this->error ('参数错误!');
			exit;
		}
		$ID                = $_SESSION[C('USER_AUTH_KEY')];
		$msg               = M ('msg');
		$where             = array();
		$where['isid']     = $ID;
		$where['s_uid']    = $ID;
		$where['id']       = $Pid;
		$mid               = array();
		$mid['id']         = $Pid;
		$field             = '*';
		$vo = $msg ->where($where)->field($field)->find();
		$msg->where($mid)->setField('is_read','1');//更新查看字段

		$rs = $msg -> where('id ='.$Pid) -> find();
		if($rs){
			$where = array();
			$where['title'] = $rs['title'];
			$where['pdt']   = $rs['pdt'];
			$where['id']    = array('neq',$Pid);
			$rs2 = $msg -> where($where) -> find();
			if($rs2){
				$msg->where('id='.$rs2['id'])->setField('is_read','1');//更新查看字段
			}
		}

		if ($vo){
			$this->assign('vo',$vo);
			$this->display('messagesShow');
		}else{
			$this->error('数据错误!');
			exit;
		}
	}
	//=================================查看已发邮件
	public function outMessagesShow(){
		$Pid = (int) $_GET['Pid'];
		if (!is_numeric($Pid)){
			$this->error('数据错误!');
			exit;
		}
		if (strlen($Pid)>9){
			$this->error ('参数错误!');
			exit;
		}
		$ID                = $_SESSION[C('USER_AUTH_KEY')];
		$msg               = M ('msg');
		$where             = array();
		$where['r_uid']    = $ID;
		$where['id']       = $Pid;
		$mid               = array();
		$mid['id']         = $Pid;
		$field             = '*';
		$vo = $msg ->where($where)->field($field)->find();
		//$msg->where($mid)->setField('is_read','1');//更新查看字段
		if ($vo){
			$this->assign('vo',$vo);
			$this->display('outMessagesShow');
		}else{
			$this->error('数据错误!');
			exit;
		}
	}
	//=================================回复留言中转
	public function messagesShowAC(){
		$Pid = (int) $_POST['Pid'];
		$Msg = $_POST['Msg'];
		if ($Pid == 0){
			$this->error('数据错误!');
			exit;
		}
		if (strlen($Pid) > 12){
			$this->error ('参数错误!');
			exit;
		}
		$this->_messagesShowReply($Pid,$Msg);
	}
	//=============================================回复留言
	private function _messagesShowReply($Pid=0,$Msg=''){
		$ID = $_SESSION[C('USER_AUTH_KEY')];
		$msg = M ('msg');
		$where = array();//
		$where['isid']  = $ID;
		$where['s_uid'] = $ID;
		$where['id'] = $Pid;
		$field = '*';
		$vo = $msg ->where($where)->field($field)->find();
		if (!$vo){
			$this->error ('参数错误!');
			exit;
		}
		//开始事务处理
		$msg->startTrans();
		if($vo['is_type'] == 1){
			$Title = $vo['title'];
		}else{
			$Title = '回复：'. $vo['title'];
		}
        $nowdate = strtotime(date('c'));
		//留言表
		$data = array();
		$data['isid']           = $vo['s_uid'];
		$data['title']          = $Title;
		$data['msg']            = $Msg;
		$data['r_uid']          = $vo['s_uid'];
		$data['r_user_id']      = $vo['s_user_id'];
		$data['s_uid']          = $vo['r_uid'];
		$data['s_user_id']      = $vo['r_user_id'];
		$data['pdt']            = $nowdate;
		$datas['is_type']       = 1;
		$data['is_read']        = 0;
		$msg->create();
		$rs1 = $msg->add($data);
		unset($data);
		//
		$data = array();
		$data['isid']          = $vo['r_uid'];
		$data['title']         = $Title;
		$data['msg']           = $Msg;
		$data['r_uid']         = $vo['s_uid'];
		$data['r_user_id']     = $vo['s_user_id'];
		$data['s_uid']         = $vo['r_uid'];
		$data['s_user_id']     = $vo['r_user_id'];
		$data['pdt']           = $nowdate;
		$data['is_type']       = 1;
		$data['is_read']       = 0;
		$msg->create();
		$rs2 = $msg->add($data);
		unset($data,$ID,$where,$field,$vo,$Title,$nowdate,$Msg,$Pid);
		if ($rs1 && $rs2){
			//提交事务
			$msg->commit();
			$this->success('回复成功!');
			exit;
		}else{
			//事务回滚
			$msg->rollback();
			$this->error ('回复留言!');
			exit;
		}
	}

	public function level_menber(){      //查看下级会员
		$level = 1;

		if($level <=0 or $level >=7 ){
			$this -> error ('级别错误!');
			exit;
		}

		$fck = M ('fck');
		$ID = $_SESSION[C('USER_AUTH_KEY')];  //登录会员ID(自动编号)
		$where = array();

		$where['p_path'] = array('like','%,'. $ID .',%');
		$rs = $fck -> where($where) -> field('p_path,id,is_pay') -> select();  //找出该会员的所有下级会员
		$id_str = ',';
		foreach($rs as $rss){
			$p_path = trim($rss['p_path'],',');  //去除路径两边的逗号
			$p_path = explode(',',$p_path);  //分解为数组
			rsort($p_path);  //数组按倒序排序

			$l = $level - 1;
			if($p_path[$l] == $ID){
				$id_str .=  $rss['id'] .',';
			}
		}

		$id_str = trim($id_str,',');  //去除路径两边的逗号
		//$list = $fck -> select($id_str);

		$map = array();
		$field = '*';
		$map['id'] = array('in',$id_str);
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $fck->where($map)->count();//总页数
        $listrows = 10 ;//每页显示的记录数
        $Page = new ZQPage($count,$listrows,1);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $list = $fck->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
        $this->assign('list',$list);//数据输出到模板
        //=================================================

		$rs = $fck -> field('user_id') -> find($ID);
		$this -> assign('rs',$rs);

		$this -> display('level_menber');
	}

	//会员升级
	public function user_level(){
		if ($_SESSION['Urlszpass'] == 'Mysskai' ){
			$fck = M("fck");
			$fee = M ('fee');
			$ul = M('ulevel');

			$id = $_SESSION[C('USER_AUTH_KEY')];
			$fee_rs = $fee->find();
			$s10 = explode('|',$fee_rs['s10']);
			$s9 = explode('|',$fee_rs['s9']);//金额

			$wehre = array();
			$f_where = array();
			$f_rs = $fck -> field("p_path,u_level,p_level")->find($id);

			$this->assign('f',$f_rs);//会员级别
			$this->assign('s10',$s10);//输级会员级别
			$this->assign('s9',$s9);


			$map['uid'] = $id;
			//查询条件
			$where['id'] = $id;
			$field ='user_id,user_name,agent_max,agent_use,is_agent,agent_cash,u_level,nickname,cpzj,re_nums';
			$fck_rs = $fck ->where($where)->field($field)->find();
			$field  = '*';
			//=====================分页开始==============================================
			import ( "@.ORG.ZQPage" );  //导入分页类
			$count = $ul->where($map)->count();//总页数
			$listrows = C('ONE_PAGE_RE');//每页显示的记录数
			$Page = new ZQPage($count,$listrows,1);
			//===============(总页数,每页显示记录数,css样式 0-9)
			$show = $Page->show();//分页变量
			$this->assign('page',$show);//分页变量输出到模板
			$list = $ul->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
			$HYJJ = '';
			$this->_levelConfirm($HYJJ,1);
			$this->assign('HYJJ',$HYJJ);//会员级别
			$this->assign('list',$list);


			$this->display();
		}else{
			$this->error('二级密码错误!');
		}
	}
	//处理申请提交
	public function user_levelAC(){
		if ($_SESSION['Urlszpass'] == 'Mysskai' ){
			$fee_rs = M ('fee') -> find();
			$fck = M('Fck');
			$ulevel= M('ulevel');
			$id=$_SESSION[C('USER_AUTH_KEY')];
			$ulev=$_REQUEST['uLevel'];  //会员申请的级别
			//$zf_type = $_REQUEST['zftype'];
			$fee_rs = M('fee')->find();
			$s10 = explode('|',$fee_rs['s10']);
			$s2 = explode('|',$fee_rs['s2']);
			switch($ulev){
				case $s10[0];
					$level = 1;
					$ds = $s2[0];
					break;
				case $s10[1];
					$level = 2;
					$ds = $s2[1];
					break;
				case $s10[2];
					$level = 3;
					$ds = $s2[2];
					break;
				case $s10[3];
					$level = 4;
					$ds = $s2[3];
					break;
				case $s10[4];
					$level = 5;
					$ds = $s2[4];
					break;
				case $s10[5];
					$level = 6;
					$ds = $s2[5];
					break;
				case $s10[6];
					$level = 7;
					$ds = $s2[6];
					break;
			}
			if(empty($ulev)){
				$this->error('请选择级别！');
				exit;
			}
			$where = array();
			$where['id'] = $id;
			$field ='*';
			$fck_rs = $fck ->where($where)->field($field)->find();
			if($fck_rs){
				if($fck_rs['is_pay'] == 0){
					$this->error('临时会员不能升级!');
					exit;
				}

				$u_nn = $ulevel->where('uid='.$id.' and is_pay=0')->count();
				if($u_nn>0){
					$this->error('您之前还有升级申请尚未确认，请不要重复申请！');
					exit;
				}


				$mlevel=$fck_rs['u_level'];
				$re_id=$fck_rs['re_id'];
				if($level<=$mlevel){
					$this->error('升级级别必须大于当前级别!');
					exit;
				}else{
					$s9= explode('|',$fee_rs['s9']);
					$money = $s9[$level-1] - $s9[$mlevel-1];
					$ds = $ds - $fck_rs['f4'];
					$nowdate = strtotime(date('c'));
					$data               = array();
					$data['is_pay']     = 0;
					$data['uid']        = $id;
					$data['u_level']   = $mlevel;
					$data['up_level']  = $level;
					$data['money']      = $money;
					$data['create_time']  = $nowdate;
					$data['danshu']     = $ds;
					$data['user_id']    = $fck_rs['user_id'];

					$result = $ulevel->add($data);
//					unset($ulevel,$data);
					if($result){
						$bUrl = __URL__.'/user_level';
						$this->_box(1,'升级申请成功！',$bUrl,1);
					}else{
						dump($ulevel);
						$bUrl = __URL__.'/user_level';
						$this->_box(0,'升级申请失败，请重新再试！',$bUrl,1);
					}
				}
			}else{
				$this->error ('操作失败!');
				exit;
			}
		}else{
			$this->error('错误');
		}

	}

public function gTotle(){
		$pora = M('product');

		$gid = $_GET['GID'];
		$bnum = (int)$_GET['bnum'];
		$p_rs = $pora->where('id ='.$gid)->find();

		if($bnum<1){
			$num = 1;
		}else{
			$num = $bnum;
		}
		$shopping_id ='';
		if(empty($_SESSION["shopping"])){
			$_SESSION["shopping"] = $gid.",".$num;
		}else{
			$arr = $_SESSION["shopping"];
			$rs = explode('|',$arr);
			$tong = 0;
			foreach ($rs as $key=>$vo){
				$str = explode(',',$vo);
				if($str[0] == $gid){
					$str[1] = $str[1]+$num;
					if(empty($shopping_id)){
						$shopping_id = $str[0].",".$str[1];
					}else{
						$shopping_id .= '|'.$str[0].",".$str[1];
					}
					$tong = 1;
				}else{
					if(empty($shopping_id)){
						$shopping_id = $vo;
					}else{
						$shopping_id .= '|'.$vo;
					}
				}
			}
			if($tong==0){
				if(empty($shopping_id)){
					$shopping_id = $gid.",".$num;
				}else{
					$shopping_id .= '|'.$gid.",".$num;
				}
			}
			$_SESSION["shopping"] = $shopping_id;
		}
	}


}
?>