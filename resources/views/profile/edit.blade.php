@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="h4 mb-4">Profile</h2>

                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
