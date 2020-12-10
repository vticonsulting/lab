<?php

class Podcast_cover_image extends CI_Controller
{
    public function update($id)
    {
        // $podcast = Auth::user()->podcasts()->findOrFail($id);

        // request()->validate([
        //     'cover_image' => ['required', 'image', Rule::dimensions()->minHeight(500), Rule::dimensions()->minWidth(500)],
        // ]);

        // $podcast->update([
        //     'cover_path' => request()->file('cover_image')->store('images', 'public'),
        // ]);

        // return redirect("/podcasts/{$podcast->id}");
    }
}
