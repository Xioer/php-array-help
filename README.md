# PHP-ARRAY-HELP

#### 介绍
php数组处理（通过格式化数据以达到想要的数据格式）。
全是php数组操作，可用作日常开发工具。

#### 软件架构
软件架构说明


#### 安装教程
git clone https://gitee.com/githubli/php-array-help.git

#### 使用说明
1、可以直接在文件当中复制方法进行使用；
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