@extends('layouts.app')

@section('content')

<div>
    <form action="{{ route('management.createUser') }}" method="post">
        @csrf
        <label for="new_user_name_id">新しいユーザーを追加：</label>
        <input type="text" placeholder="名前を入力してください" name="new_user_name" id="new_user_name_id">
        <button type="submit">OK</button>
    </form>
</div>

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
            <form action="{{ route('management.updateUserId') }}" method="post" id="idm-form">
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

<div>
    <form action="{{ route('management.createManualStartEndTime') }}" method="post">
        @csrf
        <p>出勤退勤時間を追加する</p>

        <label for="name_manual">名前を選択</label>
        <select name="user_id" id="name_manual">
            <option value="" selected>---</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <label for="datetime">日時を選択</label>
        <input type="datetime-local" name="datetime" id="datetime">

        <label for="start_end_manual">出退勤を選択</label>
        <select name="start_or_end" id="start_end_manual">
            <option value="" selected>---</option>

            @foreach ($start_end as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>

        <button type="submit">OK</button>
    </form> 
</div>


<div>
    <form action="{{ route('export') }}" method="get">
        @csrf
        <select name="selected_y_m">
            <option value="" selected>---</option>
            @foreach ($exist_y_m as $y_m)
                <option value="{{ $y_m->year_month }}">{{ $y_m->year_month }}</option>
            @endforeach
        </select>
        <button type="submit">選択した年月の勤怠データをダウンロード(.csv)</button>
    </form>
</div>


@endsection
