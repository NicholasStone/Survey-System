@extends('formbuilder::layout')

@section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{ __('formbuilder.title.preview-form', ['form' => $form->name]) }}

                            <div class="btn-toolbar float-md-right" role="toolbar">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('formbuilder::forms.index') }}"
                                       class="btn btn-primary float-md-right btn-sm">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                    <a href="{{ route('formbuilder::forms.submissions.index', $form) }}"
                                       class="btn btn-primary float-md-right btn-sm">
                                        <i class="fa fa-th-list"></i> {{ __('formbuilder.form.index.submissions') }}
                                    </a>
                                    <a href="{{ route('formbuilder::forms.edit', $form) }}"
                                       class="btn btn-primary float-md-right btn-sm">
                                        <i class="fa fa-edit"></i> {{ __('formbuilder.title.edit') }}
                                    </a>
                                    <a href="{{ route('formbuilder::forms.create') }}"
                                       class="btn btn-primary float-md-right btn-sm">
                                        <i class="fa fa-plus-circle"></i> {{ __('formbuilder.title.create-new-form') }}
                                    </a>
                                </div>
                            </div>
                        </h5>
                    </div>

                    <div class="card-body">
                        <div id="fb-render"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">
                            Details

                            <button class="btn btn-primary btn-sm clipboard float-right"
                                    data-clipboard-text="{{ route('formbuilder::form.render', $form->identifier) }}"
                                    data-message="{{ __('formbuilder.title.link-copied') }}"
                                    data-original="{{ __('formbuilder.title.copy-url') }}"
                                    title="{{ __('formbuilder.title.copy-form-link') }}">
                                <i class="fa fa-clipboard"></i> {{ __('formbuilder.title.copy-form-link') }}
                            </button>
                        </h5>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.show.public-url') }}:</strong>
                            <a href="{{ route('formbuilder::form.render', $form->identifier) }}" class="float-right"
                               target="_blank">
                                {{$form->identifier}}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.index.visibility') }} </strong> <span
                                    class="float-right">{{ $form->visibility }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.index.allow-edit') }} </strong>
                            <span class="float-right">{{ $form->allowsEdit() ? __('formbuilder.form.create-edit.yes') : __('formbuilder.form.create-edit.no') }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.show.owner') }} </strong> <span class="float-right">{{ $form->user->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.show.current-submissions') }} </strong>
                            <span class="float-right">{{ $form->submissions_count }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.show.last-updated-on') }} </strong>
                            <span class="float-right">
                            {{ $form->updated_at->toDayDateTimeString() }}
                        </span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.show.created-on') }} </strong>
                            <span class="float-right">
                            {{ $form->created_at->toDayDateTimeString() }}
                        </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push(config('formbuilder.layout_js_stack', 'scripts'))
<script type="text/javascript">
    window._form_builder_content = eval({!! json_encode($form->form_builder_json) !!});
</script>
<script src="{{ asset('vendor/formbuilder/js/preview-form.js') }}{{ jazmy\FormBuilder\Helper::bustCache() }}"
        defer></script>
@endpush
