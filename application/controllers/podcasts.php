<?php

class Podcasts extends CI_Controller
{
    public function index()
    {
        // $podcasts = Podcast::published()->paginate(24);

        // return view('podcasts.index', [
        //     'podcasts' => $podcasts,
        // ]);
    }

    public function create()
    {
        // return view('podcasts.create');
    }

    public function store()
    {
        // request()->validate([
        //     'title' => ['required', 'max:150'],
        //     'description' => ['max:500'],
        //     'website' => ['url'],
        // ]);

        // $podcast = Auth::user()->podcasts()->create(request([
        //     'title',
        //     'description',
        //     'website',
        // ]));

        // return redirect("/podcasts/{$podcast->id}");
    }

    public function show($id)
    {
        // $podcast = Podcast::findOrFail($id);

        // abort_unless($podcast->isVisibleTo(Auth::user()), 404);

        // return view('podcasts.show', [
        //     'podcast' => $podcast,
        //     'episodes' => $podcast->recentEpisodes(5),
        // ]);
    }

    public function edit($id)
    {
        // $podcast = Auth::user()->podcasts()->findOrFail($id);

        // return view('podcasts.edit', [
        //     'podcast' => $podcast,
        // ]);
    }

    public function update($id)
    {
        // $podcast = Auth::user()->podcasts()->findOrFail($id);

        // request()->validate([
        //     'title' => ['required', 'max:150'],
        //     'description' => ['max:500'],
        //     'website' => ['url'],
        // ]);

        // $podcast->update(request([
        //     'title',
        //     'description',
        //     'website',
        // ]));

        // return redirect("/podcasts/{$podcast->id}");
    }

    public function destroy($id)
    {
        // $podcast = Auth::user()->podcasts()->findOrFail($id);

        // $podcast->delete();

        // return redirect("/podcasts");
    }
}
