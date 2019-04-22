<?php

namespace DevDojo\LaravelReactions\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Reactable
{
    /**
     * @return MorphToMany
     */
    public function reactions()
    {
        /** @var $this Model */
        return $this->morphToMany('DevDojo\\LaravelReactions\\Models\\Reaction', 'reactable')
            ->withPivot(['responder_id', 'responder_type']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getReactionsSummary()
    {
        return $this->reactions()
            ->getQuery()
            ->select('name', \DB::raw('count(*) as count'))
            ->groupBy('name')
            ->get();
    }

    public function reacted($responder = null)
    {
        if (is_null($responder)) {
            $responder = auth()->user();
        }

        return $this->reactions()
            ->where('responder_id', $responder->id)
            ->where('responder_type', get_class($responder))->exists();

    }
}
