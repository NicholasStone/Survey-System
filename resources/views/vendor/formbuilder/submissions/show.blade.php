@extends('formbuilder::layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">
                            Viewing Submission #{{ $submission->id }} for form '{{ $submission->form->name }}'
                            {{ __('formbuilder.form.submissions.view-submission-detail', ['form' => $submission->form->name, 'n' => $submission->id]) }}

                            <div class="btn-toolbar float-right" role="toolbar">
                                <div class="btn-group" role="group" aria-label="First group">
                                    <a href="{{ route('formbuilder::forms.submissions.index', $submission->form->id) }}"
                                       class="btn btn-primary float-md-right btn-sm" title="{{ __('formbuilder.form.submissions.back-to-my-submission') }}">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                    <form action="{{ route('formbuilder::forms.submissions.destroy', [$submission->form, $submission]) }}"
                                          method="POST" id="deleteSubmissionForm_{{ $submission->id }}"
                                          class="d-inline-block">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm rounded-0 confirm-form"
                                                data-form="deleteSubmissionForm_{{ $submission->id }}"
                                                data-message="{{ __('formbuilder.form.submissions.delete-submission') }}" title="{{ __('formbuilder.form.submissions.delete-submission-message') }}?">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </h5>
                    </div>

                    <ul class="list-group list-group-flush">
                        @foreach($form_headers as $header)
                            <li class="list-group-item">
                                <strong>{{ $header['label'] ?? title_case($header['name']) }}: </strong>
                                <span class="float-right">
                                {{ $submission->renderEntryContent($header['name'], $header['type']) }}
                            </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card rounded-0">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('formbuilder.form.show.details') }}</h5>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.form-name') }}: </strong>
                            <span class="float-right">{{ $submission->form->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.common.user-name') }}: </strong>
                            <span class="float-right">{{ $submission->user->name ?? 'Guest' }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.show.last-updated-on') }}: </strong>
                            <span class="float-right">{{ $submission->updated_at->toDayDateTimeString() }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __('formbuilder.form.submissions.submitted-on') }}: </strong>
                            <span class="float-right">{{ $submission->created_at->toDayDateTimeString() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
