<?php
/**
 * RobotController
 * @package site-post-category
 * @version 0.0.1
 */

namespace SitePostCategory\Controller;

use LibRobot\Library\Feed;
use SitePostCategory\Library\Robot;
use PostCategory\Model\PostCategory as PCategory;

class RobotController extends \Site\Controller
{
    public function feedAction(){
        $slug = $this->req->param->slug;
        $category = PCategory::getOne(['slug'=>$slug]);
        if(!$category)
            return $this->show404();

        $links = Robot::feedPost($category->id);

        $feed_opts = (object)[
            'self_url'          => $this->router->to('sitePostCategorySingleFeed', (array)$category),
            'copyright_year'    => date('Y'),
            'copyright_name'    => \Mim::$app->config->name,
            'description'       => '...',
            'language'          => 'id-ID',
            'host'              => $this->router->to('siteHome'),
            'title'             => \Mim::$app->config->name
        ];

        Feed::render($links, $feed_opts);
        $this->res->setCache(3600);
        $this->res->send();
    }
}