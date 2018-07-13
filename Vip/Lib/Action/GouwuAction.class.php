<?php
class GouwuAction extends CommonAction{

    function _initialize() {
		$this->_inject_check(0); //调用过滤函数
		$this->_checkUser();
		header("Content-Type:text/html; charset=utf-8");
	}

    //二级验证
    public function Cody(){
        //$this->_checkUser();
        $UrlID = (int) $_GET['c_id'];
        if (empty($UrlID)){
            $this->error('二级密码错误!');
            exit;
        }
//        if ($_SESSION['urlcody_admin'] == 'myss'){
//            $bUrl = __URL__.'/codys/Urlsz/'. $UrlID;
//            $this->_boxx($bUrl);
//        }else{
            $cody   =  M ('cody');
            $list   =  $cody->where("c_id=$UrlID")->getField('c_id');
            if (!empty($list)){
                $this->assign('vo',$list);
                $this->display('../Public/cody');
                exit;
            }else{
                $this->error('二级密码错误!');
                exit;
//            }
        }
    }
	//二级验证后调转页面
	public function Codys() {
		$this->_checkUser();
		$Urlsz = $_POST['Urlsz'];
		$pass = $_POST['oldpassword'];
		$fck = M('fck');
		if (!$fck->autoCheckToken($_POST)) {
			$this->error('页面过期请刷新页面!');
			exit ();
		}
		if (empty ($pass)) {
			$this->error('二级密码错误!');
			exit ();
		}
		$where = array ();
		$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$where['passopen'] = md5($pass);
		$list = $fck->where($where)->field('id')->find();
		if ($list == false) {
			$this->error('二级密码错误!');
			exit ();
		}
		switch ($Urlsz) {
			case 1;
				$_SESSION['UrlszUserpass'] = 'cpluru';//产品录入
				$bUrl = __URL__ . '/cp';
				$this->_boxx($bUrl);
				break;
			case 2;
				$_SESSION['UrlszUserpass'] = 'gouwuguanli';//购物管理
				$bUrl = __URL__ . '/BuycpInfo1';
				$this->_boxx($bUrl);
				break;
			case 3;
				$_SESSION['UrlszUserpass'] = 'ACmilan';
				$bUrl = __URL__ . '/Buycp';
				$this->_boxx($bUrl);
				break;
			case 4;
				$_SESSION['UrlszUserpass'] = 'manlian';//求购股票
				$bUrl = __URL__ . '/BuycpInfo';
				$this->_boxx($bUrl);
				break;
			case 5;
				$_SESSION['UrlszUserpass'] = 'basailuona';//产品录入
				$bUrl = __URL__ . '/guqshez';
				$this->_boxx($bUrl);
				break;
			case 6;
				$_SESSION['UrlszUserpass'] = 'jihuomanGL';//购买记录
				$bUrl = __URL__ . '/gmjl';
				$this->_boxx($bUrl);
				break;
			case 7;
				$_SESSION['UrlszUserpass'] = 'huangma';//股票拆分
				$bUrl = __URL__ . '/guqgoumai';
				$this->_boxx($bUrl);
				break;
			case 8;
				$_SESSION['UrlszUserpass'] = 'xinxi';//购物管理
				$bUrl = __URL__ . '/guqshezSave';
				$this->_boxx($bUrl);
				break;
			case 9;
				$_SESSION['UrlszUserpass'] = 'luru';//产品录入
				$bUrl = __URL__ . '/cp';
				$this->_boxx($bUrl);
				break;
			case 10;
				$_SESSION['UrlszUserpass'] = 'cpgoumai';//购买查询
				$bUrl = __URL__ . '/Buycp';
				$this->_boxx($bUrl);
				break;
			case 11;
				$_SESSION['UrlszUserpass'] = 'gouwugl';//购物管理
				$bUrl = __URL__ . '/BuycpInfo';
				$this->_boxx($bUrl);
				break;
			default;
				$this->error('二级密码错误!');
				break;
		}
	}

	public function cp(){
		if ($_SESSION['UrlszUserpass'] == 'cpluru'){
			$product = M ('product');
			$map = array();
			$map['id'] = array('gt',0);
            $field  = '*';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $product->where($map)->count();//总页数
            $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
            $page_where = '';//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $list = $product->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================

            $PID = (int) $_GET['PID'];
            echo $PID;
            if (!empty($PID)){
            	$prs = $product->find($PID);
            	$this->assign('prs',$prs);
            }
			$title = '产品管理';
			$this->assign('title',$title);
			$this->display('cp');
		}else{
			$this->error('错误!');
		}
	}

	public function cpShow(){
		//产品备注说明
		if ($_SESSION['UrlszUserpass'] == 'cpluru'){
			$product = M ('product');
			$PID = (int) $_GET['PID'];
			if (empty($PID)){
				$this->error('错误!');
				exit;
			}
			$where = array();
			$where['id'] = $PID;
			$prs = $product->where($where)->field('content')->find();
			if ($prs){
				$title = '产品备注说明';
				$this->assign('title',$title);
				$this->assign('content',$prs['content']);
				$this->display('cpShow');
			}else{
				$this->error('没有改产品记录!');
			}
		}else{
			$this->error('错误!');
		}
	}


	public function adminProductAdd(){
		//添加产品
		if ($_SESSION['UrlszUserpass'] == 'cpluru'){
			$product = M ('product');
//			if (!$product->autoCheckToken($_POST)){
//	            $this->error('页面过期，请刷新页面！');
//	            exit;
//	        }
	        $PID     = $_POST['PID'];
	        $name    = $_POST['Pname'];
	        $ePoints = $_POST['ePoints'];
	        $content = $_POST['content'];
	        if (!empty($name) && !empty($ePoints)){
	        	$data = array();
	        	$data['name']     = $name;
	        	$data['money']    = $ePoints;
	        	$data['content']  = $content;
	        	$data['create_time']      = strtotime(date('c'));
	        	if (!empty($PID)){
	        		$data['id'] = $PID;
	        		$result = $product->data($data)->save();
	        	}else{
	        	  $result = $product->data($data)->add();
	        	}
//	        	dump($data);
	        	dump($result);
//	        	unset($product,$PID,$name,$ePoints,$content,$data);
	        	if ($result){
	        		$bUrl = __URL__.'/cp';
                    $this->_box(1,'保存产品！',$bUrl,1);
	        	}else{
	        		$this->error('保存产品失败!');
	        	}
	        }else{
	        	$this->error('产品名称和产品价钱不能为空!');
	        }
		}else{
			$this->error('错误!');
		}
	}

	public function adminProductAC(){
        //处理提交按钮
        $action = $_POST['action'];
        //获取复选框的值
        $XGid = $_POST['tabledb'];
        $product = M ('product');
        if (!$product->autoCheckToken($_POST)){
            $this->error('页面过期，请刷新页面！');
            exit;
        }
        unset($product);
        if (!isset($XGid) || empty($XGid)){
            $bUrl = __URL__.'/cp';
            $this->_box(0,'请选择！',$bUrl,1);
            exit;
        }
        switch ($action){
            case '删除产品';
                $this->_adminProductDel($XGid);
                break;
        default;
            $bUrl = __URL__.'/cp';
            $this->_box(0,'没有该产品！',$bUrl,1);
            break;
        }
    }

    private function _adminProductDel($XGid=0){
    	if ($_SESSION['UrlszUserpass'] == 'cpluru'){
    		$product = M ('product');
    		$where = array();
    		$where['id'] = array ('in',$XGid);
    		$product->where($where)->delete();
            unset($product,$where);
            $bUrl = __URL__.'/cp';
            $this->_box(1,'删除产品！',$bUrl,1);
            exit;
    	}else{
    		$this->error('错误!');
    	}
    }


    public function adminLogistics(){
		//物流管理
		if ($_SESSION['UrlszUserpass'] == 'gouwuguanli'){
			$shopping = M ('gouwu');
            $UserID = trim($_REQUEST['UserID']);
            if (!empty($UserID)){
            	import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
            	$fck = M ('fck');
            	$where = array();
				$where['nickname'] = array('like',"%".$UserID."%");
				$where['user_name'] = array('like',"%".$UserID."%");
				$where['_logic']    = 'or';
				$map['_complex']    = $where;
            	$fck_rs = $fck->where($map)->field('id')->select();
            	foreach ($fck_rs as $vo){
            		$fck_id .= ",".$vo['id'];
            	}
            	$UserID = urlencode($UserID);
            	unset($fck,$where,$fck_rs);
            }
            if (!empty($fck_id)){
            	$shopping_where = 'xt_shopping.u_is_pay=1 and xt_shopping.uid in (0'. $fck_id . ',0)';//连表查询条件
            }else{
			    $shopping_where = 'xt_shopping.u_is_pay=1';//连表查询条件
            }
            //查询字段
            $field   = 'xt_gouwu.id,xt_gouwu.name,xt_gouwu.points,xt_gouwu.shu,xt_gouwu.uid';
            $field  .= ',xt_gouwu.is_pay,xt_gouwu.u_is_pay,xt_gouwu.shop_name,xt_gouwu.adt';
            $field  .= ',xt_fck.user_id,xt_fck.user_tel,xt_fck.bank_card,xt_fck.user_name,xt_fck.user_address,xt_fck.nickname,xt_fck.user_phone';
            //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $shopping->where($shopping_where)->count();//总页数
            $listrows = C('PAGE_LISTROWS')  ;//每页显示的记录数
		    if (!empty($UserID)){
                $page_where = 'UserID/'.$UserID;//分页条件
            }
            $Page = new ZQPage($count, $listrows, 1, 0, 3, $page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
            $join = 'left join xt_fck ON xt_shopping.uid=xt_fck.id';//连表查询
            $list = $shopping ->where($shopping_where)->field($field)->join($join)->Distinct(true)->page($Page->getPage().','.$listrows)->select();
            $this->assign('list',$list);//数据输出到模板
            //=================================================
            $title = '物流管理';
            $this->assign('title',$title);
            $this->display('adminLogistics');
		}else{
			$this->error('错误!');
		}
	}

    public function adminLogisticsAC(){
        //处理提交按钮
        $action = $_POST['action'];
        //获取复选框的值
        $XGid = $_POST['tabledb'];
        $shopping = M ('shopping');
        if (!$shopping->autoCheckToken($_POST)){
            $this->error('页面过期，请刷新页面！');
            exit;
        }
        unset($shopping);
        if (!isset($XGid) || empty($XGid)){
            $bUrl = __URL__.'/adminLogistics';
            $this->_box(0,'请选择！',$bUrl,1);
            exit;
        }
        switch ($action){
            case '确认发货';
                $this->_adminLogisticsOK($XGid);
                break;
        default;
            $bUrl = __URL__.'/adminLogistics';
            $this->_box(0,'没有该产品！',$bUrl,1);
            break;
        }
    }

    private function _adminLogisticsOK($XGid){
    	//确定发货
        if ($_SESSION['UrlszUserpass'] == 'gouwuguanli'){
            $shopping = M ('shopping');
            $where = array();
            $where['id'] = array ('in',$XGid);
            $shopping->where($where)->setField('is_pay',1);
            unset($shopping,$where);
            $bUrl = __URL__.'/adminLogistics';
            $this->_box(1,'发货！',$bUrl,1);
            exit;
        }else{
            $this->error('错误!');
        }
    }

    public function Cpcontent() { //显示产品信息
		$cp = M('product');
		$fck = M('fck');
		$product = M ('product');
			$PID = (int) $_GET['id'];
			if (empty($PID)){
				$this->error('错误!');
				exit;
			}
			$where = array();
			$where['id'] = $PID;
			$prs = $product->where($where)->field('content')->find();
			if ($prs){
				$title = '产品备注说明';
				$this->assign('title',$title);
				$this->assign('content',$prs['content']);
				$this->display('Cpcontent');
			}
	}

    public function Buycp() { //购买产品页
		$cp = M('product');
		$fck = M('fck');
		$map['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$f_rs = $fck->where($map)->find();
		$nun = $f_rs['user_id'];
		$agent_cash = $f_rs['agent_cash'];
		$ul = $f_rs['u_level'] - 1;
		$where = array();
		$order = 'id asc';
		$rs = $cp->where($where)->order($order)->field('*')->select();
//		foreach ($rs as $a) {
//			$hyjia = explode('|', $a['hyjia']);
//			if ($ul <= count($hyjia)) {
//				$ep[$a['id']] = $hyjia[$ul];
//
//			}
//		}
//		$this->assign('ep', $ep);
		if (!empty ($rs)) {
			$this->_list_two($cp, $where, $order); //分页
		}
		$this->assign('nun', $nun);
		$this->assign('agent_cash', $agent_cash);
		$this->display('Buycp');
	}

	public function BUY() {
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$history = M('history');
		$ydate = mktime();
		$cid = $_SESSION[C('USER_AUTH_KEY')];
		$map['id'] = $cid;
		$f_rs = $fck->where($map)->find();
		$agent_cash = $f_rs['agent_cash'];
		$ul = $f_rs['u_level'] - 1;
		$uid = $_POST['uid'];//所以产品的ID
		$where['id'] = array (
			'in',
			$uid
		);
		$rs = $cp->where($where)->select();//查找所以cp；
		foreach ($rs as $a) {
			$aa = "shu".$a['id'];

			$cc = $_POST[$aa];
			if ($cc != 0) {
				$price = 0;
				$price = $price + $a['money'] * $cc;
				$txt .= $a['id'] .',';

			}
		}

		if ($price == 0) {
			$this->error('温馨提示：\n\n 请您输入购买数量,\n请检验后再试！');
			exit;
		}
		if ($price > $agent_cash) {
			$this->error('温馨提示：\n\n 你的余额不足,\n请检验后再试！');
			exit;
		} else {
			$where1['id'] = array (
				'in',
				$txt.'0'
			);
			$rs1 = $cp->where($where1)->select();
			$i=0;
			$p=array();
			foreach ($rs1 as $b) {
				$id = $b['id'];
				$aa1 = "shu" . $b['id'];
				$cc1 = $_POST[$aa1];
				if ($cc1 != 0) {
					$hy1 = $b['money'];

					$p[$i] = $hy1 * $cc1;
					$p1 = $hy1 * $cc1;
					$i++;
//					$price2 = $agent_cash -$price;
					$gouwu->execute("INSERT INTO __TABLE__ (UID,DID,lx,ispay,PDT,money,shu,Cprice,guquan) VALUES ($cid,$id,1,0,$ydate,$hy1,'$cc1','$p1',0) ");

				}

			}
			$p2=0;
			for($i=0;$i<count($p);$i++){
				$p2=$p2+$p[$i];
			}
			$Zong = $agent_cash-$p2;
			$fck->execute("UPDATE __TABLE__ SET `agent_cash`=agent_cash-$p2  WHERE id=$cid");
			$history->execute("INSERT INTO __TABLE__ (uid,action_type,pdt,epoints,bz,zong,qubie) VALUES ($cid,100,$ydate,'-$p2','购物',$Zong,3) ");

			$bUrl = __URL__ . '/Buycp';
			$this->_box(1, '购买成功！', $bUrl, 1);
		}
	}

	public function BUY1() {
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$history = M('history');
		$fee = M ('fee');
		$fee_rs = $fee ->find();
		$fee_s14 =  $fee_rs['s14'];
		$ydate = mktime();
		$cid = $_SESSION[C('USER_AUTH_KEY')];
		$map['id'] = $cid;
		$f_rs = $fck->where($map)->find();
		$agent_cash = $f_rs['agent_cash'];
		$ul = $f_rs['u_level'] - 1;
		$aa = "shu1";
		$cc = $_POST['shu1'];

		if ($cc != 0) {
			$price = $fee_s14 * $cc;
		}

		if ($price == 0) {
			$this->error('温馨提示：\n\n 请您输入购买数量,\n请检验后再试！');
			exit;
		}
		if ($price > $agent_cash) {
			$this->error('温馨提示：\n\n 你的余额不足,\n请检验后再试！');
			exit;
		} else {
				$aa1 = "shu1";
				$cc1 = $_POST[$aa1];
				if ($cc1 != 0) {
					$hy1 = $fee_s14;
					$p = $hy1 * $cc1;
//					$price2 = $agent_cash -$price;
					$gouwu->execute("INSERT INTO __TABLE__ (UID,lx,ispay,PDT,money,shu,Cprice,guquan) VALUES ($cid,1,0,$ydate,$hy1,'$cc1','$p',1) ");

				}

			$Zong = $agent_cash-$p;
			$fck->execute("UPDATE __TABLE__ SET `agent_cash`=agent_cash-$p  WHERE id=$cid");
			$history->execute("INSERT INTO __TABLE__ (uid,action_type,pdt,epoints,bz,zong,qubie) VALUES ($cid,101,$ydate,'-$p','购买股权',$Zong,101) ");

			$bUrl = __URL__ . '/guqgoumai';
			$this->_box(1, '购买成功！', $bUrl, 1);
		}
	}

	public function BuycpInfo() {//购买信息
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$map['uid'] = $id;
		$map['guquan'] = array('neq',1);
		 //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $gouwu->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
//            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
		$where = 'xt_gouwu.ID>0 and xt_gouwu.guquan=0 and xt_gouwu.shu>0 and xt_gouwu.uid ='.$id;
		$field = 'xt_fck.user_id,xt_fck.nickname,xt_product.name,xt_gouwu.PDT,xt_gouwu.id,xt_gouwu.money,xt_gouwu.ispay,xt_gouwu.shu' .
		',xt_gouwu.Cprice';
		$join = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join1 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询
		$list = $gouwu->where($where)->field($field)->join($join)->join($join1)->order('PDT desc')->page($Page->getPage().','.$listrows)->select();
		$rs1 = $gouwu->where($map)->sum('Cprice');
		$this->assign('count', $rs1);
		$this->assign('list', $list);
		$this->display('BuycpInfo');
	}

	public function BuycpInfo1() {//购买信息
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$map['guquan'] = array('neq',1);
		//=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $gouwu->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
//            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板
		$where = 'xt_gouwu.ID>0 and xt_gouwu.shu>0 and xt_gouwu.guquan=0 ';
		$field = 'xt_fck.user_id,xt_fck.nickname,xt_product.name,xt_gouwu.PDT,xt_gouwu.id,xt_gouwu.money,xt_gouwu.ispay,xt_gouwu.shu' .
		',xt_gouwu.Cprice';
		$join = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join1 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询
		$list = $gouwu->where($where)->field($field)->join($join)->join($join1)->order('PDT desc')->select();
		$rs1 = $gouwu->sum('Cprice');
		$this->assign('count', $rs1);
		$this->assign('list', $list);
		$this->display('BuycpInfo1');
	}

	public function fahuo(){//确定发货
		$gouwu = M('gouwu');
		$id = $_GET['id'];
		$gouwu->execute("UPDATE __TABLE__ SET `ispay`=1  WHERE id=$id");
			$bUrl = __URL__ . '/BuycpInfo1';
			$this->_box(1, '发货成功！', $bUrl, 1);
	}

	public function delcp() {//删除产品
		$gouwu = M('gouwu');
		$did = $_GET['id'];
		$where['id'] = $did;
		$rs = $gouwu->where($where)->delete();
		if ($rs) {
			$this->success('删除成功！');
		} else {
			$this->success('删除失败！');
		}
	}

	public function guqgoumai(){//股权购买
		$cp = M('product');
		$fck = M('fck');
		$fee = M ('fee');
		$gouwu = M('gouwu');
		$map['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$fee_rs = $fee ->find();
		$f_rs = $fck->where($map)->find();
		$s14 = $fee_rs['s14'];
		$nun = $f_rs['user_id'];
		$agent_cash = $f_rs['agent_cash'];
		$ul = $f_rs['u_level'] - 1;
		$where = array();
		$order = 'id asc';
		$rs = $cp->where($where)->order($order)->field('*')->select();
		if (!empty ($rs)) {
			$this->_list_two($cp, $where, $order); //分页
		}


		$map1['guquan'] = 1;
		$where1 = 'xt_gouwu.ID>0 and xt_gouwu.shu>0 and xt_gouwu.guquan>0 ';
		$field = 'xt_fck.user_id,xt_fck.nickname,xt_product.name,xt_gouwu.PDT,xt_gouwu.id,xt_gouwu.money,xt_gouwu.ispay,xt_gouwu.shu' .
		',xt_gouwu.Cprice';
		$join = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join1 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询
		$list = $gouwu->where($where1)->field($field)->join($join)->join($join1)->order('PDT desc')->select();
		$rs1 = $gouwu->where($map1)->sum('Cprice');
		$this->assign('count', $rs1);
		$this->assign('list', $list);


		$this->assign('nun', $nun);
		$this->assign('agent_cash', $agent_cash);
		$this ->assign('s14',$s14);
		$this ->display('Buygq');



	}

	public function guqshez(){//股权设置
		$fee = M ('fee');
		$gouwu = M('gouwu');
		$where['guquan'] = 1;
		$g_rs = $gouwu ->where($where)->sum('shu');
		$fee_rs = $fee ->find();
		$fee_s14 =  $fee_rs['s14'];
		$this ->assign('g_rs',$g_rs);
		$this ->assign('fee_s14',$fee_s14);
		$this ->display('guqshez');

	}

	public function guqshezSave(){//股权设置
//		if ($_SESSION['UrlszUserpass'] == 'xinxi'){
			$fee = M ('fee');
			$rs = $fee -> find();
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
                if (!empty($arr_s[$i])){
                	if(empty($s_sql2)){
                    	$s_sql = 's'.$i . "='{$arr_s[$i]}'";
                	}else{
                		$s_sql .= ',s'.$i . "='{$arr_s[$i]}'";
                	}
                }
            }

			$fee->execute("update __TABLE__ SET ".$s_sql2 . $s_sql. "  where `id`=1");
			//$fee -> where($where) -> data($data) -> save();
			$this->success('参数设置！');
			exit;
//		}else{
//			$this->error('错误!'); //12345678901112131417181920s3
//			exit;
//		}
	}

	public function gmjl(){//购买记录
		$cp = M('product');
		$fck = M('fck');
		$gouwu = M('gouwu');
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$UserID = $_REQUEST['UserID'];
			$ss_type = (int) $_REQUEST['type'];
			if (!empty($UserID)){
				import ( "@.ORG.KuoZhan" );  //导入扩展类
                $KuoZhan = new KuoZhan();
                if ($KuoZhan->is_utf8($UserID) == false){
                    $UserID = iconv('GB2312','UTF-8',$UserID);
                }
                unset($KuoZhan);
//				$where['nickname'] = array('like',"%".$UserID."%");
//				$where['user_id'] = array('like',"%".$UserID."%");
//				$where['_logic']    = 'or';
//				dump($where);
//				$map['_complex']    = $where;
				$map = "(xt_fck.nickname like '%{$UserID}%' OR xt_fck.user_id like '%{$UserID}%') AND ";
				$UserID = urlencode($UserID);
			}


		$map1['guquan'] = 1;
		 //=====================分页开始==============================================
            import ( "@.ORG.ZQPage" );  //导入分页类
            $count = $gouwu->where($map)->count();//总页数
       		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
            $page_where = 'UserID=' . $UserID;//分页条件
            $Page = new ZQPage($count, $listrows, 1, 0, 3,$page_where);
            //===============(总页数,每页显示记录数,css样式 0-9)
            $show = $Page->show();//分页变量
            $this->assign('page',$show);//分页变量输出到模板

		$map .= 'xt_gouwu.ID>0 and xt_gouwu.shu>0 and xt_gouwu.guquan>0 ';
		$field = 'xt_fck.user_id,xt_fck.nickname,xt_product.name,xt_gouwu.PDT,xt_gouwu.id,xt_gouwu.money,xt_gouwu.ispay,xt_gouwu.shu' .
		',xt_gouwu.Cprice';
		$join = 'left join xt_fck ON xt_gouwu.UID=xt_fck.id'; //连表查询
		$join1 = 'left join xt_product ON xt_gouwu.DID=xt_product.id'; //连表查询
		$list = $gouwu->where($map)->field($field)->join($join)->join($join1)->order('PDT desc')->page($Page->getPage().','.$listrows)->select();
		$rs1 = $gouwu->where($map1)->sum('Cprice');
		$this->assign('count', $rs1);
		$this->assign('list', $list);
		$this->display('gmjl');
	}

	protected function _list_two($model, $map, $sortBy = '', $asc = false) {

		//取得满足条件的记录数
		$count = $model->where($map)->count('id');
		if ($count > 0) {
			import("@.ORG.Page");
			//创建分页对象
			if (!empty ($_REQUEST['listRows'])) {
				$listRows = $_REQUEST['listRows'];
			} else {
				$listRows = '';
			}
			$p = new Page($count, 10);
			//分页查询数据
			$order = !empty ($sortBy) ? $sortBy : $model->getPk();
			$voList = $model->where($map)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ($map as $key => $val) {
				if (!is_array($val)) {
					$p->parameter .= "$key=" . urlencode($val) . "&";
				}
			}

			//分页显示
			$page = $p->show();
			//列表排序显示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign('clist', $voList);
			$this->assign('sort', $sort);
			$this->assign("page", $page);
		}
		Cookie :: set('_currentUrl_', __SELF__);
		return;
	}

}
?>