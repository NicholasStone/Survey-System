@extends('formbuilder::layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{ __('formbuilder.title.forms') }}

                            <div class="btn-toolbar float-md-right" role="toolbar">
                                <div class="btn-group" role="group" aria-label="Third group">
                                    <a href="{{ route('formbuilder::forms.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus-circle"></i> {{ __('formbuilder.title.create-new-form') }}
                                    </a>

                                    <a href="{{ route('formbuilder::my-submissions.index') }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fa fa-th-list"></i> {{ __('formbuilder.title.my-submissions') }}
                                    </a>
                                </div>
                            </div>
                        </h5>
                    </div>

                    @if($forms->count())
                        <div class="table-responsive">
                            <table class="table table-bordered d-table table-striped pb-0 mb-0">
                                <thead>
                                <tr>
                                    <th class="five">#</th>
                                    <th>{{ __('formbuilder.form.index.name') }}</th>
                                    <th class="ten">{{ __('formbuilder.form.index.submissions') }}</th>
                                    <th class="fifteen">{{ __('formbuilder.form.index.allow-edit') }}</th>
                                    <th class="ten">{{ __('formbuilder.form.index.submissions') }}</th>
                                    <th class="twenty-five">{{ __('formbuilder.form.index.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($forms as $form)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $form->name }}</td>
                                        <td>{{ $form->visibility }}</td>
                                        <td>{{ $form->allowsEdit() ? __('formbuilder.form.create-edit.yes') : __('formbuilder.form.create-edit.no') }}</td>
                                        <td>{{ $form->submissions_count }}</td>
                                        <td>
                                            <a href="{{ route('formbuilder::forms.submissions.index', $form) }}"
                                               class="btn btn-primary btn-sm"
                                               title="{{ __('formbuilder.form.index.view-submission-for-form', ['form', $form->name]) }}">
                                                <i class="fa fa-th-list"></i> {{ __('formbuilder.form.index.data') }}
                                            </a>
                                            <a href="{{ route('formbuilder::forms.show', $form) }}"
                                               class="btn btn-primary btn-sm"
                                               title="{{ __('formbuilder.form.index.preview-form', ['form' => $form->name]) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('formbuilder::forms.edit', $form) }}"
                                               class="btn btn-primary btn-sm"
                                               title="{{ __('formbuilder.form.index.edit-form') }}">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button class="btn btn-primary btn-sm clipboard"
                                                    data-clipboard-text="{{ route('formbuilder::form.render', $form->identifier) }}"
                                                    data-message="" data-original=""
                                                    title="{{ __('formbuilder.title.copy-url') }}">
                                                <i class="fa fa-clipboard"></i>
                                            </button>

                                            <form action="{{ route('formbuilder::forms.destroy', $form) }}"
                                                  method="POST" id="deleteFormForm_{{ $form->id }}"
                                                  class="d-inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm confirm-form"
                                                        data-form="deleteFormForm_{{ $form->id }}"
                                                        data-message="{{ __('formbuilder.form.index.delete-form', ['$form' => $form->name]) }}?"
                                                        title="{{ __('formbuilder.form.index.delete-form', ['form'=>$form->name]) }}'">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($forms->hasPages())
                            <div class="card-footer mb-0 pb-0">
                                <div>{{ $forms->links() }}</div>
                            </div>
                        @endif
                    @else
                        <div class="card-body">
                            <h4 class="text-danger text-center">
                                {{ __('formbuilder.form.index.no-data-to-display') }}
                            </h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
