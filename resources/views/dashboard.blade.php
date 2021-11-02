<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
        <title>R-AFP</title>
    </head>
    <body>
        <div class="form">
            {!! Form::open(['url' => 'dashboard', 'method' => 'get', 'id' => 'formSelect']) !!}
            {!! Form::button(
                '<em class="fas fa-arrow-circle-left"></em>',
                ['class' => 'btn btn-default', 'id' => 'previousBtn', 'onclick' => 'previous()']
            ) !!}
            {!! Form::select('month', $months, $monthSelected, ['onchange' => 'reload()']) !!}
            {!! Form::select('year', $years, $yearSelected, ['onchange' => 'reload()']) !!}
            {!! Form::button(
                '<em class="fas fa-arrow-circle-right"></em>',
                ['class' => 'btn btn-default', 'id' => 'nextBtn', 'onclick' => 'next()']
            ) !!}
            {!! Form::close() !!}
        </div>
        <div class="band">
            @foreach ($rentabilities as $fundAdministrator => $items)
                <div id="{{ $fundAdministrator }}" class="fund-administrator">
                    <div class="card">
                        <article>
                            <h1>{{ $fundAdministrator }}</h1>
                            @foreach ($items as $item)
                                <p>
                                    <strong>{{ $item->investment_fund }}</strong>:
                                    <label class="{!! $item->rentability < 0 ? 'red-text' : 'green-text' !!}">
                                        {{ $item->rentability }}
                                    </label>
                                </p>
                            @endforeach
                        </article>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($rentabilities->isEmpty())
            <div class="sync">
                <h1>No existen datos</h1>
                {!! Form::button(
                    '<em class="fas fa-sync-alt"></em> Sincronizar',
                    ['class' => 'btn btn-secondary', 'onclick' => 'sync()']
                ) !!}
            </div>
        @endif
        <div class="graph">
            <canvas id="myChart"></canvas>
        </div>
    </body>
    <script type="text/javascript">
        localStorage.setItem('url', '{{ url('/') }}')
        localStorage.setItem('rentabilitiesLast12Months', JSON.stringify({!! json_encode($rentabilitiesLast12Months) !!}))
        localStorage.setItem('months', JSON.stringify({!! json_encode($months) !!}))
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
</html>
