<?php

namespace DevDojo\LaravelReactions\Tests\Integration\Support;

use DevDojo\LaravelReactions\Contracts\ReactableInterface;
use DevDojo\LaravelReactions\Traits\Reactable;
use Illuminate\Database\Eloquent\Model;

class PostTestModel extends Model implements ReactableInterface
{
    use Reactable;

    protected $table = 'posts';
}
