<?php

namespace DevDojo\LaravelReactions\Traits;

use DevDojo\LaravelReactions\Contracts\ReactableInterface;
use DevDojo\LaravelReactions\Models\Reaction;

trait Reacts
{
    public function reactTo(ReactableInterface $reactable, Reaction $reaction)
    {
        // print_r((object)$reaction);
        // die('blah');
        // die('test');
        $reacted = $reactable->reactions()->where([
            'responder_id' => $this->getKey()
        ])->first();

        if ( !$reacted ) {
            return $this->storeReaction($reactable, $reaction);
        }

        if ($reacted->name == $reaction->name) {
            return $reaction;
        }
        $this->deleteReaction($reactable, $reacted);

        return $this->storeReaction($reactable, $reaction);

    }

    public function isReactedBy($responder = null)
    {
        if (is_null($responder)) {
            $responder = auth()->user();
        }

        return $this->reactions()
            ->where('responder_id', $responder->id)
            ->where('responder_type', get_class($responder))->exists();

    }

    /**
     * Store reaction.
     *
     * @param  ReactableInterface                       $reactable
     * @param  mixed                                    $type
     * @return \Qirolab\Laravel\Reactions\Models\Reaction
     */
    protected function storeReaction(ReactableInterface $reactable, Reaction $reaction)
    {
        $reactable->reactions()->attach(
            $reaction->id,
            [
                'responder_id' => $this->getKey(),
                'responder_type' => get_class($this)
            ]
        );

        // $reaction = $reactable->reactions()->create([
        //     'user_id' => $this->getKey(),
        //     'type' => $type,
        // ]);
        //event(new OnReaction($reactable, $reaction, $this));
        return $reaction;
    }

    protected function deleteReaction(ReactableInterface $reactable, Reaction $reacted)
    {
        $reactable->reactions()->detach(
            $reacted->id,
            [
                'responder_id' => $this->id,
                'responder_type' => get_class($this)
            ]
        );
    }
}
