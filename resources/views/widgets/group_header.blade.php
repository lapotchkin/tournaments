<h2>
    <i class="fab fa-{{ $tournament->platform->icon }} @if($tournament->platform->icon === 'xbox') text-success @endif"></i>
    {{ $tournament->title }}
    <span class="badge badge-pill badge-secondary">
        {{ $tournament->min_players }} на {{ $tournament->min_players }}
    </span>
    @auth
        @if(Auth::user()->isAdmin())
            <a class="btn btn-primary" href="{{ route('group.tournament.edit', ['tournamentId' => $tournament->id]) }}">
                <i class="fas fa-edit"></i>
            </a>
        @endif
    @endauth
</h2>
<p>Создан: {{ (new \DateTime($tournament->createdAt))->format('d.m.Y') }}</p>
@if(count($tournament->winners))
    <h3>Победитель и призёры</h3>
    <div class="row">
        @foreach($tournament->winners as $winner)
            <div class="col-12 col-md-4">
                <blockquote class="blockquote alert alert-{{ TextUtils::winnerClass($winner->place) }}">
                    <footer class="blockquote-footer"><i class="fas fa-trophy"></i> {{ $winner->place }} место</footer>
                    <p class="mb-0"><a
                            href="{{ route('team', ['teamId' => $winner->team_id]) }}">{{ $winner->team->name }}</a></p>
                </blockquote>
            </div>
        @endforeach
    </div>
@endif
