<?php

class Test_Shipyard_Instagram_Import_Images extends WP_UnitTestCase {

    public $iid = '123456789abcdef';

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    private function create_instagram_image_object() {
        $low_resolution = [
            'url'    => 'https://scontent.cdninstagram.com/hphotos-xaf1/t51.2885-15/s320x320/e15/11849355_522917117862040_694509739_n.jpg',
            'width'  => 320,
            'height' => 320,
        ];
        $thumbnail = [
            'url'    => 'https://scontent.cdninstagram.com/hphotos-xaf1/t51.2885-15/s150x150/e15/11849355_522917117862040_694509739_n.jpg',
            'width'  => 150,
            'height' => 150,
        ];
        $standard_resolution = [
            'url'    => 'https://scontent.cdninstagram.com/hphotos-xaf1/t51.2885-15/s640x640/sh0.08/e35/11849355_522917117862040_694509739_n.jpg',
            'width'  => 640,
            'height' => 640,
        ];

        $image_object = new StdClass;
        $image_object->low_resolution      = (object) $low_resolution;
        $image_object->thumbnail           = (object) $thumbnail;
        $image_object->standard_resolution = (object) $standard_resolution;

        $user = ['username' => 'test_user'];
        $caption = ['text' => 'Caption' ];

        $media_object = new StdClass;
        $media_object->id           = $this->iid;
        $media_object->link         = 'https://instagram.com/';
        $media_object->created_time = time();
        $media_object->type         = 'image';
        $media_object->images       = $image_object;
        $media_object->user         = (object) $user;
        $media_object->caption      = (object) $caption;

        return $media_object;
    }


	public function test_get_instagram_feed() {
		$client_id = 'fddbfd43a3d443179c856d42dd1691e3';
        $hashtag = 'stronglifts';

        $feed_items = Shipyard_Instagram_Import_Images::get()->get_instagram_feed( $client_id, $hashtag );
        $this->assertInternalType( 'array', $feed_items );
        $this->assertNotEmpty( $feed_items );
		$this->assertNotFalse( $feed_items );

        $false_client_id = 'boop';
        $feed_items = Shipyard_Instagram_Import_Images::get()->get_instagram_feed( $false_client_id, $hashtag );
        $this->assertFalse( $feed_items );
	}


    public function test_check_if_post_exists() {
        $instagram_id      = '123456789';
        $instagram_fake_id = 'sjdalkfjaslkd9';
        $post_type         = Shipyard_Instagram_Post_Type::get()->post_type;
        $post_id           = $this->factory->post->create( [ 'post_type' => $post_type ] );

        update_post_meta( $post_id, '_instagram_id', $instagram_id );

        $exists = Shipyard_Instagram_Import_Images::get()->check_if_post_exists( $instagram_id );
        $this->assertTrue( $exists );

        $exists = Shipyard_Instagram_Import_Images::get()->check_if_post_exists( $instagram_fake_id );
        $this->assertFalse( $exists );
    }



    public function test_maybe_add_instgram_post() {
        $media_object = $this->create_instagram_image_object();
        $post_id = Shipyard_Instagram_Import_Images::get()->maybe_add_instgram_post( $media_object );

        $this->assertInternalType( 'int', $post_id );

        $exists = Shipyard_Instagram_Import_Images::get()->check_if_post_exists( $this->iid );
        $this->assertTrue( $exists );
    }
}

