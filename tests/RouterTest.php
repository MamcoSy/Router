<?php

namespace MamcoSy\Tests;

use MamcoSy\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testGetMethodWithRootPath()
    {
        $router = new Router("/", 'GET');
        $router->get('/', 'MamcoSy\Tests\Fixtures\TestController@index', 'home');
        $this->assertEquals('test success', $router->resolve());

    }

    public function testGetMethodWithPostsPath()
    {
        $router = new Router("/posts", 'GET');
        $router->get('/posts', function () {
            return 'all posts';
        });

        $this->assertEquals('all posts', $router->resolve());
    }

    public function testGetMethodWithPostId()
    {
        $router = new Router("/post/1", 'GET');
        $router->get('/post/{id}', function (int $id) {
            return 'posts number ' . $id;
        });

        $this->assertEquals('posts number 1', $router->resolve());
    }

    public function testGetMethodWithSlugAndId()
    {
        $router = new Router("/post/mon-article-1", 'GET');
        $router->get('/post/{slug}-{id}', function ($slug, $id) {
            return 'slug ' . $slug . ' for id ' . $id;
        });

        $this->assertEquals('slug mon-article for id 1', $router->resolve());
    }

    public function testGetMethodWithSlugAndIdInversed()
    {
        $router = new Router("/post/1-mon-article", 'GET');
        $router->get('/post/{id}-{slug}', function ($id, $slug) {
            return 'slug ' . $slug . ' for id ' . $id;

        })->with('id', '[0-9]+')->with('slug', '[a-z\-0-9]+');

        $this->assertEquals('slug mon-article for id 1', $router->resolve());
    }
}
