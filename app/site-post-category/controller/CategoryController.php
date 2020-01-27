<?php
/**
 * CategoryController
 * @package site-post-category
 * @version 0.0.1
 */

namespace SitePostCategory\Controller;

use SitePostCategory\Library\Meta;
use PostCategory\Model\PostCategory as PCategory;
use PostCategory\Model\PostCategoryChain as PCChain;
use Post\Model\Post;
use LibFormatter\Library\Formatter;

class CategoryController extends \Site\Controller
{
    public function singleAction() {
        $slug = $this->req->param->slug;

        $category = PCategory::getOne(['slug'=>$slug]);
        if(!$category)
            return $this->show404();

        $category = Formatter::format('post-category', $category, ['user']);

        $posts = [];

        $cond = [
            'post.status'   => 3,
            'post_category' => $category->id
        ];

        list($page, $rpp) = $this->req->getPager(12, 24);

        $pchains = PCChain::get($cond, $rpp, $page, ['created'=>false]);
        if($pchains){
            $post_ids = array_column($pchains, 'post');
            $posts = Post::get(['id'=>$post_ids], 0, 1, ['created'=>false]);
            $posts = Formatter::formatMany('post', $posts, ['user']);
        }

        $params = [
            'category'  => $category,
            'meta'      => Meta::single($category),
            'posts'     => $posts,
            'total'     => PCChain::count($cond)
        ];

        $this->res->render('post-category/single', $params);
        $this->res->setCache(86400);
        $this->res->send();
    }
}