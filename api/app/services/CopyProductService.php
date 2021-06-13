<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/7 0007
 * Time: 11:30
 */

namespace app\services;


use services\HttpService;

class CopyProductService
{
    //错误信息
    protected $errorInfo = true;
    //产品默认字段
    protected $productInfo = [
        'goods_name' => '',
        'imgs' => '',
        'banner_imgs_url' => '',
        'description' => '',
    ];
    //抓取网站主域名
    protected $grabName = [
        'taobao',
        'tmall',
        'jd'
    ];


    /**
     * 设置错误信息
     * @param string $msg 错误信息
     * @return bool
     */
    public function setErrorInfo($msg = '')
    {
        $this->errorInfo = $msg;
        return false;
    }

    /**
     * 设置字符串字符集
     * @param string $str 需要设置字符集的字符串
     * @return string
     */
    public function editUtf8($str)
    {
        $encode = mb_detect_encoding($str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        if (strtoupper($encode) != 'UTF-8') $str = mb_convert_encoding($str, 'utf-8', $encode);
        return $str;
    }

    /**
     * 获取资源,并解析出对应的商品参数
     * @return json
     */
    public function getRequestContents()
    {

        $url=input('post.url');
        if(!$url){
            return app('json')->fail('url必填');
        }
        $url = $this->checkUrl($url);
        if ($url === false) {
            return app('json')->fail($this->errorInfo);
        }
        $this->errorInfo = true;
        $html = $this->curl_Get($url, 60);
        if (!$html){
            return app('json')->fail('商品HTML信息获取失败');
        }
        $html = $this->editUtf8($html);
        preg_match('/<title>([^<>]*)<\/title>/', $html, $title);

        //商品标题
        $this->productInfo['goods_name'] = isset($title['1']) ? str_replace(['-淘宝网', '-tmall.com天猫', ' - 阿里巴巴', ' ', '-', '【图片价格品牌报价】京东', '京东', '【行情报价价格评测】'], '', trim($title['1'])) : '';
        try {
            //获取url信息
            $pathinfo = pathinfo($url);
            if (!isset($pathinfo['dirname'])) return app('json')->fail('解析URL失败');
            //提取域名
            $parse_url = parse_url($pathinfo['dirname']);
            if (!isset($parse_url['host'])) return app('json')->fail('获取域名失败');
            //获取第一次.出现的位置
            $strLeng = strpos($parse_url['host'], '.') + 1;
            //截取域名中的真实域名不带.com后的
            $funsuffix = substr($parse_url['host'], $strLeng, strrpos($parse_url['host'], '.') - $strLeng);
            if (!in_array($funsuffix, $this->grabName)) return app('json')->fail('您输入的地址不在复制范围内！');
            //设拼接设置产品函数
            $funName = "setProductInfo" . ucfirst($funsuffix);
            //执行方法

            if (method_exists($this, $funName))
                $this->$funName($html);
            else
                return app('json')->fail('设置产品函数不存在');
            if (!$this->productInfo['banner_imgs_url']) return app('json')->fail('未能获取到商品信息，请确保商品信息有效！');
            return app('json')->success($this->productInfo);
        } catch (\Exception $e) {
            return app('json')->fail('系统错误', ['line' => $e->getLine(), 'meass' => $e->getMessage()]);
        }
    }

    /**
     * 淘宝设置产品
     * @param string $html 网页内容
     * */
    public function setProductInfoTaobao($html)
    {
        //获取轮播图
        $images = $this->getTaobaoImg($html);
        $images = array_merge($images);
        $this->productInfo['banner_imgs_url'] = isset($images['gaoqing']) ? $images['gaoqing'] : (array)$images;
        $this->productInfo['imgs'] = is_array($this->productInfo['banner_imgs_url']) && isset($this->productInfo['banner_imgs_url'][0]) ? $this->productInfo['banner_imgs_url'][0] : '';
    }

    /**
     * 天猫设置产品
     * @param string $html 网页内容
     * */
    public function setProductInfoTmall($html)
    {
        //获取轮播图
        $images = $this->getTianMaoImg($html);
        $images = array_merge($images);
        $this->productInfo['banner_imgs_url'] = $images;
        $this->productInfo['imgs'] = is_array($this->productInfo['banner_imgs_url']) && isset($this->productInfo['banner_imgs_url'][0]) ? $this->productInfo['banner_imgs_url'][0] : '';
    }

    /**
     * JD设置产品
     * @param string $html 网页内容
     * */
    public function setProductInfoJd($html)
    {
        //获取产品详情请求链接
        $desc_url = $this->getJdDesc($html);
        //获取请求内容
        $desc_json = HttpService::getRequest($desc_url);
        //转换字符集
        $desc_json = $this->editUtf8($desc_json);
        //截取掉多余字符
        if (substr($desc_json, 0, 8) == 'showdesc') $desc_json = str_replace('showdesc', '', $desc_json);
        $desc_json = str_replace('data-lazyload=', 'src=', $desc_json);
        $descArray = json_decode($desc_json, true);
        if (!$descArray) $descArray = ['content' => ''];
        //获取轮播图
        $images = $this->getJdImg($html);
        $images = array_merge($images);
        $this->productInfo['banner_imgs_url'] = $images;
        $this->productInfo['imgs'] = is_array($this->productInfo['banner_imgs_url']) ? $this->productInfo['banner_imgs_url'][0] : '';
        $this->productInfo['description'] = $descArray['content'];
        //获取详情图
        $description_images = $this->decodeDesc($descArray['content']);
        $this->productInfo['description_images'] = is_array($description_images) ? $description_images : [];
    }

    /**
    * 检查淘宝，天猫的商品链接
     * @param $link
    * @return string
    */
    public function checkUrl($link)
    {
        $link = strtolower($link);
        if (!$link) return $this->setErrorInfo('请输入链接地址');
        if (substr($link, 0, 4) != 'http') return $this->setErrorInfo('链接地址必须以http开头');
        $arrLine = explode('?', $link);
        if (!count($arrLine)) return $this->setErrorInfo('链接地址有误(ERR:1001)');
        if (!isset($arrLine[1])) {
            if (strpos($link, '1688') !== false && strpos($link, 'offer') !== false) return trim($arrLine[0]);
            else if (strpos($link, 'item.jd') !== false) return trim($arrLine[0]);
            else return $this->setErrorInfo('链接地址有误(ERR:1002)');
        }
        if (strpos($link, '1688') !== false && strpos($link, 'offer') !== false) return trim($arrLine[0]);
        if (strpos($link, 'item.jd') !== false) return trim($arrLine[0]);
        $arrLineValue = explode('&', $arrLine[1]);
        if (!is_array($arrLineValue)) return $this->setErrorInfo('链接地址有误(ERR:1003)');
        if (!strpos(trim($arrLine[0]), 'item.htm')) $this->setErrorInfo('链接地址有误(ERR:1004)');
        //链接参数
        $lastStr = '';
        foreach ($arrLineValue as $k => $v) {
            if (substr(strtolower($v), 0, 3) == 'id=') {
                $lastStr = trim($v);
                break;
            }
        }
        if (!$lastStr) return $this->setErrorInfo('链接地址有误(ERR:1005)');
        return trim($arrLine[0]) . '?' . $lastStr;
    }
    
    /**
     * 提取商品描述中的所有图片
     * @param string $desc
     * @return array|string
     */
    public function decodeDesc($desc = '')
    {
        $desc = trim($desc);
        if (!$desc) return '';
        preg_match_all('/<img[^>]*?src="([^"]*?)"[^>]*?>/i', $desc, $match);
        if (!isset($match[1]) || count($match[1]) <= 0) {
            preg_match_all('/:url(([^"]*?));/i', $desc, $match);
            if (!isset($match[1]) || count($match[1]) <= 0) return $desc;
        } else {
            preg_match_all('/:url(([^"]*?));/i', $desc, $newmatch);
            if (isset($newmatch[1]) && count($newmatch[1]) > 0) $match[1] = array_merge($match[1], $newmatch[1]);
        }
        $match[1] = array_unique($match[1]); //去掉重复
        foreach ($match[1] as $k => &$v) {
            $_tmp_img = str_replace([')', '(', ';'], '', $v);
            $_tmp_img = strpos($_tmp_img, 'http') ? $_tmp_img : 'http:' . $_tmp_img;
            if (strpos($v, '?')) {
                $_tarr = explode('?', $v);
                $_tmp_img = trim($_tarr[0]);
            }
            $_urls = str_replace(['\'', '"'], '', $_tmp_img);
            if ($this->_img_exists($_urls)) $v = $_urls;
        }
        return $match[1];
    }

    /**
     * 获取京东商品组图
     * @param string $html
     * @return array|string
     */
    public function getJdImg($html = '')
    {
        //获取图片服务器网址
        preg_match('/<img(.*?)id="spec-img"(.*?)data-origin=\"(.*?)\"[^>]*>/', $html, $img);
        if (!isset($img[3])) return '';
        $info = parse_url(trim($img[3]));
        if (!$info['host']) return '';
        if (!$info['path']) return '';
        $_tmparr = explode('/', trim($info['path']));
        $url = 'http://' . $info['host'] . '/' . $_tmparr[1] . '/' . str_replace(['jfs', ' '], '', trim($_tmparr[2]));
        preg_match('/imageList:(.*?)"],/is', $html, $img);
        if (!isset($img[1])) {
            return '';
        }
        $_arr = explode(',', $img[1]);
        foreach ($_arr as $k => &$v) {
            $_str = $url . str_replace(['"', '[', ']', ' '], '', trim($v));
            if (strpos($_str, '?')) {
                $_tarr = explode('?', $_str);
                $_str = trim($_tarr[0]);
            }
            if ($this->_img_exists($_str)) {
                $v = $_str;
            } else {
                unset($_arr[$k]);
            }
        }
        return array_unique($_arr);
    }

    /**
     * 获取京东商品描述
     * @param string $html
     * @return string
     */
    public function getJdDesc($html = '')
    {
        preg_match('/,(.*?)desc:([^<>]*)\',/i', $html, $descarr);
        if (!isset($descarr[1]) && !isset($descarr[2])) return '';
        $tmpArr = explode(',', $descarr[2]);
        if (count($tmpArr) > 0) {
            $descarr[2] = trim($tmpArr[0]);
        }
        $replace_arr = ['\'', '\',', ' ', ',', '/*', '*/'];
        if (isset($descarr[2])) {
            $d_url = str_replace($replace_arr, '', $descarr[2]);
            return $this->formatDescUrl(strpos($d_url, 'http') ? $d_url : 'http:' . $d_url);
        }
        $d_url = str_replace($replace_arr, '', $descarr[1]);
        $d_url = $this->formatDescUrl($d_url);
        $d_url = rtrim(rtrim($d_url, "?"), "&");
        return substr($d_url, 0, 4) == 'http' ? $d_url : 'http:' . $d_url;
    }

    /**
     * 处理下京东商品描述网址
     * @param string $url
     * @return string
     */
    public function formatDescUrl($url = '')
    {
        if (!$url) return '';
        $url = substr($url, 0, 4) == 'http' ? $url : 'http:' . $url;
        if (!strpos($url, '&')) {
            $_arr = explode('?', $url);
            if (!is_array($_arr) || count($_arr) <= 0) return $url;
            return trim($_arr[0]);
        } else {
            $_arr = explode('&', $url);
        }
        if (!is_array($_arr) || count($_arr) <= 0) return $url;
        unset($_arr[count($_arr) - 1]);
        $new_url = '';
        foreach ($_arr as $k => $v) {
            $new_url .= $v . '&';
        }
        return !$new_url ? $url : $new_url;
    }


    /** 获取天猫商品组图
     * @param string $html
     * @return array|string
     */
    public function getTianMaoImg($html = '')
    {
        $pic_size = '430';
        preg_match('/<img[^>]*id="J_ImgBooth"[^r]*rc=\"([^"]*)\"[^>]*>/', $html, $img);
        if (isset($img[1])) {
            $_arr = explode('x', $img[1]);
            $filename = $_arr[count($_arr) - 1];
            $pic_size = intval(substr($filename, 0, 3));
        }
        preg_match('|<ul id="J_UlThumb" class="tb-thumb tm-clear">(.*)</ul>|isU', $html, $match);
        preg_match_all('/<img src="(.*?)" \//', $match[1], $images);
        if (!isset($images[1])) return '';
        foreach ($images[1] as $k => &$v) {
            $tmp_v = trim($v);
            $_arr = explode('x', $tmp_v);
            $_fname = $_arr[count($_arr) - 1];
            $_size = intval(substr($_fname, 0, 3));
            if (strpos($tmp_v, '://')) {
                $_arr = explode(':', $tmp_v);
                $r_url = trim($_arr[1]);
            } else {
                $r_url = $tmp_v;
            }
            $str = str_replace($_size, $pic_size, $r_url);
            if (strpos($str, '?')) {
                $_tarr = explode('?', $str);
                $str = trim($_tarr[0]);
            }
            $_i_url = strpos($str, 'http') ? $str : 'http:' . $str;
            if ($this->_img_exists($_i_url)) {
                $v = $_i_url;
            } else {
                unset($images[1][$k]);
            }
        }
        return array_unique($images[1]);
    }

    //获取天猫商品描述
    public function getTianMaoDesc($html = '')
    {
        preg_match('/descUrl":"([^<>]*)","httpsDescUrl":"/', $html, $descarr);
        if (!isset($descarr[1])) {
            preg_match('/httpsDescUrl":"([^<>]*)","fetchDcUrl/', $html, $descarr);
            if (!isset($descarr[1])) return '';
        }
        return strpos($descarr[1], 'http') ? $descarr[1] : 'http:' . $descarr[1];
    }

    /**
     * 获取淘宝商品组图
     * @param string $html
     * @return array|string
     */
    public function getTaobaoImg($html = '')
    {
        preg_match('/auctionImages([^<>]*)"]/', $html, $imgarr);
        if (!isset($imgarr[1])) return '';
        $arr = explode(',', $imgarr[1]);
        foreach ($arr as $k => &$v) {
            $str = trim($v);
            $str = str_replace(['"', ' ', '', ':['], '', $str);
            if (strpos($str, '?')) {
                $_tarr = explode('?', $str);
                $str = trim($_tarr[0]);
            }
            $_i_url = strpos($str, 'http') ? $str : 'http:' . $str;
            if ($this->_img_exists($_i_url)) {
                $v = $_i_url;
            } else {
                unset($arr[$k]);
            }
        }
        return array_unique($arr);
    }

    /**
     * GET 请求
     * @param string $url
     * @param int $time_out
     * @return bool|mixed|string
     */
    public function curl_Get($url = '', $time_out = 25)
    {
        if (!$url) return '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('user-agent:' . $_SERVER['HTTP_USER_AGENT']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
        $response = curl_exec($ch);
        if ($error = curl_error($ch)) {
            return false;
        }
        curl_close($ch);
        return mb_convert_encoding($response, 'utf-8', 'GB2312');
    }

    /**
     * 检测远程文件是否存在
     * @param string $url
     * @return bool
     */
    public function _img_exists($url = '')
    {
        ini_set("max_execution_time", 0);
        $str = @file_get_contents($url, 0, null, 0, 1);
        if (strlen($str) <= 0) return false;
        if ($str)
            return true;
        else
            return false;
    }

}