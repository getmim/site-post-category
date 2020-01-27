<?php
/**
 * Robot
 * @package site-post-category
 * @version 0.0.1
 */

namespace SitePostCategory\Library;

use PostCategory\Model\PostCategory as PCategory;
use PostCategory\Model\PostCategoryChain as PCChain;
use Post\Model\Post;

class Robot
{
    static private function getPages(): ?array{
        $cond = [
            'updated' => ['__op', '>', date('Y-m-d H:i:s', strtotime('-2 days'))]
        ];
        $pages = PCategory::get($cond);
        if(!$pages)
            return null;

        return $pages;
    }

    static private function getCategoryPosts(int $category): ?array{
        $cond = [
            'post_category' => $category,
            'post.status'   => 3,
            'post.updated'  => ['__op', '>', date('Y-m-d H:i:s', strtotime('-2 days'))]
        ];

        $pages = PCChain::get($cond);
        if(!$pages)
            return null;

        $post_ids = array_column($pages, 'post');
        $posts = Post::get(['id'=>$post_ids]);

        return $posts;
    }

    static function feed(): array {
        $mim = &\Mim::$app;

        $pages = self::getPages();
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route = $mim->router->to('sitePostCategorySingle', (array)$page);
            $meta  = json_decode($page->meta);
            $title = $meta->title ?? $page->name;
            $desc  = $meta->description ?? substr($page->content, 0, 100);

            $result[] = (object)[
                'description'   => $desc,
                'page'          => $route,
                'published'     => $page->created,
                'updated'       => $page->updated,
                'title'         => $title,
                'guid'          => $route
            ];
        }

        return $result;
    }

    static function feedPost(int $category): array{
        $mim = &\Mim::$app;

        $pages = self::getCategoryPosts($category);
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route = $mim->router->to('sitePostSingle', (array)$page);
            $meta  = json_decode($page->meta);
            $title = $meta->title ?? $page->title;
            $desc  = $meta->description ?? $page->title;

            $result[] = (object)[
                'description'   => $desc,
                'page'          => $route,
                'published'     => $page->created,
                'updated'       => $page->updated,
                'title'         => $title,
                'guid'          => $route
            ];
        }

        return $result;
    }

    static function sitemap(): array {
        $mim = &\Mim::$app;

        $pages = self::getPages();
        if(!$pages)
            return [];

        $result = [];
        foreach($pages as $page){
            $route  = $mim->router->to('sitePostCategorySingle', (array)$page);
            $result[] = (object)[
                'page'          => $route,
                'updated'       => $page->updated,
                'priority'      => '0.8',
                'changefreq'    => 'daily'
            ];
        }

        return $result;
    }
}