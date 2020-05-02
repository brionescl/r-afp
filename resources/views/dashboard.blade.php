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
            }

            .band {
                width: 90%;
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
                font-size: 20px;
                margin: 0;
                color: #333;
            }

            .card p {
                line-height: 1.4;
            }
        </style>
    </head>
    <body>
        <div class="band">
            @foreach ($fundAdministrators as $fundAdministrator)
                <div class="item-1">
                    <div class="card">
                        <article>
                            <h1>{{ $fundAdministrator->name }}</h1>
                            @foreach ($investmentFunds as $investmentFund)
                                <p>
                                    <b>{{ $investmentFund }}</b>:
                                    {{ $rentability[$fundAdministrator->name][$investmentFund] }}
                                </p>
                            @endforeach
                        </article>
                    </div>
                </div>
            @endforeach
        </div>
    </body>
</html>
