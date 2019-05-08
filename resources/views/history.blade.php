@extends('layouts.master')

@section('content')
    <div class='alert-light border-top border-dark font-weight-bold'>
        <form method='GET' action='/history'>
            <fieldset>
                <legend>Filter Criteria:</legend>
                <label for='sourceCurrencyId'>Source Currency: </label>
                <select name='sourceCurrencyId' id='sourceCurrencyId'>
                    <option value=''>Choose</option>
                    @foreach ($currency_list as $currency)
                        <option value='{{ $currency['id'] }}'
                        @if (isset($source) && $source == $currency['id'])
                            {{'selected'}}
                                @endif
                        >{{$currency['code']}}
                        </option>
                    @endforeach
                </select>
                <br>
                <label for='targetCurrencyId'>Target Currency: </label>
                <select name='targetCurrencyId' id='targetCurrencyId'>
                    <option value=''>Choose</option>
                    @foreach ($currency_list as $currency)

                        <option value='{{ $currency['id'] }}'
                        @if (isset($target) && $target == $currency['id'])
                            {{'selected'}}
                                @endif
                        >{{$currency['code']}}
                        </option>
                    @endforeach
                </select>
                <br>
                <input type='submit' value='Filter' class='btn btn-primary mb-2'>
                <br>
            </fieldset>
        </form>
    </div>
    <div class="container">
        <div class="row alert-info border-top border-bottom border-dark font-weight-bold">
            <div class="col"> Source Amount</div>
            <div class="col"> Source Currency</div>
            <div class="col"> Target Currency</div>
            <div class="col"> Rate</div>
            <div class="col"> Converted Amount</div>
            <div class="col"> Time</div>
            <div class="col"></div>
        </div>
        @foreach ($conversions as $conv)
            <div class="row history">
                <div class="col">{{ $conv->source_amount }}</div>
                <div class="col">{{ $conv->sourceCurrency->code }}</div>
                <div class="col">{{ $conv->targetCurrency->code }}</div>
                <div class="col">
                    <form method='POST' action='/history'>
                        {{method_field('PUT')}}
                        {{ csrf_field() }}
                        <input type='hidden' name='id' value='{{ $conv->id }}'/>
                        <input type='hidden' name='amount' value='{{ $conv->source_amount }}'/>
                        <input class="m-2 " name="rate" placeholder="Rate" value='{{$conv->rate}}'>
                        <button class="btn btn-success m-2" type="submit">Update</button>
                    </form>
                    @if (isset($errors) && count($errors) > 0 && old('id') == $conv->id)
                        <div class='alertbox alert-danger font-weight-bold mb-2'>
                            {{ $errors->first() }}
                        </div>
                    @endif
                </div>
                <div class="col">{{ $conv->converted_amount }}</div>
                <div class="col">{{ $conv->time_stamp }}</div>
                <div class="col">
                    <form method='POST' action='/history'>
                        {{method_field('DELETE')}}
                        {{ csrf_field() }}
                        <input type='hidden' name='id' value='{{ $conv->id }}'/>
                        <button class="btn btn-dark m-2" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <a href='/' class='btn btn-danger refresh'>Home</a>
@endsection







