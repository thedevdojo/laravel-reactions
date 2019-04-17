<?php

namespace DevDojo\LaravelReactions\Tests\Integration\Support;

use Illuminate\Database\Eloquent\Model;
use DevDojo\LaravelReactions\Traits\Reacts;

class UserTestModel extends Model
{
    use Reacts;

    protected $table = 'users';
}
