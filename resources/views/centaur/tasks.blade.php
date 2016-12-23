@extends('Centaur::layout')

@section('title', 'Dashboard')



@section('content')


  <div class="container" v-cloak>
    <div class="row">
        <create-task></create-task>
        <task-list></task-list>
        <sidebar></sidebar>
    </div>
  </div>
@stop
