<?php

// .-----------------------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |   Version: 2013.01
// |      Site: http://www.hdphp.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://houdunwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------

/**
 * Mysql数据库基类
 * @package     Db
 * @subpackage  Driver
 * @author      后盾向军 <houdunwangxj@gmail.com>
 */
abstract class Db implements DbInterface
{

    protected $table = NULL; //表名
    public $fieldArr; //字段数组
    public $lastQuery; //最后发送的查询结果集
    public $pri = null; //默认表主键
    public $opt = array(); //SQL 操作
    public $opt_old = array(); //上次操作参数
    public $lastSql; //最后发送的SQL
    public $error = NULL; //错误信息
    protected $cacheTime = NULL; //查询操作缓存时间单位秒
    protected $dbPrefix; //表前缀

    /**
     * 将eq等替换为标准的SQL语法
     * @var  array
     */
    protected $condition = array(
        "eq" => " = ", "neq" => " <> ",
        "gt" => " > ", "egt" => " >= ",
        "lt" => " < ", "elt" => " <= ",
    );

    /**
     * 数据库连接
     * 根据配置文件获得数据库连接对象
     * @param string $table
     * @return Object   连接对象
     */
    public function connect($table)
    {
        //通过数据驱动如MYSQLI连接数据库
        if ($this->connectDb()) {
            if (!is_null($table)) {
                $this->dbPrefix = C("DB_PREFIX");
                $this->table($table);
                $this->table = $table;
                $this->pri = $this->opt['pri'];
                $this->fieldArr = $this->opt['fieldArr'];
                $this->optInit(); //初始始化WHERE等参数
            } else {
                $this->optInit();
            }
            return $this->link;
        }
        halt("数据库连接出错了请检查数据库配置");
    }

    /**
     * 初始化表字段与主键及发送字符集
     * @param string $tableName 表名
     */
    public function table($tableName)
    {
        if (is_null($tableName)) return;
        $this->optInit();
        $field = $this->getFields($tableName); //获得表结构信息设置字段及主键属性
        $this->opt['table'] = $tableName;
        $this->opt['pri'] = isset($field['pri']) && !empty($field['pri']) ? $field['pri'] : '';
        $this->opt['fieldArr'] = $field['field'];
    }

    /**
     * 查询操作归位
     * @access public
     * @return void
     */
    public function optInit()
    {
        $this->opt_old = $this->opt;
        $this->cacheTime = NULL; //SELECT查询缓存时间
        $this->error = NULL;
        $opt = array(
            'table' => $this->table,
            'pri' => $this->pri,
            'field' => '*',
            'fieldArr' => $this->fieldArr,
            'where' => '',
            'like' => '',
            'group' => '',
            'having' => '',
            'order' => '',
            'limit' => '',
            'in' => '',
            'cache' => '',
            'filter_func' => array() //对数据进行过滤处理
        );
        $this->opt = array_merge($this->opt, $opt);
    }

    /**
     * 获得表字段
     * @access public
     * @param string $tableName 表名
     * @return type
     */
    public function getFields($tableName)
    {
        $tableCache = $this->getCacheTable($tableName);
        $tableField = array();
        foreach ($tableCache as $v) {
            $tableField['field'][] = $v['field'];
            if ($v['key']) {
                $tableField['pri'] = $v['field'];
            }
        }
        return $tableField;
    }

    /**
     * 获得表结构缓存  如果不存在则生生表结构缓存
     * @access public
     * @param type $tableName
     * @return array    字段数组
     */
    private function getCacheTable($tableName)
    {
        $cacheName = C('DB_DATABASE') . '.' . $tableName;
        //字段缓存
        if (!DEBUG) {
            $cacheTableField = F($cacheName, false, APP_TABLE_PATH);
            if ($cacheTableField)
                return $cacheTableField;
        }
        //获得表结构
        $tableinfo = $this->getTableFields($tableName);
        $fields = $tableinfo['fields'];
        //字段缓存
        if (!DEBUG) {
            F($cacheName, $fields, APP_TABLE_PATH);
        }
        return $fields;
    }

    /**
     * 获得表结构及主键
     * 查询表结构获得所有字段信息，用于字段缓存
     * @access private
     * @param string $tableName
     * @return array
     */
    public function getTableFields($tableName)
    {
        $sql = "show columns from " . $tableName;
        $fields = $this->query($sql);
        if ($fields === false) {
            error("表{$tableName}不存在", false);
        }
        $n_fields = array();
        $f = array();
        foreach ($fields as $res) {
            $f ['field'] = $res ['Field'];
            $f ['type'] = $res ['Type'];
            $f ['null'] = $res ['Null'];
            $f ['field'] = $res ['Field'];
            $f ['key'] = ($res ['Key'] == "PRI" && $res['Extra']) || $res ['Key'] == "PRI";
            $f ['default'] = $res ['Default'];
            $f ['extra'] = $res ['Extra'];
            $n_fields [$res ['Field']] = $f;
        }
        $pri = '';
        foreach ($n_fields as $v) {
            if ($v['key']) {
                $pri = $v['field'];
            }
        }
        $info = array();
        $info['fields'] = $n_fields;
        $info['primarykey'] = $pri;
        return $info;
    }

    /**
     * 将查询SQL压入调试数组 show语句不保存
     * @param void
     */
    protected function debug($sql)
    {
        $this->lastSql = $sql;
        if (DEBUG && !preg_match("/^\s*show/i", $sql)) {
            Debug::$sqlExeArr[] = $sql; //压入一条成功发送SQL
        }
    }

    //错误处理
    protected function error($error)
    {
        $this->error = $error;
        if (DEBUG) {
            error($this->error);
        } else {
            log_write($this->error);
        }
    }

    /**
     * 查找记录
     * @param string $where
     * @return array|string
     */
    public function select($where)
    {
        if (empty($this->opt['table'])) {
            $this->error("没有可操作的数据表");
            return false;
        }
        //设置条件
        if (!empty($where)) {
            $this->where($where);
        }
        //去除WHERE尾部AND OR
        $this->removeWhereLogic();
        //连接SQL
        $sql = 'SELECT ' . $this->opt['field'] . ' FROM ' . $this->opt['table'] .
            $this->opt['where'] . $this->opt['group'] . $this->opt['having'] .
            $this->opt['order'] . $this->opt['limit'];
        return $this->query($sql);
    }

    /**
     * SQL中的REPLACE方法，如果存在与插入记录相同的主键或unique字段进行更新操作
     * @param array $data
     * @param string $type
     * @return array|bool
     */
    public function insert($data, $type = 'INSERT')
    {
        $value = $this->formatField($data);
        if (empty($value)) {
            $this->error("没有任何数据用于 INSERT");
            return false;
        } else {
            $sql = $type . " INTO " . $this->opt['table'] . "(" . implode(',', $value['fields']) . ")" .
                "VALUES (" . implode(',', $value['values']) . ")";
            return $this->exe($sql);
        }
    }

    /**
     * 格式化SQL操作参数 字段加上标识符  值进行转义处理
     * @param array $vars 处理的数据
     * @return array
     */
    public function formatField($vars)
    {
        //格式化的数据
        $data = array();
        foreach ($vars as $k => $v) {
            //校验字段与数据
            if (!$this->isField($k) || is_array($v)) {
                continue;
            }
            $data['fields'][] = "`" . $k . "`";
            $v = $this->escapeString($v);
            $data['values'][] = "\"" . $v . "\"";
        }
        return $data;
    }

    /**
     * 更新数据
     * @access      public
     * @param  mixed $data
     * @return mixed
     */
    public function update($data)
    {
        //验证条件
        if (empty($this->opt['where'])) {
            if (isset($data[$this->opt['pri']])) {
                $this->opt['where'] = " WHERE " . $this->opt['pri'] . " = " . intval($data[$this->opt['pri']]);
            } else {
                $this->error('UPDATE更新语句必须输入条件');
                return false;
            }
        }
        $data = $this->formatField($data);
        if (empty($data)) return false;
        $sql = "UPDATE " . $this->opt['table'] . " SET ";
        foreach ($data['fields'] as $n => $field) {
            $sql .= $field . "=" . $data['values'][$n] . ',';
        }
        //移除WHERE AND OR
        $this->removeWhereLogic();
        $sql = trim($sql, ',') . $this->opt['where'] . $this->opt['limit'];
        return $this->exe($sql);
    }

    /**
     * 删除方法
     * @param $data
     * @return bool
     */
    public function delete($data = array())
    {
        if (!empty($data)) {
            $this->where($data);
        }
        if (empty($this->opt['where'])) {
            $this->error("DELETE删除语句必须输入条件");
            return false;
        }
        $this->removeWhereLogic();
        $sql = "DELETE FROM " . $this->opt['table'] . $this->opt['where'] . $this->opt['limit'];
        return $this->exe($sql);
    }

    /**
     * 过滤非法字段
     * @param mixed $opt
     * @return array
     */
    public function fieldFilter($opt)
    {
        if (empty($opt) || !is_array($opt))
            return null;
        $field = array();
        foreach ($opt as $k => $v) {
            if ($this->isField($k))
                $field[$k] = $v;
        }
        return $field;
    }

    /**
     * SQL查询条件
     * @param mixed $opt 链式操作中的WHERE参数
     * @return string
     */
    public function where($opt)
    {
        $where = '';
        if (empty($opt)) return;
        if (is_numeric($opt)) {
            $where .= ' ' . $this->opt['pri'] . "=$opt ";
            if (!preg_match('/(OR|AND)\s*/i', $where)) {
                $where .= ' AND ';
            }
        } else if (is_string($opt)) {
            $where .= " $opt ";
            if (!preg_match('/(OR|AND)\s*/i', $where)) {
                $where .= ' AND ';
            }
        } else if (is_array($opt)) {
            foreach ($opt as $field => $set) {
                //过滤字段
                if ($this->isField($field)) {
                    $field = " $field ";
                    if (is_string($set)) {
                        $logic = isset($opt['_logic']) ? " {$opt['_logic']} " : ' AND '; //连接方式
                        $where .= $field . "='$set' " . $logic;
                    } else if(is_array($set)){
                        $type = str_replace(' ', '', $set[0]); //类型
                        $option = is_string($set[1]) ? explode(',', $set[1]) : $set[1]; //选项
                        //连接方式
                        if (isset($opt['_logic'])) {
                            $logic = " {$opt['_logic']} ";
                        } else {
                            $logic = isset($set[2]) ? " {$set[2]} " : ' AND ';
                        }
                        switch (strtoupper($type)) {
                            case 'IN':
                                $value = '';
                                foreach ($option as $v) {
                                    $value .= is_numeric($v) ? $v . "," : "'" . $v . "',";
                                }
                                $value = trim($value, ',');
                                $where .= $field . " IN ($value) $logic";
                                break;
                            case 'NOTIN':
                                $value = '';
                                foreach ($option as $v) {
                                    $value .= is_numeric($v) ? $v . "," : "'" . $v . "',";
                                }
                                $value = trim($value, ',');
                                $where .= $field . " NOT IN ($value) $logic";
                                break;
                            case 'BETWEEN':
                                $where .= $field . " BETWEEN " . $option[0] . ' AND ' . $option[1] . $logic;
                                break;
                            case 'NOTBETWEEN':
                                $where .= $field . " NOT BETWEEN " . $option[0] . ' AND ' . $option[1] . $logic;
                                break;
                            case 'LIKE':
                                foreach ($option as $v) {
                                    $where .= $field . " LIKE '$v' " . $logic;
                                }
                                break;
                            case 'NOLIKE':
                                foreach ($option as $v) {
                                    $where .= $field . " NO LIKE '$v'" . $logic;
                                }
                                break;
                            case 'EQ':
                                $where .= $field . '=' . (is_numeric($set[1]) ? $set[1] : "'{$set[1]}'") . $logic;
                                break;
                            case 'NEQ':
                                $where .= $field . '<>' . (is_numeric($set[1]) ? $set[1] : "'{$set[1]}'") . $logic;
                                break;
                            case 'GT':
                                $where .= $field . '>' . (is_numeric($set[1]) ? $set[1] : "'{$set[1]}'") . $logic;
                                break;
                            case 'EGT':
                                $where .= $field . '>=' . (is_numeric($set[1]) ? $set[1] : "'{$set[1]}'") . $logic;
                                break;
                            case 'LT':
                                $where .= $field . '<' . (is_numeric($set[1]) ? $set[1] : "'{$set[1]}'") . $logic;
                                break;
                            case 'ELT':
                                $where .= $field . '<=' . (is_numeric($set[1]) ? $set[1] : "'{$set[1]}'") . $logic;
                                break;
                            case 'EXP':
                                $where .= $field . $set[1] . $logic;
                                break;
                        }

                    }
                } else if (is_numeric($field) && is_string($set)) {
                    $where .= $set;
                    if(!preg_match('/(OR|AND)\s*/i',$where)){
                        $where.=' AND ';
                    }
                }
            }
        }
        if (empty($this->opt['where']) && !empty($where)) {
            $this->opt['where'] = ' WHERE ';
        }
        $this->opt['where'] .= $where;
    }

    //移除where后的AND OR
    private function removeWhereLogic()
    {
        $this->opt['where'] = preg_replace('/(AND|OR)\s*$/i', '', $this->opt['where']);
    }

    /**
     * 字段集
     * @param mixed $data
     * @param boolean $exclude 排除字段
     */
    public function field($data, $exclude = false)
    {
        //字符串时转为数组
        if (is_string($data)) {
            $data = explode(",", $data);
        }
        //排除字段
        if ($exclude) {
            $_data = $data;
            $data = $this->fieldArr;
            foreach ($_data as $name => $field) {
                if (in_array($field, $this->fieldArr)) {
                    unset($data[$name]);
                }
            }
        }
        $field = trim($this->opt['field']) == '*' ? '' : $this->opt['field'] . ',';
        foreach ($data as $name => $d) {
            if (is_string($name)) {
                $field .= $name . ' AS ' . $d . ",";
            } else {
                $field .= $d . ',';
            }
        }
        $this->opt['field'] = substr($field, 0, -1);
    }

    /**
     * 验证字段是否全法
     * @param $field 字段名
     * @return bool
     */
    protected function isField($field)
    {
        return is_string($field) && in_array($field, $this->opt['fieldArr']);
    }

    /**
     * limit 操作
     * @param mixed $data
     * @return type
     */
    public function limit($data)
    {
        $this->opt['limit'] = " LIMIT $data ";
    }

    /**
     * SQL 排序 ORDER
     * @param type $data
     */
    public function order($data)
    {
        $this->opt['order'] = " ORDER BY $data ";
    }

    /**
     * 分组操作
     * @param type $opt
     */
    public function group($opt)
    {
        $this->opt['group'] = " GROUP BY $opt";
    }

    /**
     * 分组条件having
     * @param type $opt
     */
    public function having($opt)
    {
        $this->opt['having'] = " HAVING $opt";
    }

    /**
     * 设置查询缓存时间
     * @param $time
     */
    public function cache($time = -1)
    {
        $this->cacheTime = is_numeric($time) ? $time : -1;
    }

    /**
     * 判断表名是否存在
     * @param $table 表名
     * @param bool $full 是否加表前缀
     * @return bool
     */
    public function isTable($table, $full = true)
    {
        //不为全表名时加表前缀
        if (!$full)
            $table = C('DB_PREFIX') . $table;
        $table = strtolower($table);
        $info = $this->query('show tables');
        foreach ($info as $n => $d) {
            if ($table == current($d)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获得最后一条SQL
     * @return type
     */
    public function getLastSql()
    {
        return $this->lastSql;
    }

    /**
     * 获得所有SQL语句
     * @return type
     */
    public function getAllSql()
    {
        return Debug::$sqlExeArr;
    }

    /**
     * 获得表信息
     * @param   string $table 数据库名
     * @return  array
     */
    public function getTableInfo($table)
    {
        $table = empty($table) ? null : $table; //表名
        $info = $this->query("SHOW TABLE STATUS FROM " . C("DB_DATABASE"));
        $arr = array();
        $arr['total_size'] = 0; //总大小
        $arr['total_row'] = 0; //总条数
        foreach ($info as $k => $t) {
            if ($table) {
                if (!in_array($t['Name'], $table)) {
                    continue;
                }
            }
            $arr['table'][$t['Name']]['tablename'] = $t['Name'];
            $arr['table'][$t['Name']]['engine'] = $t['Engine'];
            $arr['table'][$t['Name']]['rows'] = $t['Rows'];
            $arr['table'][$t['Name']]['collation'] = $t['Collation'];
            $charset = $arr['table'][$t['Name']]['collation'] = $t['Collation'];
            $charset = explode("_", $charset);
            $arr['table'][$t['Name']]['charset'] = $charset[0];
            $arr['table'][$t['Name']]['datafree'] = $t['Data_free'];
            $arr['table'][$t['Name']]['size'] = $t['Data_free'] + $t['Data_length'];
            $info = $this->getTableFields($t['Name']);
            $arr['table'][$t['Name']]['field'] = $info['fields'];
            $arr['table'][$t['Name']]['primarykey'] = $info['primarykey'];
            $arr['table'][$t['Name']]['autoincrement'] = $t['Auto_increment'] ? $t['Auto_increment'] : '';
            $arr['total_size'] += $arr['table'][$t['Name']]['size'];
            $arr['total_row']++;
        }
        return empty($arr) ? false : $arr;
    }

    /**
     * 获得数据库或表大小
     */
    public function getSize($table)
    {
        $sql = "show table status from " . C("DB_DATABASE");
        $row = $this->query($sql);
        $size = 0;
        foreach ($row as $v) {
            if ($table) {
                $size += in_array(strtolower($v['Name']), $table) ? $v['Data_length'] + $v['Index_length'] : 0;
            }
        }
        return get_size($size);
    }
}