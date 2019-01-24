@extends('formbuilder::layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{ $pageTitle ?? '' }}

                            <a href="{{ route('formbuilder::forms.index') }}"
                               class="btn btn-sm btn-primary float-md-right">
                                <i class="fa fa-arrow-left"></i> {{ __('formbuilder.title.back-to-my-form') }}
                            </a>
                        </h5>
                    </div>

                    <form action="{{ route('formbuilder::forms.store') }}" method="POST" id="createFormForm">
                        @csrf

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name"
                                               class="col-form-label">{{ __('formbuilder.form.create-edit.form-name') }}</label>

                                        <input id="name" type="text"
                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               name="name" value="{{ old('name') }}" required autofocus
                                               placeholder="{{ __('formbuilder.form.create-edit.enter-form-name') }}">

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="visibility"
                                               class="col-form-label">{{ __('formbuilder.form.create-edit.form-visibility') }}</label>

                                        <select name="visibility" id="visibility" class="form-control"
                                                required="required">
                                            <option value="">{{__('formbuilder.form.create-edit.select-form-visibility')}}</option>
                                            @foreach(jazmy\FormBuilder\Models\Form::$visibility_options as $option)
                                                <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('visibility'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('visibility') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4" style="display: none;" id="allows_edit_DIV">
                                    <div class="form-group">
                                        <label for="allows_edit" class="col-form-label">
                                            {{ __('formbuilder.form.create-edit.allow-submission-edit') }}
                                        </label>

                                        <select name="allows_edit" id="allows_edit" class="form-control"
                                                required="required">
                                            <option value="0">{{ __('formbuilder.form.create-edit.no') }}</option>
                                            <option value="1">{{ __('formbuilder.form.create-edit.yes') }}</option>
                                        </select>

                                        @if ($errors->has('allows_edit'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('allows_edit') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info" role="alert">
                                        <i class="fa fa-info-circle"></i>
                                        {{ __('formbuilder.form.create-edit.drag-and-drop') }}
                                    </div>

                                    <div id="fb-editor" class="fb-editor"></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-footer" id="fb-editor-footer" style="display: none;">
                        <button type="button" class="btn btn-primary fb-clear-btn">
                            <i class="fa fa-remove"></i> {{ __('formbuilder.form.create-edit.clear-form') }}
                        </button>
                        <button type="button" class="btn btn-primary fb-save-btn">
                            <i class="fa fa-save"></i> {{ __('formbuilder.form.create-edit.submit-and-save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('formbuilder::partials.model')
@endsection

@push(config('formbuilder.layout_js_stack', 'scripts'))
<script type="text/javascript">
    window.FormBuilder = window.FormBuilder || {};
    window.FormBuilder.form_roles = @json($form_roles);
    window.FormBuilder.forms_url = '{{ route("formbuilder::api.forms") }}'
</script>
<script src="{{ asset('vendor/formbuilder/js/create-form.js') }}{{ jazmy\FormBuilder\Helper::bustCache() }}" defer></script>
@endpush
