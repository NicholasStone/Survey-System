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
                // TODO 从候选列表中删去自己
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
            // 'access',
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
                    label: '',
                    options: {
                        0: 'Custom',
                        1: 'Associated'
                    }
                },
                formAssoc: {
                    label: '表单',
                    // options: [],
                    text: '',
                    placeholder: '请选择表单',
                    disabled: 'disabled'
                },
                column: {
                    label: '数据段',
                    // options: [],
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
                    const sourceSelect = $(field.find(`#source-${fieldId}`));
                    const formInputGroup = $(field.find('.formAssoc-wrap')[0]);
                    const formInput = $(formInputGroup.find(`#formAssoc-${fieldId}`));
                    const columnInputGroup = $(field.find('.column-wrap')[0]);
                    const columnInput = $(columnInputGroup.find(`#column-${fieldId}`));

                    if (sourceSelect.val() == 1) {
                        // 数据来源->server
                        fieldOptionsGroup.hide();
                        formInputGroup.show();
                        columnInputGroup.show();
                    } else {
                        // 数据来源->custom
                        // fieldOptionsGroup.show();
                        formInputGroup.hide();
                        columnInputGroup.hide();
                    }

                    // 数据来源更改
                    sourceSelect.change(function () {
                        if (sourceSelect.val()) {
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
                return swal('Error', message, 'error')
            },
            success: function (message) {
                return swal('Success', message, 'success')
            },
            warning: function (message) {
                return swal('Warning', message, 'warning');
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

        sConfirm("Are you sure you want to clear all fields from the form?", function () {
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
                title: "Error",
                text: "The form builder cannot be empty",
                icon: 'error',
            });
            return
        }

        // ask for confirmation
        sConfirm("Save this form definition?", function () {
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
                            title: "Form Saved!",
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
