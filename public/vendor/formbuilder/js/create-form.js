jQuery(function () {
    $('#visibility').change(function (e) {
        e.preventDefault();
        const ref = $(this);

        if (ref.val() == "" || ref.val() == 'PUBLIC') {
            $('#allows_edit_DIV').hide()
        } else {
            $('#allows_edit_DIV').slideDown();
            $('#allows_edit').val('0')
        }
    });

    const AssocSelectModal = function (formInput, columnInput) {
        let formData = window.formData;

        const formSelect = $('#modal-assoc-form');
        const columnSelect = $('#modal-assoc-column');
        const model = $('#assoc-modal');

        // load column
        const loadColumns = function () {
            columnSelect.empty();
            if (formSelect.val() === '0') {
                columnSelect.append($('<option>', {
                    disabled: 'disabled',
                    selected: 'selected',
                    value: 0
                }).text('请先选择表单'));
                return;
            }
            $.each(formData.find(col => col.id == formSelect.val()).form, function (key, val) {
                columnSelect.append($('<option>', {value: val.name}).text(val.label));
            });
        };
        if (formInput.val()) {
            formSelect.val(formInput.val());
        }
        if (formSelect.val()) {
            loadColumns();
            columnSelect.val(columnInput.val());
        }
        formSelect.change(function () {
            loadColumns(formSelect.val());
        });
        $('#save-assoc-select').click(function () {
            formInput.val(formSelect.val());
            columnInput.val(columnSelect.val());
            model.modal('hide');
        });
        model.modal('show');
    };


    // create the form editor
    const fbEditor = $(document.getElementById('fb-editor'));
    let formBuilder;
    const fbOptions = {
        dataType: 'json',
        formData: window._form_builder_content ? window._form_builder_content : '',
        controlOrder: [
            'header',
            'paragraph',
            'text',
            'textarea',
            'select',
            'number',
            'date',
            'autocomplete',
            'file',
        ],
        disableFields: [
            'button', // buttons are not needed since we are the one handling the submission
        ],  // field types that should not be shown
        disabledAttrs: [
            'access',
        ],
        typeUserDisabledAttrs: {
            'file': [
                'multiple',
                'subtype',
            ],
            'checkbox-group': [
                'other',
            ],
        },
        typeUserAttrs: {
            select: {
                source: {
                    label: '数据源',
                    options: {
                        0: '自定义',
                        1: '关联其他表单数据'
                    }
                },
                formAssoc: {
                    label: '表单',
                    text: '',
                    placeholder: '点击选择要关联的表单',
                    disabled: 'disabled'
                },
                column: {
                    label: '数据段',
                    text: '',
                    placeholder: '请选择数据段',
                    disabled: 'disabled',
                }
            }
        },
        typeUserEvents: {
            select: {
                onadd: function (f) {
                    const field = $(f);
                    const fieldId = field.attr('id');
                    const fieldOptionsGroup = $(field.find('.field-options')[0]);
                    const SourceSelectGroup = $(field.find('.source-wrap')[0]);
                    const sourceSelect = $(field.find(`#source-${fieldId}`));
                    const formInputGroup = $(field.find('.formAssoc-wrap')[0]);
                    const formInput = $(formInputGroup.find(`#formAssoc-${fieldId}`));
                    const columnInputGroup = $(field.find('.column-wrap')[0]);
                    const columnInput = $(columnInputGroup.find(`#column-${fieldId}`));

                    // 没有可以使用的表单数据
                    if (!window.formData.length) {
                        // swal('无法使用关联','您没有可以关联的表单','warning');
                        SourceSelectGroup.hide();
                    }

                    if (sourceSelect.val() == 1) {
                        // 数据来源->server
                        fieldOptionsGroup.hide();
                        formInputGroup.show();
                        columnInputGroup.show();
                    } else {
                        // 数据来源->custom
                        fieldOptionsGroup.show();
                        formInputGroup.hide();
                        columnInputGroup.hide();
                    }

                    // 数据来源更改
                    sourceSelect.change(function () {
                        if (sourceSelect.val() === '1') {
                            fieldOptionsGroup.hide();
                            formInputGroup.show();
                            columnInputGroup.show();
                        } else {
                            fieldOptionsGroup.show();
                            formInputGroup.hide();
                            columnInputGroup.hide();
                        }
                    });

                    formInputGroup.click(() => AssocSelectModal(formInput, columnInput));
                    columnInputGroup.click(() => AssocSelectModal(formInput, columnInput));
                },

            }
        },
        showActionButtons: false, // show the actions buttons at the bottom
        disabledActionButtons: ['data'], // get rid of the 'getData' button
        sortableControls: false, // allow users to re-order the controls to their liking
        editOnAdd: false,
        fieldRemoveWarn: false,
        roles: window.FormBuilder.form_roles || {},
        i18n: window.FormBuilder.i18n || {},
        subtypes: {
            text: ['datetime-local']
        },
        notify: {
            error: function (message) {
                return swal('发生错误', message, 'error')
            },
            success: function (message) {
                return swal('成功', message, 'success')
            },
            warning: function (message) {
                return swal('警告', message, 'warning');
            }
        },
        // onSave: function () {
        // },
    };

    $.ajax({
        url: window.FormBuilder.forms_url,
        dataType: 'json',
        success: function (data) {
            // init assoc select modal
            if (window.FormBuilder.form_id) {
                // 去掉本表单
                const self_index = data.findIndex(x => x.id == window.FormBuilder.form_id);
                if (self_index > -1)
                    data.splice(self_index, 1);
            }
            window.formData = data;
            const formSelect = $('#modal-assoc-form');
            formSelect.append($('<option>', {disable: 'disable', selected: 'selected', value: 0}).text('请选择数据源表单'));

            $.each(data, function (key, val) {
                formSelect.append($('<option>', {value: val.id}).text(val.name));
            });

            formBuilder = fbEditor.formBuilder(fbOptions);
        }
    });

    const fbClearBtn = $('.fb-clear-btn');
    const fbShowDataBtn = $('.fb-showdata-btn');
    const fbSaveBtn = $('.fb-save-btn');

    // setup the buttons to respond to save and clear
    fbClearBtn.click(function (e) {
        e.preventDefault();

        if (!formBuilder.actions.getData().length) return;

        sConfirm("您确定清空所有已选择的数据段吗 ? ", function () {
            formBuilder.actions.clearFields()
        })
    });

    fbShowDataBtn.click(function (e) {
        e.preventDefault();
        formBuilder.actions.showData()
    });

    fbSaveBtn.click(function (e) {
        e.preventDefault();

        const form = $('#createFormForm');

        // make sure the form is valid
        if (!form.parsley().validate()) return;

        // make sure the form builder is not empty
        if (!formBuilder.actions.getData().length) {
            swal({
                title: "错误",
                text: "表单不能为空",
                icon: 'error',
            });
            return
        }

        // ask for confirmation
        sConfirm("是否保存表单 ?", function () {
            fbSaveBtn.attr('disabled', 'disabled');
            fbClearBtn.attr('disabled', 'disabled');

            const formBuilderJSONData = formBuilder.actions.getData('json');
            // console.log(formBuilderJSONData)
            // var formBuilderArrayData = formBuilder.actions.getData()
            // console.log(formBuilderArrayData)


            const postData = {
                name: $('#name').val(),
                visibility: $('#visibility').val(),
                allows_edit: $('#allows_edit').val(),
                form_builder_json: formBuilderJSONData,
                _token: window.FormBuilder.csrfToken
            };

            const method = form.data('formMethod') ? 'PUT' : 'POST';
            jQuery.ajax({
                url: form.attr('action'),
                processData: true,
                data: postData,
                method: method,
                cache: false,
            })
                .then(function (response) {
                    fbSaveBtn.removeAttr('disabled');
                    fbClearBtn.removeAttr('disabled');

                    if (response.success) {
                        // the form has been created
                        // send the user to the form index page
                        swal({
                            title: "表单保存成功!",
                            text: response.details || '',
                            icon: 'success',
                        });

                        setTimeout(function () {
                            window.location = response.dest
                        }, 1500);

                        // clear out the form
                        // $('#name').val('')
                        // $('#visibility').val('')
                        // $('#allows_edit').val('0')
                    } else {
                        swal({
                            title: "Error",
                            text: response.details || 'Error',
                            icon: 'error',
                        })
                    }
                }, function (error) {
                    handleAjaxError(error);

                    fbSaveBtn.removeAttr('disabled');
                    fbClearBtn.removeAttr('disabled')
                })
        })

    });

    // show the clear and save buttons
    $('#fb-editor-footer').slideDown()
});
