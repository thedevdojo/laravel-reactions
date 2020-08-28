<?php

namespace DevDojo\LaravelReactions\Tests\Integration;

use DevDojo\LaravelReactions\Models\Reaction;
use DevDojo\LaravelReactions\Tests\Integration\Support\PostTestModel;
use DevDojo\LaravelReactions\Tests\Integration\Support\UserTestModel;
use Illuminate\Database\Schema\Blueprint;

class ReactsModelTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->createTestEntitiesTables();
        $this->createTestEntities();
    }

    public function testItShouldReactToReactable()
    {
        /** @var UserTestModel $user */
        $user = UserTestModel::first();

        /** @var PostTestModel $post */
        $post = PostTestModel::first();

        $loveReaction = Reaction::where('name', '=', 'love')->first();

        $user->reactTo($post, $loveReaction);

        $this->seeInDatabase('reactables', [
            'reaction_id'    => $loveReaction->id,
            'reactable_id'   => $post->id,
            'reactable_type' => PostTestModel::class,
            'responder_id'   => $user->id,
            'responder_type' => UserTestModel::class,
        ]);
    }

    private function createTestEntities()
    {
        $user = new UserTestModel();
        $user->name = 'Francesco';
        $user->email = 'email@address.com';
        $user->password = '';
        $user->save();

        $reaction = Reaction::createFromName('like');
        $reaction->save();
        $reaction2 = Reaction::createFromName('love');
        $reaction2->save();

        $post = new PostTestModel();
        $post->title = 'Hello World!';
        $post->save();

        $post2 = new PostTestModel();
        $post2->title = 'The End of The World';
        $post2->save();
    }

    private function createTestEntitiesTables()
    {
        \Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }
}
