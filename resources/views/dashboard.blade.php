<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>R-AFP</title>
        <style>
            html {
                background: #f5f7f8;
                font-family: 'Roboto', sans-serif;
                -webkit-font-smoothing: antialiased;
                padding: 20px 0;
                font-size: 12px;
            }
            .band {
                width: 60%;
                max-width: 1240px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                grid-gap: 20px;
            }
            @media only screen and (min-width: 500px) {
                .band {
                    grid-template-columns: 1fr 1fr;
                }
            }
            @media only screen and (min-width: 850px) {
                .band {
                    grid-template-columns: 1fr 1fr 1fr 1fr;
                }
            }
            .card {
                background: white;
                text-decoration: none;
                color: #444;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                display: flex;
                flex-direction: column;
                min-height: 100%;
                position: relative;
                top: 0;
                transition: all .1s ease-in;
            }
            .card:hover {
                top: -2px;
                box-shadow: 0 4px 5px rgba(0,0,0,0.2);
            }
            .card article {
                padding: 20px;
            }
            .card h1 {
                font-size: 15px;
                margin: 0;
                color: #7999e4;
                text-transform: lowercase;
            }
            .card h1::first-letter {
                text-transform: uppercase;
            }
            .card p {
                line-height: 0.5;
                padding-left: 10px;
            }
            .form {
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }
            .form select {
                width: 100px;
                font: inherit;
                font-size: 15px;
            }
            .sync {
                display: block;
                text-align: center;
                justify-content: center;
            }
            .sync h1 {
                font-size: 14px;
                color: #444;
            }
            .btn {
                border: none;
                padding: 5px 15px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                border-radius: 4px;
                cursor: pointer;
            }
            .btn:hover {
                box-shadow: 0 4px 5px rgba(0,0,0,0.2);
            }
            .btn-primary {
                color: white;
                background-color: #008CBA;
            }
            .btn-secondary {
                color: white;
                background-color: #4CAF50;
            }
        </style>
    </head>
    <body>
        <div class="form">
            {!! Form::open(['url' => 'dashboard', 'method' => 'get', 'id' => 'formSelect']) !!}
            {!! Form::select('month', $months, $monthSelected) !!}
            {!! Form::select('year', $years, $yearSelected) !!}
            {!! Form::submit('Seleccionar', ['class' => 'btn btn-primary', 'id' => 'btnSelect']) !!}
            {!! Form::close() !!}
        </div>
        <div class="band">
            @foreach ($rentabilities as $fundAdministrator => $items)
                <div class="item-{{ $fundAdministrator }}">
                    <div class="card">
                        <article>
                            <h1>{{ $fundAdministrator }}</h1>
                            @foreach ($items as $item)
                                <p>
                                    <b>{{ $item->investment_fund }}</b>: {{ $item->rentability }}
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
                {!! Form::button('Sincronizar', ['class' => 'btn btn-secondary', 'onclick' => 'sync()']) !!}
            </div>
        @endif
    </body>
    <script>
        const url = '{{ url('/') }}'
        document.getElementById('formSelect').addEventListener('submit', function(event) {
            event.preventDefault();
            this.btnSelect.disabled = true
            window.location.replace(url + `/dashboard/${this.year.value}/${this.month.value}`)
        })
        sync = function() {
            const year = document.getElementById('formSelect').year.value
            const month = document.getElementById('formSelect').month.value
            window.location.replace(url + `/dashboard/${year}/${month}/sync`)
        }
    </script>
</html>
