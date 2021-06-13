<?php
namespace app\controller\install;

use app\validate\InstallValidate;
use bases\BaseCommon;
use bases\BaseController;
use exceptions\BaseException;
use mysqli;
use think\facade\View;

class Index extends BaseController
{
    protected function initialize()
    {
        // 检测是否安装过
        if ((new BaseCommon())->vae_is_installed()) {
            header('Location:/jump.html');
            exit;
        }
    }

    public function step1()
    {
        return View::fetch('step1');
    }

    public function step2()
    {
        if (class_exists('pdo')) {
            $data['pdo'] = 1;
        } else {
            $data['pdo'] = 0;
        }

        if (extension_loaded('pdo_mysql')) {
            $data['pdo_mysql'] = 1;
        } else {
            $data['pdo_mysql'] = 0;
        }

        if (extension_loaded('curl')) {
            $data['curl'] = 1;
        } else {
            $data['curl'] = 0;
        }

        if (ini_get('file_uploads')) {
            $data['upload_size'] = ini_get('upload_max_filesize');
        } else {
            $data['upload_size'] = 0;
        }

        if (function_exists('session_start')) {
            $data['session'] = 1;
        } else {
            $data['session'] = 0;
        }
        return View::fetch('step2', ['data' => $data]);
//        return $this->fetch('', ['data' => $data]);
    }


    public function step3()
    {

        return View::fetch('step3');
    }

    public function createData()
    {

        if ($this->request->isPost()) {

            $data = input('post.');
            (new InstallValidate())->goCheck();
            // 连接数据库
            $link = @new mysqli("{$data['DB_HOST']}:{$data['DB_PORT']}", $data['DB_USER'], $data['DB_PWD']);
            // 获取错误信息
            $error = $link->connect_error;
            if (!is_null($error)) {
                // 转义防止和alert中的引号冲突
                throw new BaseException(['msg' => '数据库链接失败']);
            }
            // 设置字符集
            $link->query("SET NAMES 'utf8'");
            if ($link->server_info < 5.0) {
                throw new BaseException(['msg' => '请将您的mysql升级到5.0以上']);
            }
            // 创建数据库并选中
            if (!$link->select_db($data['DB_NAME'])) {
                $create_sql = 'CREATE DATABASE IF NOT EXISTS ' . $data['DB_NAME'] . ' DEFAULT CHARACTER SET utf8;';
                if (!$link->query($create_sql)) {
                    throw new BaseException(['msg' => '数据库链接失败.0以上']);
                }
                $link->select_db($data['DB_NAME']);
            }
            // 导入sql数据并创建表
            $vaethink_sql = file_get_contents(VAE_ROOT . '/data/ruhua.sql');
            $sql_array = preg_split("/;[\r\n]+/", str_replace("ims_qy2019_shop_", $data['DB_PREFIX'], $vaethink_sql));
            foreach ($sql_array as $k => $v) {
                if (!empty($v)) {
                    $link->query($v);
                }
            }
            //插入管理员
            $username = input('post.username');
            $password = input('post.password');
            $password = (new BaseCommon())->password($password);
            $create_time = time();

            $caeate_admin_sql = "INSERT INTO " . "`".$data['DB_PREFIX'] . "admin"."` "
                . "VALUES "
                . "('1','$username','$password','1','','0','','0','$create_time')";
            if (!$link->query($caeate_admin_sql)) {
                throw new BaseException(['msg' => '创建管理员信息失败']);
            }
            $link->commit();
            $link->close();
            $db_str = "
<?php
use think\\facade\Env;

return [
    // 默认使用的数据库连接配置
    'default'         => Env::get('database.driver', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => Env::get('database.type', 'mysql'),
            // 服务器地址
            'hostname'          => Env::get('database.hostname', '{$data['DB_HOST']}'),
            // 数据库名
            'database'          => Env::get('database.database', '{$data['DB_NAME']}'),
            // 用户名
            'username'          => Env::get('database.username', '{$data['DB_USER']}'),
            // 密码
            'password'          => Env::get('database.password', '{$data['DB_PWD']}'),
            // 端口
            'hostport'          => Env::get('database.hostport', '{$data['DB_PORT']}'),
            // 数据库连接参数
            'params'            => [],
            // 数据库编码默认采用utf8
            'charset'           => Env::get('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix'            => Env::get('database.prefix', '{$data['DB_PREFIX']}'),
            // 数据库调试模式
            'debug'           => Env::get('database.debug', true),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => false,
            // 监听SQL
            'trigger_sql'       => true,
            // 开启字段缓存
            'fields_cache'      => false,
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ],

        // 更多的数据库配置信息
    ],
];";


            // 创建数据库配置文件
            if (false == file_put_contents(VAE_ROOT . "config/database.php", $db_str)) {
                throw new BaseException(['msg' => '创建安装鉴定文件失败，请检查目录权限']);
            }
//            // 创建数据库配置文件
//            if (false == file_put_contents(VAE_ROOT . "config/setting.php", $setting_str)) {
//                throw new BaseException(['msg' => '创建安装鉴定文件失败，请检查目录权限']);
//            }
//            $res = Admin::create(['username' => $username, 'password' => $password, 'group_id' => 1]);
//            if (!$res) {
//                throw new BaseException(['msg' => '创建管理员信息失败']);
//            }
            if (false == file_put_contents(VAE_ROOT . "data/install.lock", 'ruhua安装鉴定文件，勿删！！！！！此次安装时间：' . date('Y-m-d H:i:s', time()))) {
                throw new BaseException(['msg' => '创建安装鉴定文件失败，请检查目录权限']);
            }

//            (new QrcodeServer)->getCode($_SERVER['HTTP_HOST'].'/h5/index.html');
            return $res['code'] = 1;
        }
    }
}

