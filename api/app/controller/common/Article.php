<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 8:58
 */

namespace app\controller\common;


use bases\BaseController;
use app\model\Article as ArticleModel;
use services\QyFactory;

class Article extends BaseController
{
    /**
     *  获取所有文章
     * @return mixed
     */
    public function getAllArticle()
    {
        $article=(new QyFactory())->instance('UserService');
        $data=$article->get_article_list();
        return app('json')->success($data);
    }

    /**
     * 获取某一篇公告
     * @return mixed
     */
    public function getArticle()
    {
        return ArticleModel::getArticle();
    }

    /**
     * 获取文章详情
     * @return mixed
     */
    public function getOneArticle($id)
    {
        return ArticleModel::getOneArticle($id);
    }

    /**
     * 用户分类文章
     * @param $type
     * @return mixed
     */
    public function getTypeArticle($type)
    {
        $res=ArticleModel::with('img')->where('is_hidden',1)->where('type',$type)->select();
        return app('json')->success($res);
    }

    /**
     * 用户获取文章
     * @return mixed
     */
    public function userArticle()
    {
        return ArticleModel::userArticle();
    }
}