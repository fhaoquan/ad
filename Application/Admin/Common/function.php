<?php
/**
 * Created by PhpStorm.
 * User: 亚东
 * Date: 2016/3/11
 * Time: 17:36
 */

/**
 * 清空目录
 * @param $dir 目录名
 * @return bool 如果出错返回false，成功返回true
 */
function cleardir($dir) {
    //先删除目录下的文件：
    try{
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    cleardir($fullpath);
                }
            }
        }
        closedir($dh);
    }catch (\Think\Exception $e){
        return false;
    }
    return true;
}

/**
 * 备份数据表
 * @param $table 备份表名
 * @param $dir 目标目录名
 * @param $conf 数据库配置
 * @param bool $is_drop 选择是否增加删除数据表的语句，默认为true
 * @return bool 如果出错返回false，成功返回true
 */
function dump_table($table,$dir,$conf,$is_drop=true)
{
    $need_close = false;
    if($conf != null){
        //连接数据库
        $con = mysql_connect($conf['DB_HOST'], $conf['DB_USER'], $conf['DB_PWD']);
        if(!$con){
            return false;
        }
        mysql_query("set names 'utf8'", $con);
        mysql_select_db($conf['DB_NAME'],$con);
    }
    try{
        $a=mysql_query("show create table `{$table}`", $con);//显示创建mysql数据的的语句结构。
        $row=mysql_fetch_assoc($a);//导出表结构
        $rs = mysql_query("SELECT * FROM `{$table}`", $con);

        $dom = new DOMDocument("1.0","utf-8");   //创建xml对象
        $dom->formatOutput = true;

        $root = $dom->createElement("xmls");    //创建xmls节点
        $dom->appendChild($root);               //将xmls节点加入xml对象

        if($is_drop){
            $drop = "DROP TABLE IF EXISTS `{$table}`";
            $droptableNode = $dom->createElement("droptable");     //创建droptable节点
            $droptableValue = $dom->createAttribute("value");  //创建value属性
            $droptableNode->appendChild($droptableValue);          //将value属性加入droptable节点
            $droptableNode->setAttribute("value",$drop);  //设置droptable节点中value属性的值
            $root->appendChild($droptableNode);
        }

        $tableNode = $dom->createElement("table");     //创建table节点
        $tableValue = $dom->createAttribute("value");  //创建value属性
        $tableNode->appendChild($tableValue);          //将value属性加入table节点
        $tableNode->setAttribute("value",$row['Create Table']);  //设置table节点中value属性的值
        $root->appendChild($tableNode);                //将table节点加入xmls节点，意思就是成为xmls的子节点

        while ($row = mysql_fetch_row($rs)) {           //如上，每有一行数据就创建一个row节点，每个row节点都有一个value属性，
            $insert = $dom->createElement("row");       //他的值就是insert语句，每个row节点都成为xmls的子节点
            $in = $dom->createAttribute("value");
            $insert->appendChild($in);
            $insert->setAttribute("value",get_insert_sql($table, $row));
            $root->appendChild($insert);
        }
        $dom->save($dir."/{$table}.xml");       //最后保存xml文件，括号内是保存的路径
        mysql_free_result($rs);//释放内存
    }catch (\Think\Exception $e){
        return false;
    }
    if($con != null){
        //关闭数据库连接
        mysql_close($con);
    }
    return true;
}

/**
 * 将表中每一行拼接成sql语句
 * @param $table 数据表名
 * @param $row 该行字段数据集合
 * @return string 生成的insert语句
 */
function get_insert_sql($table, $row)
{
    $sql = "INSERT INTO `{$table}` VALUES (";
    $values = array();
    foreach ($row as $value) {
        $values[] = "'" . mysql_real_escape_string($value) . "'";
    }
    $sql .= implode(', ', $values) . ");";
    return $sql;
}

/**
 * 还原数据表
 * @param $dir 还原目录名
 * @param $conf 数据库配置
 * @return bool 如果出错返回false，成功返回true
 */
function restore_db($dir, $conf){
    $current_dir = opendir($dir);    //opendir()返回一个目录句柄,失败返回false
    while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
        $sub_dir = $dir . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
        if($file == '.' || $file == '..') {
            continue;
        } else if(is_dir($sub_dir)) {    //如果是目录,则忽略
            //echo 'Directory ' . $file . ':<br>';
            continue;
        } else {    //如果是文件,直接输出
            if('xml'==pathinfo($file, PATHINFO_EXTENSION) ) {
                if(!import_table($dir.'/'.$file, $conf))
                    return false;
            }
        }
    }
    return true;
}

/**
 * 导入数据表
 * @param $xmlPath 备份的数据表xml文件路径
 * @param $conf 数据库配置
 * @return bool 如果出错返回false，成功返回true
 */
function import_table($xmlPath, $conf){
    if($conf != null){
        //连接数据库
        $con = mysql_connect($conf['DB_HOST'], $conf['DB_USER'], $conf['DB_PWD']);
        if(!$con){
            return false;
        }
        mysql_query("set names 'utf8'", $con);
        mysql_select_db($conf['DB_NAME'],$con);
    }
    try{
        $xml = new DOMDocument();//创建xml对象
        $xml->load($xmlPath);//根据括号内路径载入文件

        $droptables = $xml->getElementsByTagName("droptable");//取得节点名称为droptable的节点集合
        $SqlDroptable = "";             //新建删除表的sql语句
        foreach($droptables as $droptable){       //遍历名称为droptable的节点集合
            $SqlDroptable = $droptable->getAttribute("value");//取得每个droptable节点中value属性的值，并拼接到sql语句上
        }
        $res = mysql_query($SqlDroptable, $con); //运行删除表的sql语句

        $tables = $xml->getElementsByTagName("table");//取得节点名称为table的节点集合
        $SqlCreateTable = "";             //新建创建表的sql语句
        foreach($tables as $table){       //遍历名称为table的节点集合
            $SqlCreateTable = $table->getAttribute("value");//取得每个table节点中value属性的值，并拼接到sql语句上
        }
        $res = mysql_query($SqlCreateTable, $con); //运行创建表的sql语句

        $rows = $xml->getElementsByTagName("row");//取得节点名称为row的节点集合
        $SqlInsert = "";        //新建插入数据的sql语句
        foreach($rows as $row){    //遍历名称为row的节点集合
            $SqlInsert=$row->getAttribute("value");     //取得每个row节点中value属性的值，并拼接到sql语句上
            $res=mysql_query($SqlInsert, $con);
        }
    }catch(\Think\Exception $e){
        return false;
    }
    if($con != null){
        //关闭数据库连接
        mysql_close($con);
    }
    return true;
}


///////////////////////////////递归目录//////////////////////////////
//function traverse($path = '.') {
//    $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
//    while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
//        $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
//        if($file == '.' || $file == '..') {
//            continue;
//        } else if(is_dir($sub_dir)) {    //如果是目录,进行递归
//            traverse($sub_dir);
//        } else {    //如果是文件,直接输出
//            if(import_table($path.'/'.$file))
//                var_dump($path.'/'.$file);
//        }
//    }
//}