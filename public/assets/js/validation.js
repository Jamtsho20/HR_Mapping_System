$(document).ready(function () {
    $(document).on('change', 'select[required], input[required], textarea[required]', function () {
        if ($(this).val() !== "") {
            removeMessage($(this));
        }
    });

    $(document).on('input', 'input[required], textarea[required]', function () {
        if ($(this).val() !== "") {
            removeMessage($(this));
        }
    });

    function addMessage(curField, message) {
        curField.addClass('validation-error');
        // Remove existing validation messages before adding new ones
        curField.closest('.form-group').find('.validation-message').remove();

        if (curField.closest('.form-group').find('.validation-message').length === 0) {
            curField.closest('.form-group').find('label').append(
                "<span style='color:red;' class='validation-message'> " + message + "</span>"
            );
        }

        if (curField.hasClass('select2')) {
            curField.closest('.form-group').find('.select2-container').addClass('validation-error');
        }
    }

    function removeMessage(curField) {
        curField.removeClass('validation-error');
        curField.closest('.form-group').find('.validation-message').remove();
        if (curField.hasClass('select2')) {
            curField.closest('.form-group').find('.select2-container').removeClass('validation-error');
        }
    }

    function validate(container) {
        var formValid = true;

        // Reset all error states before validating again
        container.find('.validation-error').removeClass('validation-error');
        container.find('.validation-message').remove();

        var requiredFields = container.find('input[required], select[required], textarea[required]');
        var regexFields = container.find("[data-regex]");
        var dateFields = container.find("input[type='date']");

        // Validate required fields
        requiredFields.each(function () {
            var curField = $(this);
            var fieldValue = $(this).val();

            if (curField.is('select')) {
                if (curField.find('option:selected').val() === "") {
                    formValid = false;
                    addMessage(curField, "This field is required!");
                }
            } else {
                if (fieldValue === "") {
                    formValid = false;
                    addMessage(curField, "This field is required!");
                }
            }
        });

        // Validate date fields
        dateFields.each(function () {
            var curField = $(this);
            var fieldValue = $(this).val();
            if (fieldValue !== '') {
                var date = new Date(fieldValue);
                var maxDate = new Date(curField.attr('max'));
                if (date > maxDate) {
                    formValid = false;
                    addMessage(curField, "Date must not be greater than " + curField.attr('max'));
                }
            }
        });

        // Validate regex fields
        regexFields.each(function () {
            var curField = $(this);
            var regCheck = curField.data('regex');
            var fieldValue = $(this).val();
            if (fieldValue.trim() !== "") {
                const regex = new RegExp(regCheck, 'gm');
                const str = fieldValue.trim();
                const result = regex.test(str);

                if (!result) {
                    formValid = false;
                    addMessage(curField, curField.data('message'));
                } else {
                    curField.removeClass('validation-error');
                    curField.closest('.form-group').find('.validation-message').remove();
                    if (curField.hasClass('select2')) {
                        curField.closest('.form-group').find('.select2-container').removeClass(
                            'validation-error');
                    }
                }
            } else {
                var isRequiredCheck = curField.attr('data-validation');
                if (isRequiredCheck !== undefined) {
                    curField.removeClass('validation-error');
                    curField.closest('.form-group').find('.validation-message').remove();
                    if (curField.hasClass('select2')) {
                        curField.closest('.form-group').find('.select2-container').removeClass(
                            'validation-error');
                    }
                    formValid = false;
                    addMessage(curField, "This field is required!");
                }
            }
        });

        return formValid;
    }
});
