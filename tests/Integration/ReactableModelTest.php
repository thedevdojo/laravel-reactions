<?php

namespace DevDojo\LaravelReactions\Tests\Integration;

use DevDojo\LaravelReactions\Models\Reaction;
use DevDojo\LaravelReactions\Tests\Integration\Support\PostTestModel;
use DevDojo\LaravelReactions\Tests\Integration\Support\UserTestModel;
use Illuminate\Database\Schema\Blueprint;

class ReactableModelTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTestEntitiesTables();
        $this->createTestEntities();
    }

    public function testItShouldReturnRightSummary()
    {
        /** @var PostTestModel $post */
        $post = PostTestModel::first();
        $summaryAsArray = $post->getReactionsSummary()->toArray();

        $this->assertEquals([
            ['name' => 'like', 'count' => 1],
            ['name' => 'love', 'count' => 2],
        ], $summaryAsArray);
    }

    private function createTestEntities()
    {
        $user = $this->createTestUser(1);
        $user2 = $this->createTestUser(2);
        $user3 = $this->createTestUser(3);

        $like = Reaction::createFromName('like');
        $like->save();
        $love = Reaction::createFromName('love');
        $love->save();

        $post = new PostTestModel();
        $post->title = 'Hello World!';
        $post->save();

        $user->reactTo($post, $like);
        $user2->reactTo($post, $love);
        $user3->reactTo($post, $love);
    }

    private function createTestEntitiesTables()
    {
        \Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }

    private function createTestUser($id)
    {
        $user = new UserTestModel();
        $user->name = "User $id";
        $user->email = "email$id@address.com";
        $user->password = '';
        $user->save();

        return $user;
    }
}
