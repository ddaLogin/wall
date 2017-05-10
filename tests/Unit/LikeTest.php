<?php

namespace Tests\Unit;

use App\Classes;
use Tests\TestCase;

class LikeTest extends TestCase
{
    public function testGetStatusLike()
    {
        $like = new Classes\Like(true);
        $this->assertTrue($like->getStatus());
    }

    public function testGetStatusDislike()
    {
        $like = new Classes\Like(false);
        $this->assertFalse($like->getStatus());
    }

    public function testGetStatusNull()
    {
        $like = new Classes\Like(null);
        $this->assertNull($like->getStatus());
    }

    public function testGetStatusLikeInt()
    {
        $like = new Classes\Like(true);
        $this->assertEquals(1, $like->getStatusLikeInt());
    }

    public function testGetStatusDislikeInt()
    {
        $like = new Classes\Like(false);
        $this->assertEquals(-1, $like->getStatusLikeInt());
    }

    public function testGetStatusNullInt()
    {
        $like = new Classes\Like(null);
        $this->assertEquals(0, $like->getStatusLikeInt());
    }
}
