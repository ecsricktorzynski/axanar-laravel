@extends('layouts.app')

@section('mediaTitle', 'Admin Dashboard')

@section('content')
    <?php showMessages(); ?>

    <div class="list-group">
        <h3>Admin Areas</h3>
    </div>

    <div class="container">
        <ul class="list-group">
            <li class="list-group-item"><a href="/admin/members">Members</a></li>
            <li class="list-group-item"><a href="/admin/campaigns">Campaigns</a></li>
        </ul>
    </div>
@endsection
