@extends('layouts.app')

@section('content')

    <div class="container">
        <ol>

            <li>Получаем обновленные города через timestamp <a href="{{ route('cityapi','1616039490') }}">json</a>   </li>
            <li>Получаем обновленные районы через timestamp <a href="{{ route('districtapi','1619768990') }}">json</a>   </li>

        </ol>

    </div>
@endsection
