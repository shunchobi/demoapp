@extends('layouts.app')

@section('content')

@if (count($non_registered_cards) > 0)
<div>
    <table>
        <thead>
            <tr>
                <td>IDm Number</td>
                <td>Name</td>
            </tr>
        </thead>
        <tbody>
            <form action="{{ route('management.store') }}" method="post" id="idm-form">
            @csrf
            @foreach ($non_registered_cards as $card)
            <tr>
                <td> 
                    <lavel name="selected_user_id[]" for="{{ $card->idm }}">{{ $card->idm }} {{ $card->touched_at }}</lavel> 
                </td>
                <td>
                    <input type="hidden" name="card_id[]" value="{{ $card->id }}"/>
                    <select name="selected_user_id[]">
                        <option value="" selected>---</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            @endforeach
        </tbody>
        </form>
    </table>
    <button form="idm-form" type="submit">OK</button>
</div>
@endif

@endsection