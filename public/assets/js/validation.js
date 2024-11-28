var tashicellHrmsValidation = function () {

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

    function validateField(curField) {
        var fieldValue = curField.val();
        var formValid = true;

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
        return formValid;
    }

    function validateDateField(curField) {
        var formValid = true;
        var fieldValue = curField.val();
        if (fieldValue !== '') {
            var date = new Date(fieldValue);
            var maxDate = new Date(curField.attr('max'));
            if (date > maxDate) {
                formValid = false;
                addMessage(curField, "Date must not be greater than " + curField.attr('max'));
            }
        }
        return formValid;
    }

    function validateRegexField(curField) {
        var formValid = true;
        var regCheck = curField.data('regex');
        var fieldValue = curField.val();
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
                    curField.closest('.form-group').find('.select2-container').removeClass('validation-error');
                }
            }
        } else {
            var isRequiredCheck = curField.attr('data-validation');
            if (isRequiredCheck !== undefined) {
                curField.removeClass('validation-error');
                curField.closest('.form-group').find('.validation-message').remove();
                if (curField.hasClass('select2')) {
                    curField.closest('.form-group').find('.select2-container').removeClass('validation-error');
                }
                formValid = false;
                addMessage(curField, "This field is required!");
            }
        }
        return formValid;
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
            if (!validateField($(this))) {
                formValid = false;
            }
        });

        // Validate date fields
        dateFields.each(function () {
            if (!validateDateField($(this))) {
                formValid = false;
            }
        });

        // Validate regex fields
        regexFields.each(function () {
            if (!validateRegexField($(this))) {
                formValid = false;
            }
        });

        return formValid;
    }

    function initialize() {
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
    }

    return {
        Validate: validate,
        Initialize: initialize
    }
}();

$(document).ready(function () {
    tashicellHrmsValidation.Initialize();
});
