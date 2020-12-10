    <div class="container mt-6">
        <div class="row pull-x-4">
            <div class="px-4 col-3">
                <div class="block mb-4 box-shadow">
                    <a href="{{ url("/podcasts/{$podcast->id}") }}">
                        <img src="{{ $podcast->imageUrl() }}" class="img-fit">
                    </a>
                </div>
            </div>
            <div class="px-4 col-9">
                <div class="mb-6">
                    <div class="mb-4 flex-spaced flex-y-center">
                        <div>
                            <h1>
                                <a href="{{ url("/podcasts/{$podcast->id}") }}" class="text-bold">{{ $podcast->title }}</a>
                            </h1>
                            <p class="text-sm text-uppercase text-spaced text-ellipsis">
                                <a href="{{ $podcast->website }}" class="link-softer text-medium">
                                    {{ $podcast->websiteHost() }}
                                </a>
                            </p>
                        </div>
                        <div class="">
                            @if ($podcast->isOwnedBy(Auth::user()))
                                <publish-button :data-podcast="{{ $podcast }}" class="mr-2"></publish-button>
                                <a href="{{ url("/podcasts/{$podcast->id}/edit") }}" class="btn btn-sm btn-secondary">
                                    Settings
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4">
                        <subscribe-button
                            :data-subscription="{{ json_encode(Auth::user()->subscriptionTo($podcast)) }}"
                            :podcast="{{ $podcast }}"
                        ></subscribe-button>
                    </div>
                    <p class="text-dark-soft">{{ $podcast->description }}</p>
                </div>
                <div>
                    <h2 class="mb-4 text-lg">All Episodes</h2>
                    <div class="text-sm">
                    @foreach ($podcast->episodes->sortByDesc('number') as $episode)
                        <div class="flex border-t flex-y-baseline">
                            <div style="flex: 0 0 3rem;" class="py-3 pr-4 text-right text-no-wrap text-dark-softest">{{ $episode->number }}</div>
                            <div class="text-ellipsis flex-fill">
                                <a href="{{ url("/episodes/{$episode->id}") }}">{{ $episode->title }}</a>
                            </div>
                            <div style="flex: 0 0 6rem;" class="pr-4 text-right text-no-wrap text-dark-softer">
                                {{ $episode->durationForHumans() }}
                            </div>
                            <div style="flex: 0 0 6.5rem;" class="pr-4 text-no-wrap text-dark-softer">
                                {{ $episode->published_at->format('M j, Y') }}
                            </div>
                            <div style="flex: 0 0 4.5rem;" class="text-right text-no-wrap">
                                <a href="{{ url("/episodes/{$episode->id}") }}" class="btn btn-xs btn-secondary">Listen</a>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
