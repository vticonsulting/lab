<?php

class Subscriptions extends CI_Controller
{
    public function store()
    {
        // $podcast = Podcast::published()->findOrFail(request('podcast_id'));
        // $subscription = Subscription::create([
        //     'user' => Auth::user(),
        //     'podcast' => $podcast,
        // ]);
        // return $subscription;
    }

    public function destroy($id)
    {
        // $subscription = Auth::user()->subscriptions()->findOrFail($id);

        // $subscription->delete();

        // return response('', 204);
    }
}
