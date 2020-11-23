@extends('layouts.login')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Payment Details</h1>
</section>
<form action='' method='post'>
    @csrf
    @if($errors->any())
    <ul>
        @foreach($errors->all() as $error)
        <li> {{ $error }} </li>
        @endforeach
        @endif

        @if( session( 'success' ) )
        {{ session( 'success' ) }}
        @endif

        <label>Phone numbers (seperate with a comma [,])</label>
        <input type='text' name='numbers' />

        <label>Message</label>
        <textarea name='message'></textarea>

        <button type='submit'>Send!</button>
</form>
<!-- /.content -->
@endsection