<h2>
    <i class="fab fa-{{ $tournament->platform->icon }}"></i>
    {{ $tournament->title }}
    <span class="badge badge-pill badge-secondary">
        {{ $tournament->min_players }} на {{ $tournament->min_players }}
    </span>
    @auth
        @if(Auth::user()->isAdmin())
            <a class="btn btn-primary" href="{{ action('Site\GroupController@edit', ['tournamentId' => $tournament->id]) }}">
                <i class="fas fa-edit"></i>
            </a>
        @endif
    @endauth
</h2>
<p>Создан: {{ (new \DateTime($tournament->createdAt))->format('d.m.Y') }}</p>