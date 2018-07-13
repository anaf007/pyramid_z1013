<?php
class PublicAction extends CommonAction {
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		$this->_inject_check(1);//调用过滤函数
		$this->_Config_name();//调用参数
	}


	public function navi(){
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$rs = M ('fck') -> field('is_boss,is_agent') -> find($id);
		$this -> assign('rs',$rs);
		$this -> display('navi');
	}

	/* ---------------查看资料---------------- */
	public function profile() {
		$this->_checkUser();
		if ($_SESSION['DLTZURL02'] == 'ss'){
			$fck	 =	 M('fck');
			$fee     =   M('fee');

			$vo	= $fck -> getById($_SESSION[C('USER_AUTH_KEY')]);  //该登录会员信息
			$this->assign('vo',$vo);

			$this->display('profile');

		}else{
			$this->error('操作错误!');
			exit;
		}
	}

	/* ---------------显示用户修改资料界面---------------- */
	public function updateUserInfo(){
		if ($_SESSION['DLTZURL02'] == 'updateUserInfo'){
			$fck	 =	 M('fck');
			$id   = $_SESSION[C('USER_AUTH_KEY')];
			//输出登录用户资料记录
			$vo	= $fck -> getById($id);  //该登录会员记录
			$this->assign('vo',$vo);
			unset($vo);

			//输出银行
			$b_bank = $fck -> where('id='.$id) -> field("bank_name") -> find();
			$this->assign('b_bank',$b_bank);

			$fee = M ('fee');
			$fee_s = $fee->field('s2,s9,i4,str29,str99')->find();
			$wentilist = explode('|',$fee_s['str99']);
			$this->assign('wentilist',$wentilist);
			$bank = explode('|',$fee_s['str29']);
			$this->assign('bank',$bank);

			unset($bank,$b_bank);

			$this->display('updateUserInfo');

		}else{
			$this->error('操作错误!');
			exit;
		}
	}

	/* --------------- 修改保存会员信息 ---------------- */
	public function userInfoSave(){
		if($_POST['ID'] != $_SESSION[C('USER_AUTH_KEY')]){
			$this->error('操作错误1!');
			exit;
		}

		if ($_SESSION['DLTZURL02'] == 'updateUserInfo'){
			$fck = M('fck');

			$myw = array();
			$myw['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$mrs = $fck->where($myw)->field('id,wenti_dan')->find();
			if(!$mrs){
				$this->error('非法提交数据!');
				exit;
			}else{
				$mydaan = $mrs['wenti_dan'];
			}

//			$huida = trim($_POST['wenti_dan']);
//			if(empty($huida)){
//				$this->error('请输入底部的密保答案！');
//				exit;
//			}
//			if($huida!=$mydaan){
//				$this->error('密保答案验证不正确！');
//				exit;
//			}

			//$_POST['NickName'] = $this->nickname($_POST['NickName'],$_SESSION[C('USER_AUTH_KEY')]);  //检测昵称

			$data = array();
			$data['nickname']         = $_POST['NickName'];        //会员昵称
			$data['bank_name']        = $_POST['BankName'];        //银行名称
			$data['bank_card']        = $_POST['BankCard'];        //银行卡号
			$data['user_name']        = $_POST['UserName'];        //开户姓名

			$data['bank_province']    = $_POST['BankProvince'];    //省份
			$data['bank_city']        = $_POST['BankCity'];        //城市
			$data['bank_address']     = $_POST['BankAddress'];     //开户地址
			$data['user_code']        = $_POST['UserCode'];        //身份证号码
			$data['user_address']     = $_POST['UserAddress'];     //联系地址
			$data['email']            = $_POST['UserEmail'];       //电子邮箱
			$data['user_tel']         = $_POST['UserTel'];         //联系电话
			$data['qq']         = $_POST['qq'];         //qq

			$xg_wenti = trim($_POST['xg_wenti']);
			$xg_wenti_dan = trim($_POST['xg_wenti_dan']);
			if(!empty($xg_wenti)){
				$data['wenti']			= $xg_wenti;//问题
			}
			if(!empty($xg_wenti_dan)||strlen($xg_wenti_dan)>0){
				$data['wenti_dan']		= $xg_wenti_dan;//答案
			}


			$data['id']               = $_SESSION[C('USER_AUTH_KEY')];              //要修改资料的AutoId

			$rs = $fck->save($data);
			if($rs){
				$bUrl = __URL__.'/updateUserInfo';
				$this->_box(1,'资料修改成功！',$bUrl,1);
			}else{
				$this->error('操作错误2!');
				exit;
			}
		}else{
			$this->error('操作错误3!');
			exit;
		}
	}


    /* ********************** 修改密码 ********************* */
    public function changePwd(){
		$this->_checkUser();
        //对表单提交处理进行处理或者增加非表单数据
		$fck    =   M('fck');
		//检测是否是外部提交
		if(!$fck->create()){
			$this->error('页面过期,请刷新页面');
			exit;
		}
		if(md5($_POST['verify']) != $_SESSION['verify']) {
			$this->error('验证码错误！');
			exit;
		}

		$myw = array();
		$myw['id'] = $_SESSION[C('USER_AUTH_KEY')];
		$mrs = $fck->where($myw)->field('id,wenti_dan')->find();
		if(!$mrs){
			$this->error('非法提交数据!');
			exit;
		}else{
			$mydaan = $mrs['wenti_dan'];
		}

//		$huida = trim($_POST['wenti_dan']);
//		if(empty($huida)){
//			$this->error('请输入底部的密保答案！');
//			exit;
//		}
//		if($huida!=$mydaan){
//			$this->error('密保答案验证不正确！');
//			exit;
//		}

		$map	=	array();

		//检测密码级别及获取旧密码
		if ($_POST['type'] == 1){
			$map['Password']  = pwdHash($_POST['oldpassword']);
		}elseif($_POST['type'] == 2){
			$map['passopen']  = pwdHash($_POST['oldpassword']);
		}elseif($_POST['type'] == 3){
			$map['passopentwo'] = pwdHash($_POST['oldpassword']);
		}else{
			$this->error('请选择修改密码级别！');
			exit;
		}

		//检查两次密码是否相等
		if($_POST['password'] != $_POST['repassword']){
			$this->error('两次输入的密码不相等！');
			exit;
		}

        if(isset($_POST['account'])){
            $map['user_id']	 =	 $_POST['account'];
        }elseif(isset($_SESSION[C('USER_AUTH_KEY')])){
            $map['id']	     =	 $_SESSION[C('USER_AUTH_KEY')];
        }

        //检查用户
		$result = $fck->where($map)->field('id')->find();
        if(!$result){
            $this->error('旧密码错误！');
        }else {
			//修改密码
			$pwds = pwdHash($_POST['password']);
			if ($_POST['type'] == 1){
				$fck->where($map)->setField('pwd1',$_POST['password']);  //一级密码不加密
				$fck->where($map)->setField('password',$pwds);           //一级密码加密
			}elseif($_POST['type'] == 2){
				$fck->where($map)->setField('pwd2',$_POST['password']);  //二级密码不加密
				$fck->where($map)->setField('passopen',$pwds);           //二级密码加密
			}elseif($_POST['type'] == 3){
				$fck->where($map)->setField('pwd3',$_POST['password']);  //三级密码不加密
				$fck->where($map)->setField('passopentwo',$pwds);          //三级密码加密
			}
			//9260729
			//$fck->save();
		//生成认证条件
        $mapp            =   array();
		// 支持使用绑定帐号登录
		$mapp['id']    = $_SESSION[C('USER_AUTH_KEY')];
		$mapp['user_id']	= $_SESSION['loginUseracc'];
		import ( '@.ORG.RBAC' );
        $authInfoo = RBAC::authenticate($mapp);
        if(false === $authInfoo) {
            $this->LinkOut();
			$this->error('帐号不存在！');
			exit;
        }else {
			//更新session
			$_SESSION['login_sf_list_u'] = md5($authInfoo['user_id'].'wodetp_new_1012!@#'.$authInfoo['password'].$_SERVER['HTTP_USER_AGENT']);
		}
			$bUrl = __URL__.'/password';
			$this->_box(1,'修改密码成功！',$bUrl,1);
			exit;
         }
    }


	//过滤查询字段
	function _filter(&$map){
		$map['title'] = array('like',"%".$_POST['name']."%");
	}
	// 顶部页面
	public function top() {

		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$model	=	M("Group");
		$list	=	$model->where('status=1')->getField('ID,title');
		$this->assign('nodeGroupList',$list);
		$this->display();
	}
	// 尾部页面
	public function footer() {


		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display();
	}
	// 菜单页面
	public function menu() {
        $this->_checkUser();
		$map = array();

		$id = $_SESSION[C('USER_AUTH_KEY')];
		$field = '*';

		$map['isid']    = $id;   //信息所属所属ID
		$map['s_uid']   = $id;   //会员ID
		$map['is_read'] = 0;     // 0 为未读
        $info_count = M ('msg') -> where($map) -> count(); //总记录数
		$this -> assign('info_count',$info_count);

		$rs = array();
		$rs['0'] = $_SESSION[C('USER_AUTH_KEY')];
		$rs['1'] = $_SESSION['login_isAgent'];
		$fck = M('fck');
		$fwhere = array();
		$fwhere['ID'] = $_SESSION[C('USER_AUTH_KEY')];
		$frs = $fck->where($fwhere)->field('*')->find();
		//dump($frs);
		$HYJJ = '';
		$this->_levelConfirm($HYJJ,1);
		$rs['2'] = $HYJJ[$frs['u_level']];

		$this->assign('fck_rs',$frs);
		$this->assign('rs',$rs);//后台权限
		$this->display('menu');
	}

    // 后台首页 查看系统信息
    public function main() {
        $this->_checkUser();
        $id = $_SESSION[C('USER_AUTH_KEY')];  //登录AutoId

        $bonus = M ('bonus');  //奖金表
		$where = array();
		$where['uid'] = $id;
        $field  = '*';
        $list = $bonus->where($where)->field($field)->order('id desc')->limit(10)->select();
        $this->assign('list',$list);

		$form = M ('form');
		$map = array();
		$map['status'] = array('eq',1);
        $field  = '*';

        $fnlist = $form->where($map)->field($field)->order('baile desc,id desc')->find();
        $this->assign('fn_list',$fnlist);//数据输出到模板

        $nlist = $form->where($map)->field($field)->order('baile desc,id desc')->limit('1,10')->select();
        $this->assign('n_list',$nlist);//数据输出到模板

        $product = M ('product');
        $xs_nn = 12;
        $where = array();
		$where['yc_cp'] = array('eq',0);
		$clist = $product->where($where)->field($field)->order('id asc')->limit($xs_nn)->select();
		$cplen = count($clist);
		if($cplen<$xs_nn){
			$tt = 0;
			for($i=$cplen;$i<$xs_nn;$i++){
				$clist[$i] = $clist[$tt];
				$tt++;
				if($tt>=$cplen){
					$tt = 0;
				}
			}
		}
        $this->assign('c_list',$clist);//数据输出到模板

		//推荐人数
		$fck = M ('fck');
		$map = array();
		$map['re_id']    = $id;
		$map['is_pay']   = 1;
        $re_count = $fck -> where($map) -> count();
		$this -> assign('re_count',$re_count);

		$map = array();
		$map['isid']    = $id;   //信息所属所属ID
		$map['s_uid']   = $id;   //会员ID
		$map['is_read'] = 0;     // 0 为未读
        $info_count = M ('msg') -> where($map) -> count(); //总记录数
		$this -> assign('info_count',$info_count);

		//会员级别
        $urs = $fck -> where('id='.$id)->field('*') -> find();
		$lev = $urs['u_level']-1;
		$Level = explode('|',C("Member_Level"));

		$this -> assign('u_level',$Level[$lev]);//会员级别
		$this -> assign('fck_rs',$urs);//总奖金

		$c_nn = $fck->where('p_path like "%,'.$id.',%" and is_pay>0')->count();
		$this->assign('c_nn',$c_nn);

		$t_dd = $fck->where('re_id='.$id.' and is_pay>0')->sum('f4');
		if(empty($t_dd))$t_dd=0;
		$this->assign('t_dd',$t_dd);
		$c_dd = $fck->where('p_path like "%,'.$id.',%" and is_pay>0')->sum('f4');
		if(empty($c_dd))$c_dd=0;
		$this->assign('c_dd',$c_dd);

		$xiaof = M('xiaof');
		$all_n = $xiaof->where('uid='.$id.' and is_pay=0 and money_two>0')->sum('money_two');
		if(empty($all_n))$all_n=0;
		$this->assign('all_n',$all_n);
		$all_m = $xiaof->where('uid='.$id.' and is_pay=0 and money>0')->sum('money');
		if(empty($all_m))$all_m="0.00";
		$this->assign('all_m',$all_m);

		$fee = M('fee');
	    $fee_rs = $fee->field('str21,str22,str23')->find();
		$str21 = $fee_rs['str21'];
		$str22 = $fee_rs['str22'];
		$str23 = $fee_rs['str23'];
		$all_img = $str21."|".$str22."|".$str23;
		$this->assign('all_img',$all_img);

        $this->display();
    }


    // 找回密码1
	public function find_pw() {
		$_SESSION['us_openemail']="";
		$this->display('find_pw');
	}

	// 找回密码2
	public function find_pw_s() {
		if(empty($_SESSION['us_openemail'])){
			if(empty($_POST['us_name'])&&empty($_POST['us_email'])) {
				$_SESSION = array();
				$this->display('../Public/LinkOut');
				return;
			}
			$ptname=$_POST['us_name'];
			$us_email=$_POST['us_email'];
			$fck = M('fck');
			$rs=$fck->where("user_id='".$ptname."'")->field('id,email,user_id,user_name,pwd1,pwd2')->find();
			if ($rs==false){
				$errarry['err']='<font color=red>注：找不到此会员编号！</font>';
				$this->assign('errarry',$errarry);
				$this->display('find_pw');
			}else{
				if($us_email<>$rs['email']){
					$errarry['err']='<font color=red>注：邮箱验证失败！</font>';
					$this->assign('errarry',$errarry);
					$this->display('find_pw');
				}else{

					$passarr=array();
					$passarr[0]=$rs['pwd1'];
					$passarr[1]=$rs['pwd2'];

					$this->send_email($rs['user_id'],$rs['user_name'],$passarr,$us_email);

					$_SESSION['us_openemail']=$us_email;
					$this->find_pw_e($us_email);
				}
			}
		}else{
			$us_email=$_SESSION['us_openemail'];
			$this->find_pw_e($us_email);
		}
	}

	// 找回密码3
	public function find_pw_e($us_email) {
		$this->assign('myask',$us_email);
		$this->display('find_pw_s');
	}


    public function pprofile() {
        $this->_checkUser();
		//列表过滤器，生成查询Map对象
		$id  = $_SESSION[C('USER_AUTH_KEY')];
		$map = array();
		$map['status'] = 1;
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}

		//推荐人数
		$fck = M ('fck');

		//会员级别
        $u_all = $fck -> where('id='.$id)->field('*') -> find();
		$lev = $u_all['u_level']-1;

		$fee = M('fee');
		$fee_rs = $fee->field('s4,s10')->find();
		$s4 = explode('|',$fee_rs['s4']);
		$Level = explode('|',$fee_rs['s10']);

		$this -> assign('mycg',$s4[$lev]);//会员级别
		$this -> assign('u_level',$Level[$lev]);//会员级别
		$this -> assign('rs',$u_all);
        $this->display();
    }

	// 用户登录页面
	public function login() {
		$this->display('login');
	}

	public function index()
	{
		//如果通过认证跳转到首页
		redirect(__APP__);
	}

	// 用户登出
    public function LogOut(){
		$_SESSION = array();
		//unset($_SESSION);
        $this->assign('jumpUrl',__URL__.'/login/');
        $this->success('退出成功！');
    }

	// 登录检测
	public function checkLogin() {
		if(empty($_POST['account'])) {
			$this->error('请输入帐号！');
		}elseif (empty($_POST['password'])){
			$this->error('请输入密码！');
		}elseif (empty($_POST['verify'])){
			$this->error('请输入验证码！');
		}
		$fee = M ('fee');
//		$sel = (int) $_POST['radio'];
//		if($sel <=0 or $sel >=3){
//			$this->error('非法操作！');
//			exit;
//		}
//		if($sel != 1){
//			$this->error('暂时不支持英文版登录！');
//			exit;
//		}

        //生成认证条件
        $map            =   array();
		// 支持使用绑定帐号登录
		$map['user_id']	   = $_POST['account'];
//		$map['nickname'] = $_POST['account'];   //用户名也可以登录
//		$map['_logic']    = 'or';
		//$map['_complex']    = $where;
	    //$map["status"]	=	array('gt',0);
		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}

		import ( '@.ORG.RBAC' );
		$fck = M('fck');
		$field = 'id,user_id,password,is_pay,is_lock,nickname,user_name,is_agent,user_type,last_login_time,login_count,is_boss';
		$authInfo = $fck->where($map)->field($field)->find();
        //使用用户名、密码和状态的方式进行认证
        if(false == $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            if($authInfo['password'] != md5($_POST['password'])) {
				$this->error('密码错误！');
				exit;
            }
			if ($authInfo['is_pay'] <1){
				$this->error('用户尚未开通，暂时不能登录系统！');
				exit;
			}
			if ($authInfo['is_lock']!=0){
				$this->error('用户已锁定，请与管理员联系！');
				exit;
			}
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
            $_SESSION['loginUseracc']		=	$authInfo['user_id'];//用户名
			$_SESSION['loginNickName']		=	$authInfo['nickname'];//会员名
			$_SESSION['loginUserName']		=	$authInfo['user_name'];//开户名
            $_SESSION['lastLoginTime']	=	$authInfo['last_login_time'];
			//$_SESSION['login_count']	    =	$authInfo['login_count'];
			$_SESSION['login_isAgent']	    =	$authInfo['is_agent'];//是否报单中心
			$_SESSION['UserMktimes']        = mktime();
            //身份确认 = 用户名+识别字符+密码
			$_SESSION['login_sf_list_u']    = md5($authInfo['user_id'].'wodetp_new_1012!@#'.$authInfo['password'].$_SERVER['HTTP_USER_AGENT']);

			//登录状态
			$user_type = md5($_SERVER['HTTP_USER_AGENT'].'wtp'.rand(0,999999));
			$_SESSION['login_user_type'] = $user_type;
			$where['id'] = $authInfo['id'];
			$fck->where($where)->setField('user_type',$user_type);
//			$fck->where($where)->setField('last_login_time',mktime());
			//管理员

			$parmd = $this->_cheakPrem();
			if($authInfo['id'] == 1||$parmd[11]==1) {
            	$_SESSION['administrator']		=	1;
            }else{
				$_SESSION['administrator']		=	2;
			}

//			//管理员
//			if($authInfo['is_boss'] == 1) {
//            	$_SESSION['administrator'] =	1;
//            }elseif($authInfo['is_boss'] == 2){
//            	$_SESSION['administrator'] = 3;
//            }elseif($authInfo['is_boss'] == 3){
//                $_SESSION['administrator']  = 4;
//            }elseif($authInfo['is_boss'] == 4){
//                $_SESSION['administrator'] = 5;
//            }elseif($authInfo['is_boss'] == 5){
//                $_SESSION['administrator'] =   6;
//            }elseif($authInfo['is_boss'] == 6){
//                $_SESSION['administrator'] =   7;
//            }else{
//				$_SESSION['administrator'] = 2;
//			}

			$fck->execute("update __TABLE__ set last_login_time=new_login_time,last_login_ip=new_login_ip,new_login_time=".time().",new_login_ip='".$_SERVER['REMOTE_ADDR']."' where id=".$authInfo['id']);

			// 缓存访问权限
            RBAC::saveAccessList();
			$this->success('登录成功！');
		}
	}
	//二级密码验证
	public function cody(){
		$UrlID = (int)$_GET['c_id'];
		if (empty($UrlID)){
			$this->error('二级密码错误!');
			exit;
		}
		if(!empty($_SESSION['user_pwd2'])){
			$url = __URL__."/codys/Urlsz/$UrlID";
			$this->_boxx($url);
			exit;
		}
		$fck   =  M ('cody');
        $list	=  $fck->where("c_id=$UrlID")->getField('c_id');
		if (!empty($list)){
			$this->assign('vo',$list);
			$this->display('cody');
			exit;
		}else{
			$this->error('二级密码错误!');
			exit;
		}
	}
	//二级验证后调转页面
	public function codys(){
		$Urlsz = $_POST['Urlsz'];
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

			$where =array();
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
			case 1:
				$_SESSION['DLTZURL02'] = 'updateUserInfo';
				$bUrl = __URL__.'/updateUserInfo';//修改资料
				$this->_boxx($bUrl);
				break;
			case 2:
				$_SESSION['DLTZURL01'] = 'password';
				$bUrl = __URL__.'/password';//修改密码
				$this->_boxx($bUrl);
				break;
			case 3:
				$_SESSION['DLTZURL01'] = 'pprofile';
				$bUrl = __URL__.'/pprofile';//修改密码
				$this->_boxx($bUrl);
				break;
			case 4:
				$_SESSION['DLTZURL01'] = 'OURNEWS';
				$bUrl = __URL__.'/News';//修改密码
				$this->_boxx($bUrl);
				break;
			default;
				$this->error('二级密码错误!');
				break;
		}
	}
	//修改密码
	public function password($Fid=0){
		if ($_SESSION['DLTZURL01'] == 'password' || $Fid == 2){
			$fck = M('fck');

			$id   = $_SESSION[C('USER_AUTH_KEY')];
			//输出登录用户资料记录
			$where = array();
			$where['id'] = array('eq',$id);
			$vo	= $fck ->where($where)->find();
			$this->assign('vo',$vo);
			unset($vo);

			$this->display('password');
		}else{
			$this->error('操作错误!');
			exit;
		}
	}
	 public function verify()
    {
    	ob_clean();
		$type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Image");
        Image::buildImageVerify();
    }
	//奖金方案
	public function plan($Fid=0){
		$plan = M ('plan');
		$ID = 1;
		$vo = $plan->find(1);
		$vo['content'] = stripslashes($vo['content']);//过滤掉反斜杠
		$this->assign ( 'vo', $vo );
		$this->display ('plan');
	}
	//关于我们
	public function planTwo($Fid=0){
		$plan = M ('plan');
		$ID = 2;
		$vo = $plan->find(2);
		$vo['content'] = stripslashes($vo['content']);//过滤掉反斜杠
		$this->assign ( 'vo', $vo );
		$this->display ('planTwo');
	}

	//创富亮点
	public function planThree($Fid=0){
		$plan = M ('plan');
		$ID = 3;
		$vo = $plan->find(3);
		$vo['content'] = stripslashes($vo['content']);//过滤掉反斜杠
		$this->assign ( 'vo', $vo );
		$this->display ('planThree');
	}

	//公司客服
	public function planFour($Fid=0){
		$plan = M ('plan');
		$ID = 5;
		$vo = $plan->find(5);
		$vo['content'] = stripslashes($vo['content']);//过滤掉反斜杠
		$this->assign ( 'vo', $vo );
		$this->display ('planFour');
	}

	// 修改资料
	public function change() {
		$this->_checkUser();
		if ($_SESSION['DLTZURL02'] == 'ss'){
			$fck	 =	 M('fck');
			if(!$fck->autoCheckToken($_POST)) {
				$this->error('页面过期，请刷新页面！');
			}
			$data = array();
			$data['nickname']      = $_POST['NickName'];
			$data['bank_name']     = $_POST['BankName'];
			$data['bank_card']     = $_POST['BankCard'];
			$data['user_name']     = $_POST['UserName'];
			$data['bank_province'] = $_POST['BankProvince'];
			$data['bank_city']     = $_POST['BankCity'];
			$data['bank_address']  = $_POST['BankAddress'];
			$data['user_code']     = $_POST['UserCode'];
			$data['user_address']  = $_POST['UserAddress'];
			$data['user_post']     = $_POST['UserPost'];
			$data['user_tel']      = $_POST['UserTel'];
			$data['id']            = $_POST['ID'];
			$result	=	$fck->save($data);
			if($result) {
				$bUrl = __URL__.'/profile';
				$this->_box(1,'资料修改！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/profile';
				$this->_box(0,'资料修改！',$bUrl,1);
			}
		}else{
			$bUrl = __URL__.'/profile';
			$this->_box(0,'资料修改！',$bUrl,1);
			exit;
		}
	}

   public function News() {
        $this->_checkUser();
//        if ($_SESSION['DLTZURL01'] == 'OURNEWS'){
			$map = array();
			$map['status'] = 1;
			if (method_exists ( $this, '_filter' )) {
				$this->_filter ( $map );
			}
			$form = M ('form');
	        $field  = '*';
	        //=====================分页开始==============================================
	        import ( "@.ORG.ZQPage" );  //导入分页类
	        $count = $form->where($map)->count();//总页数
	     	$listrows = C('ONE_PAGE_RE');//每页显示的记录数
	     	$listrows = 20;//每页显示的记录数
	        $Page = new ZQPage($count,$listrows,1);
	        //===============(总页数,每页显示记录数,css样式 0-9)
	        $show = $Page->show();//分页变量
	        $this->assign('page',$show);//分页变量输出到模板
	        $list = $form->where($map)->field($field)->order('baile desc,id desc')->page($Page->getPage().','.$listrows)->select();
	        $this->assign('list',$list);//数据输出到模板
	        //=================================================

			$id = $_SESSION[C('USER_AUTH_KEY')];
			$field = '*';
			$map = array();
			$map['isid']    = $id;   //信息所属所属ID
			$map['s_uid']   = $id;   //会员ID
			$map['is_read'] = 0;     // 0 为未读
			$info_count = M ('msg') -> where($map) -> count(); //总记录数
			$this -> assign('info_count',$info_count);


	        $this->display();
//        }else{
//			$this->error('操作错误!');
//			exit;
//		}
    }

	//查询返回一条记录
	public function News_show() {
		$this->_checkUser();
//		if ($_SESSION['DLTZURL01'] == 'OURNEWS'){
			$model = M ('Form');
			$id = (int) $_GET['NewID'];
			$where = array();
			$where['id'] = $id;
			$where['status'] = 1;
			$vo = $model->where($where)->find();
			$vo['content'] = stripslashes($vo['content']);//去掉反斜杠
			$this->assign ( 'vo', $vo );
			$this->display ();
//		}else{
//			$this->error('操作错误!');
//			exit;
//		}
	}

   public function Product() {
        $this->_checkUser();
		$map = array();
		$map['id'] = array('gt',0);
		$product = M ('product');
        $field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $product->where($map)->count();//总页数
     	$listrows = C('ONE_PAGE_RE');//每页显示的记录数
     	$listrows = 12;//每页显示的记录数
        $Page = new ZQPage($count,$listrows,1);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $list = $product->where($map)->field($field)->order('id desc')->page($Page->getPage().','.$listrows)->select();
        $this->assign('list',$list);//数据输出到模板
        //=================================================
        $downurl=array();
        foreach($list as $vvv){
        	$upimg=$vvv['img'];
	        if(strpos(strtolower($upimg),'http://')!==false){
	        	$downurl[$vvv['id']]=$upimg;
	        }else{
	        	$downurl[$vvv['id']]=__ROOT__.'/'.$upimg;
	        }
        }
		$this->assign('downurl',$downurl);
        $this->display();
    }

	//查询返回一条记录
	public function Product_show() {
		$this->_checkUser();
//		if ($_SESSION['DLTZURL01'] == 'OURNEWS'){
			$product = M ('product');
			$id = (int) $_GET['pid'];
			$where = array();
			$where['id'] = $id;
			$vo = $product->where($where)->find();
			$vo['content'] = stripslashes($vo['content']);//去掉反斜杠
			$upimg=$vo['img'];
	        if(strpos(strtolower($upimg),'http://')!==false){
	        	$downurl=$upimg;
	        }else{
	        	$downurl=__ROOT__.'/'.$upimg;
	        }
	        $this->assign('downurl',$downurl);
			$this->assign ( 'vo', $vo );
			$this->display ();
	}

	public function send_email($user_id,$username,$userpass,$useremail)
	{

		require_once "stemp/class.phpmailer.php";
		require_once "stemp/class.smtp.php";

		$arra=array();
		$arra[0]=$userpass[0];
		$arra[1]=$userpass[1];

		$mail = new PHPMailer();
		//$address = $_POST['address'];
		//$address = "119515301@qq.com";
		$mail    = new PHPMailer();
		//$mail->IsSMTP();      IsSendmail            // send via SMTP
		$mail->IsSMTP();                  // send via SMTP
		$mail->Host  = "smtp.163.com";   // SMTP servers
		$mail->SMTPAuth = true;           // turn on SMTP authentication
		$mail->Username = "yuyangtaoyecn";     // SMTP username     注意：普通邮件认证不需要加 @域名
		$mail->Password = "yuyangtaoyecn666";          // SMTP password
		$mail->From  = "yuyangtaoyecn@163.com";        // 发件人邮箱
		$mail->FromName =  "商务会员管理系统";    // 发件人
		$mail->CharSet  = "utf-8";              // 这里指定字符集！
		$mail->Encoding = "base64";
		$mail->AddAddress("".$useremail."","".$useremail."");    // 收件人邮箱和姓名
		//$mail->AddAddress("119515301@qq.com","text");    // 收件人邮箱和姓名
		$mail->AddReplyTo("".$useremail."","163.com");
		$mail->IsHTML(true);    // send as HTML
		$mail->Subject  = '感谢您使用密码找回'; // 邮件主题
			$body="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"font-size:12px; line-height:24px;\">";
			$body=$body."<tr>";
			$body=$body."<td height=\"30\">尊敬的客户:".$username."</td>";
			$body=$body."</tr>";
			$body=$body."<tr>";
			$body=$body."<td height=\"30\">你的账户编号:".$user_id."</td>";
			$body=$body."</tr>";
			$body=$body."<tr>";
			$body=$body."<td height=\"30\">一级密码为:".$arra[0]."</td>";
			$body=$body."</tr>";
			$body=$body."<tr>";
			$body=$body."<td height=\"30\">二级密码为:".$arra[1]."</td>";
			$body=$body."</tr>";
			$body=$body."此邮件由系统发出，请勿直接回复。<br>";
			$body=$body."</td></tr>";
			$body=$body."<tr>";
			$body=$body."<td height=\"30\" align=\"right\">".date("Y-m-d H:i:s")."</td>";
			$body=$body."</tr>";
			$body=$body."</table>";
		$mail->Body = "".$body."";// 邮件内容
		$mail->AltBody ="text/html";
//		$mail->Send();

		if(!$mail->Send())
		{
		echo "Message could not be sent. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
		}
		//echo "Message has been sent";
	}

}
?>