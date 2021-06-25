# PHP-ARRAY-HELP

#### 介绍
php数组处理（通过格式化数据以达到想要的数据格式）。
全是php数组操作，可用作日常开发工具。

#### 软件架构
php版本：>7.0


#### 安装教程
git clone https://gitee.com/githubli/php-array-help.git

#### 使用说明
1、可以直接在文件当中复制方法进行使用；
>ArrayHelpService::rebuildByCol($a, 'uId');


2、也可以把文件引用进来使用：
```
     * 根据某个字段把该字段的值当数组的KEY重组数组
     * 例如 $a = array(
     *                 array('uId' => '1', 'data' => 'test'),
     *                 array('uId' => '2', 'data' => 'test2')
     *                )
     * ArrayHelpService::rebuildByCol($a, 'uId');
     * 会得到：
     * array(
     *       '1' => array('uId' => '1', 'data' => 'test'),
     *       '2' => array('uId' => '2', 'data' => 'test2')
     *      )
```

| 方法 | 描述 |
|--|--|
| getSimpleArray | 通过数组下标获取一个无下标的简单一维数组 |
| getValueList | 查询某个外键的信息 |
| getValueByKey | 获取数组中某个值 |
| filterDatetime | 将时间戳字段转换成日期格式 |
| filterNumber | 过滤一维数组中值是非数字或值为0的项 |
| getCol | 获取二维数组中指定的列 |
| rebuildByCol | 根据某个字段把该字段的值当数组的KEY重组数组 |
| rebuildMultiByCol | 对rebuildByCol 的增强版，会根据keyword生成一个二维数组, 一对多关系 |
| leftJoinArray | 二维数据左连接 |
| findByBalue | 根据二维数组某个字段的值查找数组 |
| arraySort | 重新排序数组 |
| listSortBy | 对查询结果集进行排序 |
| getValues | 二维数组遍历获取指定字段 |
| twoArrayToOneArray | 二维数组转一维 |
| formatDataWithKeyValue | 格式化数据（具体看函数用法） |
| dataGroup | 数组分组 |
| unsetArrayFindKey | 删除数组里面指定的key以及key对应的数据 - 支持一维二维数组 |
| unsetArrayEmpty | 删除数组中为空的值 |
| stdClassObjectToArray | 对象转换数组 |
