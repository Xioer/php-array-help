<?php
/**
 * 数组格式化帮助服务
 * @author lidy 2021年6月18日10:20:28
 * 
 */
class ArrayHelpService 
{
    /**
     * 通过数组下标获取一个无下标的简单一维数组
     * @param array $ary
     * @param string $key
     * @return array
     * @author lidy 2021年6月18日10:25:51
     */
    public static function getSimpleArray($ary, $key) {
        $data = array();
        foreach ($ary as $k => $val) {
            $data[] = $val[$key];
        }
        return $data;
    }

    /**
     * 查询某个外键的信息
     *
     * @param [type] $list
     * @param [type] $key
     * @param [type] $dataService
     * @return array
     * @author lidy 2021年6月18日10:25:43
     */
    public static function getValueList($dataService,$list,$key,$foreign_key='id'){
        $id_arr = self::getSimpleArray($list,$key);
        $l = $dataService->getAll($id_arr);
        $l = array_column($l,null,$foreign_key);
        return $l;
    }

    /**
     * 获取数组中某个值
     *
     * @param array $arr 数组
     * @param string $key key值
     * @param string $index 索引值
     * @return string 如果存在则返回值，否则返回默认值
     * @author lidy 2021年6月18日10:25:20
     */
    public static function getValueByKey($arr,$index,$key,$default=""){
        $str_value = $default;
        if(isset($arr[$index])){
            $str_value = $arr[$index][$key];
        }
        return $str_value;
    }

    /**
     * 将时间戳字段转换成日期格式
     * @param array $data
     * @param string $key
     * @param string $format
     * @return array
     * @author lidy 2021年6月18日10:25:02
     */
    public static function filterDatetime($data, $key, $format = 'Y-m-d H:i:s') {
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                $data[$k][$key] = date($format, $data[$k][$key]);
            }
        }
        return $data;
    }


    /**
     * 过滤一维数组中值是非数字或值为0的项
     *
     * @param array $ary
     * @return bool|array
     * @author lidy 2021年6月18日10:24:48
     */
    public static function filterNumber($ary) {

        if (!is_array($ary) || empty($ary)) {
            return false;
        }
        $_ary = array();
        foreach ($ary as $k => $val) {
            $val = intval($val);
            if ($val > 0) {
                $_ary[] = $val;
            }
        }
        if (empty($_ary)) {
            return false;
        }
        return $_ary;
    }

    /**
     * getCol 获取二维数组中指定的列
     *
     * @param  array  $data    必须为二维数组
     * @param  string $keyWord 所要列的键名
     * @param  string $key     列键名
     * @return array
     * @author lidy 2021年6月18日10:24:37
     */
    public static function getCol($data, $keyword, $key = null) {
        if (!is_array($data)) {
            return false;
        }
        $result = array();
        if ($key && is_string($key)) {
            foreach ($data as $value) {
                $result[$value[$key]] = $value[$keyword];
            }
        } else {
            foreach ($data as $value) {
                $result[] = $value[$keyword];
            }
        }
        return $result;
    }

    /**
     * rebuildByCol
     * 根据某个字段把该字段的值当数组的KEY重组数组
     * 例如 $a = array(
     *                 array('uId' => '1', 'data' => 'test'),
     *                 array('uId' => '2', 'data' => 'test2')
     *                )
     * Util_Array::rebuildByCol($a, 'uId');
     * array(
     *       '1' => array('uId' => '1', 'data' => 'test'),
     *       '2' => array('uId' => '2', 'data' => 'test2')
     *      )
     *
     * @param  array $data 二维数组
     * @param  string $keyword 字段名
     * @return array
     * @author lidy 2021年6月18日10:24:28
     */
    public static function rebuildByCol($data, $keyword) {

        // 无数据原样返回
        if (!$data) {
            return $data;
        }

        $result = array();

        foreach ($data as $value) {

            if (is_object($value)) {
                $result[$value->$keyword] = $value;
            } else {
                $result[$value[$keyword]] = $value;
            }

        }

        return $result;
    }

    /**
     * rebuildMultiByCol
     *  对rebuildByCol 的增强版，会根据keyword生成一个二维数组, 一对多关系
     * 例如 $a = array(
     *                 array('uId' => '1', 'data' => 'test'),
     *                 array('uId' => '2', 'data' => 'test2')
     *                array('uId' => 1, 'data' => 'test2')
     *                )
     * Util_Array::rebuildByCol($a, 'uId');
     * array(
     *       '1' => array(array('uId' => '1', 'data' => 'test'), array('uId)=>
     *       '2' => array('uId' => '2', 'data' => 'test2')
     *      )
     *
     * @param  array $data 二维数组
     * @param  string $keyword 字段名
     * @return array
     * @author lidy 2021年6月18日10:24:20
     */
    public static function rebuildMultiByCol($data, $keyword, $secKey = null) {

        if (empty($data) || !is_array($data)) {
            return $data;
        }

        $result = array();
        if (isset($secKey)) {
            foreach ($data as $value) {
                $result[$value[$keyword]][$value[$secKey]] = $value;
            }
        } else {
            foreach ($data as $value) {
                $result[$value[$keyword]][] = $value;
            }
        }

        return $result;
    }

    /**
     * 二维数据左连接
     *
     * @param array $left_array 左数组
     * @param array $right_array 右数组
     * @param string $left_field 左数组要连接的字段名
     * @param string $right_field 右数组要连接的字段名
     * @return array
     * @author lidy 2018年7月17日18:35:39
     */
    public static function left_join_array(array $left_array, array $right_array, $left_field, $right_field = NULL) {
        $result = array();
        //右数组要连接的字段名为空情况
        if (empty($right_field)) {
            $right_field = $left_field;
        }
        foreach ($left_array as $left_key => $left_value) {

            foreach ($right_array as $right_value) {
                if ($left_value[$left_field] == $right_value[$right_field]) {
                    $result[$left_key] = array_merge($right_value, $left_value);
                    break;
                }
            }

            if ($left_value[$left_field] !== $right_value[$right_field]) {
                $result[$left_key] = $left_value;
                foreach ($right_value as $right_value_key => $right_value_val) {
                    //将左数组没有的字段置空
                    if (!isset($result[$left_key][$right_value_key])) {
                        $result[$left_key][$right_value_key] = null;
                    }
                }
                unset($right_value_key, $right_value_val);
            }
            unset($right_value);
        }
        return $result;
    }

    /**
     * 根据二维数组某个字段的值查找数组
     * @author lidy 2021年6月18日10:23:58
     * @param array $array
     * @param string $index
     * @param string $value
     * @return array
     */
    public static function find_by_value($array, $index, $value) {
    
        $newarray = array();
        if (is_array($array) && count($array) > 0) {
            foreach (array_keys($array) as $key) {
                $temp[$key] = $array[$key][$index];

                if ($temp[$key] == $value) {
                    array_push($newarray,$temp[$key]);
                }
            }
        }
 
        return $newarray;
    }


    /**
     * 重新排序数组
     * @param $array 需要排序的数组
     * @param $keys 需要根据某个key排序
     * @param string $sort 倒叙还是顺序
     * @return array
     * @author lidy 2018年8月6日16:51:39
     */
    public static function arraySort($array,$keys,$sort='asc') {
        $newArr = $valArr = array();
        foreach ($array as $key=>$value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ?  asort($valArr) : arsort($valArr);//先利用keys对数组排序，目的是把目标数组的key排好序
        reset($valArr); //指针指向数组第一个值
        foreach($valArr as $key=>$value) {
            $newArr[] = $array[$key];
        }
        return $newArr;
    }

    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     * @return array
     * @author lidy 2018年8月10日16:39:42
     */
    public function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc':// 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return false;
    }


    /**
     * 二维数组遍历获取指定字段
     * @param $array array 二维数组
     * @param $keys array 数组key
     * @param array $default 默认返回值
     * @return mixed
     * @author lidy 2018年8月10日18:11:25
     *
     * begin
     * $list = array(
     *     array('id'=>1,'name'=>'a','age'=>10),
     *     array('id'=>2,'name'=>'a','age'=>10),
     *     array('id'=>3,'name'=>'a','age'=>10),
     *     array('id'=>4,'name'=>'a','age'=>10),
     * )
     *
     * 使用方式 Ap_Util_Array::getValues($list,['id','name'])
     * $list = array(
     *     array('id'=>1,'name'=>'a'),
     *     array('id'=>2,'name'=>'a'),
     *     array('id'=>3,'name'=>'a'),
     *     array('id'=>4,'name'=>'a'),
     * )
     *
     *
     *
     */
    public static function getValues($array, $keys = array(), $default = array())
    {

        if (is_array($keys)) {
            $return = array();
            foreach ($array as $k => $v) {
                foreach ($keys as $key) {
                    $return[$k][$key] = self::getValue($v, $key, $default);
                }
            }
            return $return;
        } else {
            return self::getValue($array, $keys, $default);
        }

    }

    /**
     * 获取指定列值 - 不可单独使用 - 使用上面 getValues 方法调用
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    private static function getValue($array, $key, $default = null)
    {
        if ($key instanceof \Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (($pos = strrpos($key, '.')) !== false) {
            $array = static::getValue($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (is_object($array)) {
            return $array->$key;
        } elseif (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        } else {
            return $default;
        }
    }


    /**
     * 二维数组转一维
     * @param $array
     * @return array
     * @author lidy 2021年6月18日10:35:42
     */
    public static function twoArrayToOneArray($array)
    {
        $callback_data = [];
        foreach ($array as $row){
            if(empty($row)){
                continue;
            }
            foreach ($row as $one_array){
                $callback_data[] = $one_array;
            }
        }
        return $callback_data;
    }


    /**
     * 格式化数据
     * [
     *      [$field_key] => [$fields] 示例： [id] => ['id','title','status']
     * ]
     * @param array $data 原始数据
     * @param array $fields 需要取出的字段
     * @param string $field_key 设置为数组key的字段
     * @return array
     * @author lidy 2019年6月5日19:33:01
     */
    public static function formatDataWithKeyValue($data = [], $fields = [], $field_key = '')
    {
        $result = [];
        if (!$data) {
            return $result;
        }
        if (!is_array($data)) {
            $data = array($data);
        }
        if (empty($fields)) {
            foreach ($data as $key => $value) {
                if (!empty($field_key) && !empty($value[$field_key])) {
                    $result[$value[$field_key]] = $value;
                } else {
                    $result[] = $value;
                }
            }
        } else {
            $tmp = [];
            foreach ($data as $key => $value) {
                foreach ($fields as $single) {
                    //如果key值存在， 返回 key => fields 数组
                    if (!empty($field_key) && !empty($value[$field_key])) {
                        $tmp[$value[$field_key]][$single] = !empty($value[$single]) ? $value[$single] : '';
                    } else {
                        //key值不存在，返回fields
                        $tmp[$single] = !empty($value[$single]) ? $value[$single] : '';
                    }
                }
                if(!empty($field_key) && !empty($value[$field_key])){
                    $result = $tmp;
                }else{
                    $result[] = $tmp;
                }
            }
        }

        return $result;
    }

    /**
     * 数组分组
     * @param $dataArr 需要分组的数据
     * @param $keyStr 指定分组字段
     * @return array
     * @author lidy 2019年7月12日17:07:58
     */
    public static function dataGroup($dataArr, $keyStr)
    {
        $newArr = [];
        foreach ($dataArr as $k => $val) {
            $newArr[$val[$keyStr]][] = $val;
        }
        return $newArr;
    }

    /**
     * 删除数组里面指定的key以及key对应的数据 - 支持一维二维数组
     * @param Array $data 要查找的数组
     * @param String $filed 要去除的key字段 - 例如：status,id,uid,create_time,update_time
     * @return Array
     * @author lidy 2019年12月10日20:01:21
     */
    public static function unsetArrayFindKey($data, $filed = '')
    {
        if(empty($filed)){
            return $data;
        }
        $fileds = explode(',',$filed);
        foreach($data as $key => &$val){
            if(is_integer($key)){
                foreach($val as $k => $v){
                    if(in_array($k,$fileds)){
                        unset($val[$k]);
                    }
                }
            }else{
                if(in_array($key,$fileds)){
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }

    /**
     * 删除数组中为空的值
     * @param $data - 需要处理的数据
     * @author lidy 2020年2月28日15:00:13
     * @return Array
     */
    public static function unsetArrayEmpty($data)
    {
        if(empty($data)){
            return [];
        }
        foreach($data as $key => $val){
            if(empty($val)){
                unset($data[$key]);
            }
        }
        return array_merge($data,[]);
    }


    /**
     * 对象转换数组
     * 
     * @param object stdclassobject 需要转换为数组的对象
     * @return array 
     * @author lidy 2020年6月17日17:51:16
     */
    public static function stdClassObjectToArray($stdclassobject)
    {
        $array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        foreach ($array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? self::stdClassObjectToArray($value) : $value;
            $array[$key] = $value;
        }
        return $array;
    }
}