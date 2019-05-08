@extends('layouts.master')

@section('content')
    <hr>
    <h2>Select Currencies</h2>
    <form method='POST' action='/choose'>
        <div class='alert-light border-top border-dark border-bottom font-weight-bold'>
            {{ csrf_field() }}
            <div class='container'>
                <div class='row'>
                    <div class='col mt-2'>
                        @foreach($currency_list as $currency)
                            @if(($loop->index > 0) && $loop->index % (count($currency_list)/2) == 0)
                    </div>
                    <div class='col  mt-2'>
                        @endif
                            <label>
                                <input {{$currency['display'] ? 'checked' : ''}} type='checkbox' name='currencies[]'
                                       value='{{ $currency['id'] }}'>{{ $currency['name'] }}
                            </label>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <input type='submit' value='Save' class='btn btn-primary mb-2 mt-2'>
        <a href='/' class='btn btn-primary mb-2 mt-2'>Cancel</a>
        <br>
    </form>
    @if (isset($errors) && count($errors) > 0)
        <div class='alertbox alert-danger font-weight-bold'>
            At least one currency must be selected.
        </div>
    @endif
@endsection







