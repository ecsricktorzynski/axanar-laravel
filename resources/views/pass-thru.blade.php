<?php
/**
 * @var string $script
 */
?>
@extends('layouts.app')

@section('content')
    {{ require_once($script) }}
@endsection
