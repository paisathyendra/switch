@extends('master')

@section('content')
    <form action="/recommended" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label>Genre :</label>
            <input required="required" value="{{ old('genre') }}" placeholder="Enter Genre here" type="text"
                   name="genre" class="form-control"/>
        </div>
        <div class="form-group">
            <label>Time :</label>
            <input required="required" value="{{ old('time') }}" placeholder="Enter Time here" type="text"
                   name="time" class="form-control"/>
        </div>
        <input type="submit" name='search' class="btn btn-success" value="Recommended Movies"/>
    </form>
@endsection