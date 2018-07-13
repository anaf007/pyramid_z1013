<?php
class NewsAction extends CommonAction{
	function _initialize() {
		$this->_inject_check(0);//调用过滤函数
		$this->_Admin_checkUser();//后台权限检测
		header("Content-Type:text/html; charset=utf-8");
	}
	public function plan(){
		$plan   =  M ('plan');
        $list = $plan->find(1);
		$this->assign('list',$list);
		$this->us_fckeditor('content',$list['content'],400,"100%");
		$this->display();
	}
	public function planTwo(){
		$plan   =  M ('plan');
        $list = $plan->find(2);
		$this->assign('list',$list);
		$this->display();
	}
	public function planThree(){
		$plan   =  M ('plan');
        $list = $plan->find(3);
		$this->assign('list',$list);
		$this->display();
	}
	public function planFour(){
		$plan   =  M ('plan');
        $list = $plan->find(4);
        if ($list==false){
        	$list="";
        }
		$this->assign('list',$list);
		$this->display();
	}
	public function planFive(){
		$plan   =  M ('plan');
        $list = $plan->find(5);
        if ($list==false){
        	$list="";
        }
		$this->assign('list',$list);
		$this->display();
	}
	public function planInsert(){
		$plan = M ('plan');
//		if (!$plan->autoCheckToken($_POST)){
//				$this->error('页面过期，请刷新页面！');
//                exit;
//			}
		$data = array();
		$content = $_POST['content'];
		$data['content'] = $content;
		$data['id'] = 1;
		//保存当前数据对象
		$list = $plan->save ($data);
		if ($list !== false) { //保存成功
			$this->success ('保存成功!');
		} else {
			//失败提示
			$this->error ('保存失败!');
		}
	}
	public function planInsertTwo(){
		$plan = M ('plan');
//		if (!$plan->autoCheckToken($_POST)){
//				$this->error('页面过期，请刷新页面！');
//                exit;
//			}
		$data = array();
		$content = $_POST['content'];
		$data['content'] = $content;
		$data['id'] = 2;
		//保存当前数据对象

		//dump($content);exit;
		$list = $plan->save ($data);
		if ($list !== false) { //保存成功
			$this->success ('保存成功!');
		} else {
			//失败提示
			$this->error ('保存失败!');
		}
	}

	public function planInsertThree(){
		$plan = M ('plan');
//		if (!$plan->autoCheckToken($_POST)){
//				$this->error('页面过期，请刷新页面！');
//                exit;
//			}
		$data = array();
		$content = $_POST['content'];
		$data['content'] = $content;
		$data['id'] = 3;
		//保存当前数据对象

		//dump($content);exit;
		$list = $plan->save ($data);
		if ($list !== false) { //保存成功
			$this->success ('保存成功!');
		} else {
			//失败提示
			$this->error ('保存失败!');
		}
	}

	public function planInsertFour(){
		$plan = M ('plan');
//		if (!$plan->autoCheckToken($_POST)){
//				$this->error('页面过期，请刷新页面！');
//                exit;
//			}
		$data = array();
		$content = $_POST['content'];
		$data['content'] = $content;
		$data['id'] = 4;
		//保存当前数据对象

		//dump($content);exit;
		$count = $plan->where('id=4')->count();
		if ($count==0){
			$plan->add($data);
			$this->success ('保存成功!');
		}else{
			$list = $plan->save ($data);
			if ($list !== false) { //保存成功
				$this->success ('保存成功!');
			} else {
				//失败提示
				$this->error ('保存失败!');
			}
		}
	}

	public function planInsertFive(){
		$plan = M ('plan');
//		if (!$plan->autoCheckToken($_POST)){
//				$this->error('页面过期，请刷新页面！');
//                exit;
//			}
		$data = array();
		$content = $_POST['content'];
		$data['content'] = $content;
		$data['id'] = 5;
		//保存当前数据对象

		//dump($content);exit;
		$count = $plan->where('id=5')->count();
		if ($count==0){
			$plan->add($data);
			$this->success ('保存成功!');
		}else{
			$list = $plan->save ($data);
			if ($list !== false) { //保存成功
				$this->success ('保存成功!');
			} else {
				//失败提示
				$this->error ('保存失败!');
			}
		}
	}

	//新闻管理首页
	public function index(){
		$form = M ('form');
		$title = trim($_REQUEST['title']);
		if (!empty($title)){
			import ( "@.ORG.KuoZhan" );  //导入扩展类
			$KuoZhan = new KuoZhan();
			if ($KuoZhan->is_utf8($title) == false){
				$title = iconv('GB2312','UTF-8',$title);
			}
			unset($KuoZhan);
			$map['title'] = array('like',"%".$title."%");
			$title = urlencode($title);
		}

        $field  = '*';
        //=====================分页开始==============================================
        import ( "@.ORG.ZQPage" );  //导入分页类
        $count = $form->where($map)->count();//总页数
   		$listrows = C('ONE_PAGE_RE');//每页显示的记录数
    //  $Page = new ZQPage($count,$listrows,1);
		$this_where = 'title='. $title;
        $Page = new ZQPage($count,$listrows,3,'','',$this_where);
        //===============(总页数,每页显示记录数,css样式 0-9)
        $show = $Page->show();//分页变量
        $this->assign('page',$show);//分页变量输出到模板
        $list = $form->where($map)->field($field)->order('baile desc,create_time desc,id desc')->page($Page->getPage().','.$listrows)->select();
        $this->assign('list',$list);//数据输出到模板
        //=================================================

		$this->display();
	}

	public function News(){
		//处理提交按钮
		$action = trim($_POST['action']);
		//获取复选框的值
		$PTid = $_POST['tabledb'];
		if ($action == '添加新闻'){
			$nowtime = date("Y-m-d H:i:s");
			$this->assign('nowtime',$nowtime);
			$this->us_fckeditor('content',"",400,"100%");
			$this->News_add();
			exit;
		}
		if (!isset($PTid) || empty($PTid)){
			$bUrl = __URL__.'/index';
			$this->_box(0,'请选择新闻！',$bUrl,1);
			exit;
		}
		switch ($action){
			case '添加';
				$this->News_1($PTid);
				break;
			case '启用';
				$this->News_2($PTid);
				break;
			case '禁用';
				$this->News_3($PTid);
				break;
			case '删除';
				$this->News_4($PTid);
				break;
			case '设置置顶';
				$this->News_Top($PTid);
				break;
			case '取消置顶';
				$this->News_NoTop($PTid);
				break;
		default;
			$bUrl = __URL__.'/index';
			$this->_box(0,'没有该新闻！',$bUrl,1);
			break;
		}
	}
	public function News_edit(){
		$EDid = $_GET['EDid'];
		$User = M ('form');
		$where = array();
		$where['id'] = $EDid;
		$rs = $User->where($where)->find();
		if ($rs){
			$this->assign('vo',$rs);
			$this->us_fckeditor('content',$rs['content'],400,"100%");
			$this->display('News_edit');
		}else{
			$this->error('没有该新闻！');
			exit;
		}
	}
	public function News_add(){
		$this->display('News_add');
	}
	public function News_add_save(){
		$User = M ('form');
		$vo = $User->create();
		$data = array();

		$content = stripslashes($_POST['content']);
		$title = $_POST['title'];
		$addtime = $_POST['addtime'];
		$ttime = strtotime($addtime);
		if($ttime==0){
			$ttime = mktime();
		}
		if(empty($title) or empty($content)){
			$this->error('请输入完整的信息！');
		}
		//dump($_POST['select']);exit;
		$data['title'] = $title;
		$data['content'] = $content;
		$data['user_id'] = $_POST['user_id'];
		$data['create_time'] = $ttime;
		$data['status'] = 1;
		$data['type'] = $_POST['type'];

		if (!$vo){
			$this->error('添加新闻1！');
			exit;
		}
		$rs = $User->add($data);
		if (!$rs){
			$this->error('添加新闻2');
			exit;
		}
		$bUrl = __URL__.'/index';
		$this->_box(0,'添加新闻！',$bUrl,1);
		exit;
	}
	//启用
	private function News_2($PTid=0){
		//if ($_SESSION['UrlPTPass1']=='ss'){
			$User = M ('form');
			$where['id'] = array ('in',$PTid);
			$User->where($where)->setField('status',1);
			//$rs = $User->query("update `think_form` set `status`=1 where ID in ($PTid)");
			$bUrl = __URL__.'/index';
			$this->_box('操作成功','启用！',$bUrl,1);
			exit;
		//}else{
		//	$this->error('错误！');
		//	exit;
		//}
	}
	//禁用
	private function News_3($PTid=0){
		//if ($_SESSION['UrlPTPass1']=='ss'){
			$User = M ('form');
			$where['id'] = array ('in',$PTid);
			$User->where($where)->setField('status',0);
			//$rs = $User->query("update `think_form` set `status`=0 where ID in ($PTid)");
			$bUrl = __URL__.'/index';
			$this->_box(1,'禁用！',$bUrl,1);
			exit;
		//}else{
		//	$this->error('错误！');
		//	exit;
		//}
	}
	private function News_4($PTid=0){
		//if ($_SESSION['UrlPTPass1']=='ss'){
			$User = M ('form');
			//$where['isPay'] = 0;
			$where['id'] = array ('in',$PTid);
			$rs = $User->where($where)->delete();
			if ($rs){
				$bUrl = __URL__.'/index';
				$this->_box(1,'删除！',$bUrl,1);
				exit;
			}else{
				$bUrl = __URL__.'/index';
				$this->_box(0,'删除！',$bUrl,1);
				exit;
			}
		//}else{
		//	$this->error('错误!');
		//}
	}
	//置顶
	private function News_Top($PTid=0){
			$User = M ('form');
			$where['id'] = array ('in',$PTid);
			$User->where($where)->setField('baile',1);
			$bUrl = __URL__.'/index';
			$this->_box(1,'公告置顶成功！',$bUrl,1);
			exit;
	}
	//取消置顶
	private function News_NoTop($PTid=0){
			$User = M ('form');
			$where['id'] = array ('in',$PTid);
			$User->where($where)->setField('baile',0);
			$bUrl = __URL__.'/index';
			$this->_box(1,'公告取消置顶成功！',$bUrl,1);
			exit;
	}

	public function News_5(){
		$User = M ('form');
		$vo = $User->create();
		$data = array();
		//h 函数转换成安全html
		$content = $_POST['content'];
		$title = $_POST['title'];
		$type = $_POST['type'];
		$addtime = $_POST['addtime'];
		$ttime = strtotime($addtime);
		if($ttime==0){
			$ttime = mktime();
		}
		$data['title'] = $title;
		$data['type'] =$type;
		$data['content'] = $content;
		//$data['user_id'] = $_POST['user_id'];
		$data['create_time'] = $ttime;
		$data['update_time'] = mktime();
		$data['status'] = 1;
		$data['id'] = $_POST['ID'];

		//dump($data);
		//exit;
		if (!$vo){
			$this->error('编辑失败！');
			exit;
		}
		$rs = $User->save($data);
		if (!$rs){
			$this->error('编辑失败！');
			exit;
		}
		$bUrl = __URL__.'/index';
		$this->_box(1,'编辑新闻！',$bUrl,1);
		exit;
	}

	public function News_Class(){

	}

}
?>