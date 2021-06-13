<?php
/**
 * 备份数据库的扩展类
 */

namespace backup;

use app\model\SysBackup;
use exceptions\BaseException;

class Backsql
{
    private $config = [];
    private $handler;
    private $tables = array();//需要备份的表
    private $begin; //开始时间
    private $error;//错误信息

    public function __construct($config)
    {
        $config['connections']['mysql']['path'] = ROOT . '/backup/'; //默认目录
        $config['connections']['mysql']["sqlbakname"] = date("YmdHis", time()) . ".sql";//默认保存文件
        $this->begin = microtime(true);
        $this->config = $config['connections']['mysql'];
        header("Content-type: text/html;charset=utf-8");
        $this->connect();
    }

    //首次进行pdo连接
    private function connect()
    {
        try {
            $this->handler = new \PDO("{$this->config['type']}:host={$this->config['hostname']};port={$this->config['hostport']};dbname={$this->config['database']};",
                $this->config['username'],
                $this->config['password'],
                array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']};",
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ));
        } catch (\Exception $e) {
            throw new BaseException(['msg' => $e->getMessage()]);
        }

    }

    /**
     * 查询
     * @param string $sql
     * @return mixed
     */
    private function query($sql = '')
    {
        $stmt = $this->handler->query($sql);
        $stmt->setFetchMode(\PDO::FETCH_NUM);
        $list = $stmt->fetchAll();
        return $list;
    }

    /**
     * 获取全部表
     * @return array
     */
    private function get_dbname()
    {
        $sql = 'SHOW TABLES';
        $list = $this->query($sql);
        $tables = array();
        foreach ($list as $value) {
            $tables[] = $value[0];
        }
        return $tables;
    }

    /**
     * 获取表定义语句
     * @param string $table
     * @return mixed
     */
    private function get_dbhead($table = '')
    {
        $sql = "SHOW CREATE TABLE `{$table}`";
        $ddl = $this->query($sql)[0][1] . ';';
        return $ddl;
    }

    /**
     * 获取表数据
     * @param string $table
     * @return mixed
     */
    private function get_dbdata($table = '')
    {
        $sql = "SHOW COLUMNS FROM `{$table}`";
        $list = $this->query($sql);
        //字段
        $columns = '';
        //需要返回的SQL
        $query = '';
        foreach ($list as $value) {
            $columns .= "`{$value[0]}`,";
        }
        $columns = substr($columns, 0, -1);
        $data = $this->query("SELECT * FROM `{$table}`");
        foreach ($data as $value) {
            $dataSql = '';
            foreach ($value as $v) {
                $dataSql .= "'{$v}',";
            }
            $dataSql = substr($dataSql, 0, -1);
            $query .= "INSERT INTO `{$table}` ({$columns}) VALUES ({$dataSql});\r\n";
        }
        return $query;
    }

    /**
     * 写入文件
     * @param array $tables
     * @param array $ddl
     * @param array $data
     */
    private function writeToFile($tables = array(), $ddl = array(), $data = array())
    {
        $str = "/*\r\nMySQL Database SysBackup Tools\r\n";
        $str .= "Server:{$this->config['hostname']}:{$this->config['hostport']}\r\n";
        $str .= "Database:{$this->config['database']}\r\n";
        $str .= "Data:" . date('Y-m-d H:i:s', time()) . "\r\n*/\r\n";
        $str .= "SET FOREIGN_KEY_CHECKS=0;\r\n";
        $i = 0;
        foreach ($tables as $table) {
            $str .= "-- ----------------------------\r\n";
            $str .= "-- Table structure for {$table}\r\n";
            $str .= "-- ----------------------------\r\n";
            $str .= "DROP TABLE IF EXISTS `{$table}`;\r\n";
            $str .= $ddl[$i] . "\r\n";
            $str .= "-- ----------------------------\r\n";
            $str .= "-- Records of {$table}\r\n";
            $str .= "-- ----------------------------\r\n";
            $str .= $data[$i] . "\r\n";
            $i++;
        }
        if (!file_exists($this->config['path'])) {
            mkdir($this->config['path']);
        }
        $res = file_put_contents($this->config['path'] . $this->config['sqlbakname'], $str);
        $size=$this->getfilesize($this->config['sqlbakname']);
        SysBackup::addBackup( $this->config['sqlbakname'],$size);
        return $res;
    }

    /**
     * 备份
     * @return bool
     */
    public function backup()
    {
        //存储表定义语句的数组
        $ddl = array();
        //存储数据的数组
        $data = array();
        $this->tables = $this->get_dbname();
        if (!empty($this->tables)) {
            foreach ($this->tables as $table) {
                $ddl[] = $this->get_dbhead($table);
                $data[] = $this->get_dbdata($table);
            }
            //开始写入
            $res = $this->writeToFile($this->tables, $ddl, $data);
            if ($res) {
                return app('json')->success();
            } else {
                return app('json')->fail();
            }
        } else {
            return app('json')->fail('数据库中没有表!');
        }
    }

    /**
     * 还原
     * @param string $filename
     * @return bool
     */
    public function restore($filename = '')
    {
        $path = $this->config['path'] . $filename;
        if (!file_exists($path)) {
            $this->error('SQL文件不存在!');
            return false;
        } else {
            $sql = $this->parseSQL($path);
            //dump($sql);die;
            try {
                $this->handler->exec($sql);
                echo '还原成功!花费时间', round(microtime(true) - $this->begin, 2) . 'ms';
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }
    }

    /**
     * 解析SQL文件为SQL语句数组
     * @param string $path
     * @return array|mixed|string
     */
    private function parseSQL($path = '')
    {
        $sql = file_get_contents($path);
        $sql = explode("\r\n", $sql);
        //先消除--注释
        $sql = array_filter($sql, function ($data) {
            if (empty($data) || preg_match('/^--.*/', $data)) {
                return false;
            } else {
                return true;
            }
        });
        $sql = implode('', $sql);
        //删除/**/注释
        $sql = preg_replace('/\/\*.*\*\//', '', $sql);
        return $sql;
    }

    /**
     * 获取文件是时间
     * @param string $file
     * @return string
     */
    private function getfiletime($file)
    {
        $path = $this->config['path'] . $file;
        $a = filemtime($path);
        $time = date("Y-m-d H:i:s", $a);
        return $time;
    }

    /**
     * 获取文件是大小
     * @param string $file
     * @return string
     */
    private function getfilesize($file)
    {
        $perms = stat($this->config['path'] . $file);
        $size = $perms['size'];
        $a = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, 2) . $a[$pos];
    }

    /**
     * 获取文件列表
     * @param string $Order 级别
     * @return array
     */
    public function get_filelist($Order = 0)
    {
        $FilePath = $this->config['path'];
//        print_r($FilePath);die;
        $FilePath = opendir($FilePath);
//        $FilePath = scandir($FilePath);
        $FileAndFolderAyy = array();
        $i = 1;
        while (false !== ($filename = readdir($FilePath))) {
            if ($filename != "." && $filename != "..") {
                $i++;
                $FileAndFolderAyy[$i]['name'] = $filename;
                $FileAndFolderAyy[$i]['time'] = $this->getfiletime($filename);
                $FileAndFolderAyy[$i]['size'] = $this->getfilesize($filename);
            }

        }
        $Order == 0 ? sort($FileAndFolderAyy) : rsort($FileAndFolderAyy);
        return $FileAndFolderAyy;
    }

    public function delfilename($filename)
    {
        $path = $this->config['path'] . $filename;
        if (@unlink($path)) {
            return '删除成功';
        }
    }
}

?>