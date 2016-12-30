@extends('Centaur::layout')

@section('title', 'Dashboard')



@section('content')


  <div class="container" v-cloak>
    <div class="row">
  	@if (Sentinel::check())
		<create-task></create-task>
		<task-list></task-list>
		<sidebar></sidebar>
	@else
		<p>Please Log In</p>
	@endif
    </div>
  </div>
@stop
