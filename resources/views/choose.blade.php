@extends('layouts.master')

@section('content')
    <form method='POST' action='/choose'>
        {{ csrf_field() }}
        @foreach($currency_list as $currency)
            <ul>
                <li>
                    <label>
                        <input {{$currency['display'] ? 'checked' : ''}}
                            type='checkbox' name='currencies[]' value='{{ $currency['id'] }}'>{{ $currency['name'] }}
                    </label>
                </li>
            </ul>
        @endforeach
        <input type='submit' value='Save' class='btn btn-primary'>
         <a href='/' class='btn btn-primary'>Cancel</a>
        <br>
    </form>
    @if (isset($errors) && count($errors) > 0)
        <div class='alertbox alert-danger font-weight-bold'>
            At least one currency must be selected.
        </div>
    @endif
@endsection







