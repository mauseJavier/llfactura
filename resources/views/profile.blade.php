
@extends('layouts.app')

@section('main')

<div style="display: grid; place-items: center;">

    <article style="width: 70%">
        <livewire:profile.update-profile-information-form />
    </article>   
    
    
    <article style="width: 70%">
        <livewire:profile.update-password-form />
    </article>

</div>



{{-- <livewire:profile.delete-user-form /> --}}

<div class="container">

    <livewire:crear-ver-token />
</div>






    
@endsection

