@extends('formbuilder::layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        {{ $pageTitle ?? '' }}

                        <a href="{{ route('formbuilder::forms.index') }}" class="btn btn-sm btn-primary float-md-right">
                            <i class="fa fa-arrow-left"></i> 返回到我的表单
                        </a>
                    </h5>
                </div>

                <form action="{{ route('formbuilder::forms.store') }}" method="POST" id="createFormForm">
                    @csrf 
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">表单名称</label>

                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="输入表单名称">

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="visibility" class="col-form-label">表单可见性</label>

                                    <select name="visibility" id="visibility" class="form-control" required="required">
                                        <option value="">选择表单可见性</option>
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
                                        提交后是否可再次编辑
                                    </label>

                                    <select name="allows_edit" id="allows_edit" class="form-control" required="required">
                                        <option value="0">不允许</option>
                                        <option value="1">允许</option>
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
                                    请<b>点击</b>或<b>拖动</b>组件至主面板以编辑表单
                                </div>

                                <div id="fb-editor" class="fb-editor"></div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-footer" id="fb-editor-footer" style="display: none;">
                    <button type="button" class="btn btn-primary fb-clear-btn">
                        <i class="fa fa-remove"></i> 清空面板
                    </button> 
                    <button type="button" class="btn btn-primary fb-save-btn">
                        <i class="fa fa-save"></i> 提交并保存
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push(config('formbuilder.layout_js_stack', 'scripts'))
    <script type="text/javascript">
        window.FormBuilder = window.FormBuilder || {}
        window.FormBuilder.form_roles = @json($form_roles);
    </script>
    <script src="{{ asset('vendor/formbuilder/js/create-form.js') }}{{ jazmy\FormBuilder\Helper::bustCache() }}" defer></script>
@endpush
