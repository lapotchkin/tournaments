<style>
    #carousel-games a:hover {
        text-decoration: none !important;
    }
</style>

<div id="carousel-games" class="carousel slide bg-secondary mb-3" data-ride="carousel">
    <div class="carousel-inner row w-100 mx-auto" role="listbox">
        @foreach($games as $game)
            @if($loop->iteration > 10)
                @break
            @endif
            @php
                if ($game->playoff_pair_id) {
                    $link = route('personal.tournament.playoff.game', ['tournamentId' => $game->tournament->id, 'pairId' => $game->playoff_pair_id, 'gameId' => $game->id]);
                } else {
                    $link = route('personal.tournament.regular.game', ['tournamentId' => $game->tournament->id, 'gameId' => $game->id]);
                }
            @endphp
            <a
                href="{{ $link }}"
                class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 text-light border-light border-right {{ $loop->iteration === 1 ? 'active' : '' }} p-1">
                <div class="text-center">
                    <i class="fab fa-{{ $game->tournament->platform->icon }}"></i>
                    <strong>{{ $game->tournament->title }}</strong>
                </div>
                <div class="text-center text-white-50">
                    @if($game->playoff_pair_id)
                        {{ TextUtils::playoffRound($game->tournament, $game->playoffPair->round) }}
                    @else
                        Регулярный чемпионат
                    @endif
                </div>
                <div class="h5 mb-1 row">
                    <div class="col-9 pl-4">
                        <small>
                            <span class="badge badge-light text-uppercase">
                                {{ $game->homePlayer->getClubId($game->tournament->id) }}
                            </span>
                            {{ $game->homePlayer->tag }}
                        </small>
                    </div>
                    <div class="col-3 pr-4 text-right">
                        <span class="badge badge-pill badge-dark">{{ $game->home_score }}</span>
                    </div>
                </div>
                <div class="h5 mb-1 row">
                    <div class="col-9 pl-4">
                        <small>
                            <span class="badge badge-light text-uppercase">
                                {{ $game->awayPlayer->getClubId($game->tournament->id) }}
                            </span>
                            {{ $game->awayPlayer->tag }}
                        </small>
                    </div>
                    <div class="col-3 pr-4 text-right">
                        <span class="badge badge-pill badge-dark">{{ $game->away_score }}</span>
                    </div>
                </div>
                <div class="text-center text-white-50">
                    {{ (new \DateTime($game->playedAt))->format('d.m.Y') }}
                </div>
            </a>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carousel-games" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-games" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<script>
    /*
    Carousel
*/
    $('#carousel-games').on('slide.bs.carousel', function (e) {
        /*
            CC 2.0 License Iatek LLC 2018 - Attribution required
        */
        const $e = $(e.relatedTarget);
        const idx = $e.index();
        const itemsPerSlide = 5;
        const $items = $('.carousel-item');
        const totalItems = $items.length;

        if (idx >= totalItems - (itemsPerSlide - 1)) {
            const it = itemsPerSlide - (totalItems - idx);
            for (let i = 0; i < it; i++) {
                // append slides to end
                if (e.direction === "left") {
                    $items.eq(i).appendTo('.carousel-inner');
                } else {
                    $items.eq(0).appendTo('.carousel-inner');
                }
            }
        }
    });
</script>
