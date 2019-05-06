@extends('layouts.master')

@section('content')
    <form method='GET' action='/convert'>
        <div class='row'>
            <div class='col text-right'>
                Enter currency amount:
            </div>
            <div class='col text-left'>
                <input type='text' name='amount' value='{{old('amount', $amount)}}'/>
            </div>
            <br>
        </div>
        @if($errors->get('amount'))
            <br>
            <div class='alertbox alert-danger font-italic'>
                {{ $errors->first('amount') }}
            </div>
        @endif
        <br>
        <div class='row'>
            <div class='col text-right'>
                Choose currency:
            </div>
            <div class='col text-left'>
                <select name='current' >
                    @foreach ($currency_list as $code => $value)
                        <option value='{{ $code }}'
                            @if ($code == old('current', $current))
                                {{'selected'}}
                            @endif
                            >{{$value}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <br>
        <div class='row'>
            <div class='col text-right'>
                Choose currency to convert to:
            </div>
            <div class='col text-left'>
                <select name='target' >
                    @foreach ($currency_list as $code => $value)
                        <option value='{{ $loop->index }}'
                            @if ($loop->index == old('target', $target))
                                {{'selected'}}
                            @endif
                            >{{$value}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <br>
        <div class='row'>
            <div class='col text-right'>
                Round value to nearest whole number?
            </div>
            <div class='col text-left'>
                <input type='checkbox' name='round' value='true'
                    @if(old('round', $round))
                        {{'checked'}}
                    @endif
                >
            </div>
        </div>
        <br>
        <input type='submit' value='Convert' class='btn btn-primary'>
        <br>
    </form>
    @if (isset($converted) && (!isset($errors) || count($errors) == 0))
        <div class='alertbox alert-info font-weight-bold'>
            The converted amount is: {{$converted }}
        </div>
    @endif
    <a href='/refresh' class='btn btn-success refresh'>Refresh Rates and Reset Form</a>
@endsection







