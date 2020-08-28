<?php

namespace DevDojo\LaravelReactions\Tests\Integration\Support;

use DevDojo\LaravelReactions\Traits\Reacts;
use Illuminate\Database\Eloquent\Model;

class UserTestModel extends Model
{
    use Reacts;

    protected $table = 'users';
}
