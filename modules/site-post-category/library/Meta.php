<?php
/**
 * Meta
 * @package site-post-category
 * @version 0.0.1
 */

namespace SitePostCategory\Library;


class Meta
{
    static function single(object $page){
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');

        // reset meta
        if(!is_object($page->meta))
            $page->meta = (object)[];

        $def_meta = [
            'title'         => $page->name,
            'description'   => $page->content->chars(160),
            // 'schema'        => 'Article',
            'keyword'       => ''
        ];

        foreach($def_meta as $key => $value){
            if(!isset($page->meta->$key) || !$page->meta->$key)
                $page->meta->$key = $value;
        }

        $result['head'] = [
            'description'       => $page->meta->description,
            'published_time'    => $page->created,
            'schema.org'        => [],
            'type'              => 'article',
            'title'             => $page->meta->title,
            'updated_time'      => $page->updated,
            'url'               => $page->page,
            'metas'             => []
        ];

        // schema breadcrumbList
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $home_url . '#post-category',
                        'name' => 'Post Category'
                    ]
                ]
            ]
        ];

        // // schema page
        // $result['head']['schema.org'][] = [
        //     '@context'      => 'http://schema.org',
        //     '@type'         => $page->meta->schema,
        //     'name'          => $page->meta->title,
        //     'description'   => $page->meta->description,
        //     'dateCreated'   => $page->created,
        //     'dateModified'  => $page->updated,
        //     'datePublished' => $page->created,
        //     'publisher'     => \Mim::$app->meta->schemaOrg( \Mim::$app->config->name ),
        //     // 'thumbnailUrl'  => $meta_image,
        //     'url'           => $page->page,
        //     // 'image'         => $meta_image
        // ];

        return $result;
    }
}