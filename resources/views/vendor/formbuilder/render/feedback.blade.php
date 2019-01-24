@extends('formbuilder::layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rounded-0">
                <div class="card-header">
                    <h5 class="card-title">
                        {{ __('formbuilder.form.submissions.submit-success') }}
                        @auth
                            <a href="{{ route('formbuilder::my-submissions.index') }}" class="btn btn-primary btn-sm float-md-right">
                                <i class="fa fa-th-list"></i> {{ __('formbuilder.form.submissions.back-to-my-submission') }}
                            </a>
                        @endauth
                    </h5>
                </div>

                <div class="card-body">
                    <h3 class="text-center text-success">
                        {{ __('formbuilder.form.submissions.submit-success-message', ['form' => $form->name]) }}
                    </h3>
                </div>

                <div class="card-footer">
                    <a href="{{ route('home') }}" class="btn btn-primary confirm-form">
                        <i class="fa fa-home"></i> {{ __('formbuilder.common.return-home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
