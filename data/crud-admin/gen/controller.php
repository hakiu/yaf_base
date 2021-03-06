<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      __TABLENAMEREMARK__ 管理类
 *      $Id: __TABLENAME__.php __FILETIME__ codejm $
 */

class __TABLENAME__Controller extends \Core_BackendCtl {

    /**
     * __TABLENAMEREMARK__列表
     *
     */
    public function indexAction() {
        // 分页
        $page = intval($this->getg('page', 1));
        $pageSize = 10;
        // 排序
        $orderby = $this->getg('sort');
        if($orderby) {
            $orderby = str_replace('.', ' ', $orderby);
        } else {
            $orderby = '__TABLE_PRIMARYKEY__ asc';
        }

        // 实例化Model
        $__TABLENAMES__ = new __TABLENAME__Model();
        // 查询条件
        $params = array(
            'field' => array(),
            'where' => array('status>'=>0),
            'order' => $orderby,
            'page' => $page,
            'per' => $pageSize,
        );
        // 列表
        $result = $__TABLENAMES__->getLists($params);
        // 数据总条数
        $total = $__TABLENAMES__->getCount($params);

        // 分页url
        $url = Tools_help::url('backend/__TABLENAMES__/index').'?page=';
        $pagestr = Tools_help::pager($page, $total, $pageSize, $url);

        // 模版分配数据
        $this->_view->assign('pagestr', $pagestr);
        $this->_view->assign('result', $result);
        $this->_view->assign("pageTitle", '__TABLENAMEREMARK__列表');
    }

    /**
     * 添加__TABLENAMEREMARK__
     *
     */
    public function addAction() {
        // 实例化Model
        $__TABLENAMES__ = new __TABLENAME__Model();
        // 处理post数据
        if($this->getRequest()->isPost()) {
            // 获取所有post数据
            $pdata = $this->getAllPost();
            // 处理图片等特殊数据
__CONTROLLER_EDIT_PRE__
            // 验证
            $result = $__TABLENAMES__->validation->validate($pdata, 'add');
            $__TABLENAMES__->parseAttributes($pdata);

            // 通过验证
            if($result) {
                // 入库前数据处理
__CONTROLLER_ADD__
                // Model转换成数组
                $data = $__TABLENAMES__->toArray($pdata);
                $result = $__TABLENAMES__->insert($data);
                if($result) {
                    // 提示信息并跳转到列表
                    Tools_help::setSession('Message', '添加成功！');
                    $this->redirect('/backend/__TABLENAMES__/index');
                } else {
                    // 验证失败
                    $this->_view->assign('ErrorMessage', '添加失败！');
                    $this->_view->assign("errors", $__TABLENAMES__->validation->getErrorSummary());
                }
            } else {
                // 验证失败
                $this->_view->assign('ErrorMessage', '添加失败！');
                $this->_view->assign("errors", $__TABLENAMES__->validation->getErrorSummary());
            }
        }

        // 格式化表单数据
__CONTROLLER_ADD_AFTER__

        // 模版分配数据
        $this->_view->assign("__TABLENAMES__", $__TABLENAMES__);
        $this->_view->assign("pageTitle", '添加__TABLENAMEREMARK__');
    }

    /**
     * 编辑__TABLENAMEREMARK__
     *
     */
    public function editAction() {
        // 获取主键
        $__TABLE_PRIMARYKEY__ = $this->getg('__TABLE_PRIMARYKEY__', 0);
        if(empty($__TABLE_PRIMARYKEY__)) {
            $this->error('__TABLE_PRIMARYKEY__ 不能为空!');
        }

        // 实例化Model
        $__TABLENAMES__ = new __TABLENAME__Model();

        // 处理Post
        if($this->getRequest()->isPost()) {
            // 获取所有post数据
            $pdata = $this->getAllPost();
            // 处理图片等特殊数据
__CONTROLLER_EDIT_PRE__
            // 验证
            $result = $__TABLENAMES__->validation->validate($pdata, 'edit');
            $__TABLENAMES__->parseAttributes($pdata);

            // 通过验证
            if($result) {
                // 入库前数据处理
__CONTROLLER_EDIT__

                // Model转换成数组
                $data = $__TABLENAMES__->toArray($pdata);
                $result = $__TABLENAMES__->update(array('__TABLE_PRIMARYKEY__'=>$__TABLE_PRIMARYKEY__), $data);

                if($result) {
                    // 提示信息并跳转到列表
                    Tools_help::setSession('Message', '修改成功！');
                    $this->redirect('/backend/__TABLENAMES__/index');
                } else {
                    // 出错
                    Tools_help::setSession('ErrorMessage', '修改失败, 请确定已修改了某项！');
                    $this->_view->assign("errors", $__TABLENAMES__->validation->getErrorSummary());
                }
                $__TABLENAMES__->__TABLE_PRIMARYKEY__ = $__TABLE_PRIMARYKEY__;
            } else {
                // 验证失败
                Tools_help::setSession('ErrorMessage', '修改失败, 请检查错误项');
                $this->_view->assign("errors", $__TABLENAMES__->validation->getErrorSummary());
            }
        }

        // 如果Model数据为空，则获取
        if(!empty($__TABLE_PRIMARYKEY__) && empty($__TABLENAMES__->__TABLE_PRIMARYKEY__)) {
            $data = $__TABLENAMES__->select(array('where'=>array('__TABLE_PRIMARYKEY__'=>$__TABLE_PRIMARYKEY__)));
            $__TABLENAMES__->parseAttributes($data);
        }

        // 格式化表单数据
__CONTROLLER_EDIT_AFTER__

        // 模版分配数据
        $this->_view->assign("__TABLENAMES__", $__TABLENAMES__);
        $this->_view->assign("pageTitle", '修改__TABLENAMEREMARK__');
    }

    /**
     * 单个__TABLENAMEREMARK__删除
     *
     */
    public function delAction() {
        $__TABLE_PRIMARYKEY__ = $this->getg('__TABLE_PRIMARYKEY__', 0);
        if(empty($__TABLE_PRIMARYKEY__)) {
            $this->error('__TABLE_PRIMARYKEY__ 不能为空!');
        }
        // 实例化Model
        $__TABLENAMES__ = new __TABLENAME__Model();
        $row = $__TABLENAMES__->update(array('__TABLE_PRIMARYKEY__'=>$__TABLE_PRIMARYKEY__), array('status'=>-1));
        if($row) {
            $this->error('恭喜，删除成功', 'Message');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 批量删除__TABLENAMEREMARK__或者调整顺序
     *
     */
    public function batchAction() {
        $__TABLE_PRIMARYKEY__s = $this->getp('dels');
        if(empty($__TABLE_PRIMARYKEY__s)) {
            $this->error('__TABLE_PRIMARYKEY__ 不能为空!');
        }
        // 实例化Model
        $__TABLENAMES__ = new __TABLENAME__Model();
        $row = $__TABLENAMES__->del__TABLENAME__s($__TABLE_PRIMARYKEY__s);
        if($row) {
            $this->error('恭喜，删除成功', 'Message');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 修改__TABLENAMEREMARK__状态
     *
     *
     */
    public function statusAction() {
        $__TABLE_PRIMARYKEY__ = $this->getg('__TABLE_PRIMARYKEY__', 0);
        if(empty($__TABLE_PRIMARYKEY__)) {
            $this->error('__TABLE_PRIMARYKEY__ 不能为空!');
        }
        $status = $this->getg('status', 0);
        $status = $status ? 0 : 1;
        // 实例化Model
        $__TABLENAMES__ = new __TABLENAME__Model();
        $row = $__TABLENAMES__->update(array('__TABLE_PRIMARYKEY__'=>$__TABLE_PRIMARYKEY__), array('status'=>$status));
        if($row) {
            $this->error('恭喜，操作成功', 'Message');
        } else {
            $this->error('操作失败');
        }

    }

}

?>
