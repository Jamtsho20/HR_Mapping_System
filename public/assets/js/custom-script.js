var hrms = function() {
    function initialize() {
        //Ajax to fetch gewogs based on dzongkhag section
        $(document).on("change", "#dzongkhag_search", function() {
            var dzongkhagId = $("#dzongkhag_search").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select Dzongkhag</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#gewog_search").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        $(document).on("change", "#dzongkhag_id", function() {
            var dzongkhagId = $("#dzongkhag_id").val();
            if (dzongkhagId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgewogbydzongkhag/" + dzongkhagId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select a gewog</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#gewog_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate village based on selection of gewogs
        $(document).on("change", "#gewog_id", function() {
            var gewogId = $("#gewog_id").val();
            if (gewogId !== '') {
                //ajax call
                $.ajax({
                    url: "/getvillagebygewog/" + gewogId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gewogs = data;
                        var html = "<option value='' disabled selected hidden>Select a gewog</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .village + "</option>";
                        }
                        $("#village_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate section based on selection of department
        $(document).on("change", "#department_id", function() {
            var departmentId = $("#department_id").val();
            if (departmentId !== '') {
                //ajax call
                $.ajax({
                    url: "/getsectionbydepartment/" + departmentId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var section = data;
                        var html = "<option value='' disabled selected hidden>Select a section</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#section_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate grade step based on selection of grade
        $(document).on("change", "#grade_id", function() {
            var gradeId = $("#grade_id").val();
            if (gradeId !== '') {
                //ajax call
                $.ajax({
                    url: "/getgradestepbygrade/" + gradeId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        var gradeStep = data;
                        var html = "<option value='' disabled selected hidden>Select a grade step</option>";
                        for (var x in data) {
                            html += "<option value='" +
                                data[x].id + "'>" + data[x]
                                .name + "</option>";
                        }
                        $("#grade_step_id").html(html);
                    }
                });
            } else {
                //do sth
            }
        });

        //populate payscale and basic pay based on gradestep
        $(document).on("change", "#grade_step_id", function() {
            var gradeStepId = $("#grade_step_id").val();
            if (gradeStepId !== '') {
                //ajax call
                $.ajax({
                    url: "/getpayscalebygradestep/" + gradeStepId,
                    dataType: "JSON",
                    type: "GET",
                    success: function(data) {
                        // var payScale = data.pay_scale;
                        $("#pay_scale").val(data[0].starting_salary + ' - ' + data[0].increment + ' - ' + data[0].ending_salary); // set the value for pay scale
                        $("#basic_pay").val(data[0].starting_salary); // set the value for pay scale
                    }
                });
            } else {
                $("#pay_scale").val('');
                $("#basic_pay").val('');
            }
        });


        //END

        //turn off all the autocomplete feature within the forms
        $('form').find('input').attr('autocomplete', 'off');

        //reset filters
        $('#form-reset').on('click', function() {
            let form = $('#filter-form');
            form.find('select').each(function() {
                $(this).prop('selectedIndex', 0);
            });
            form.find('input').each(function() {
                $(this).val('');
            });
            form.submit();
        });

        // edit modal script
        $('.edit-btn').click(function(e) {
            e.preventDefault(); // Prevent the default action if needed
            var url = $(this).data('url');
            var updateUrl = $(this).data('update-url');

            $.get(url)
                .done(function(data) {
                    // Populate form fields with the fetched data
                    $.each(data, function(key, value) {
                        var field = $('#edit-modal-form').find('[name="' + key + '"]');
                        if (field.length) {
                            field.val(value || '');
                        }
                    });

                    // Set the form's action URL
                    $('#edit-modal-form').attr('action', updateUrl);

                    // Show the modal
                    $('#edit-modal').modal('show');
                })
                .fail(function(error) {
                    console.error('Error:', error);
                });
        });
// add modal script
$('.add-btn').click(function(e) {
    e.preventDefault(); // Prevent the default action if needed
    var url = $(this).data('url');
    var updateUrl = $(this).data('update-url');

    $.get(url)
        .done(function(data) {
            // Populate form fields with the fetched data
            $.each(data, function(key, value) {
                var field = $('#add-modal-form').find('[name="' + key + '"]');
                if (field.length) {
                    field.val(value || '');
                }
            });

            // Set the form's action URL
            $('#add-modal-form').attr('action', updateUrl);

            // Show the modal
            $('#add-modal').modal('show');
        })
        .fail(function(error) {
            console.error('Error:', error);
        });
});

    }
    return {
        Initialize: initialize
    }
}();

$(document).ready(function() {
    hrms.Initialize();
});

//form validation and button for tabs
$(document).ready(function () {
    function updateNavigationButtons() {
        var $currentTab = $('.steps .current');
        var $nextTab = $currentTab.next();

        // Show or hide the Previous button based on the current tab
        if ($currentTab.hasClass('first')) {
            $('#previous-button').hide();
        } else {
            $('#previous-button').show();
        }

        // Show or hide the Next button based on whether there's a next tab
        if ($nextTab.length) {
            $('#next-button').show();
            $('#submit-button').hide();
        } else {
            $('#next-button').hide();
            $('#submit-button').show();
        }
    }

    function validateCurrentForm() {
        var isValid = true;

        // Check all required fields in the current and previous tabs
        $('.content .body:visible').each(function () {
            $(this).find(':input[required]').each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid'); // Add a class to highlight the input
                } else {
                    $(this).removeClass('is-invalid'); // Remove the class if the input is filled
                }
            });
        });

        return isValid;
    }

    $('#next-button').on('click', function (e) {
        e.preventDefault();

        // Validate the current form before proceeding
        if (!validateCurrentForm()) {
            alert('Please fill all required fields.');
            return;
        }

        // Find the current tab and its content panel
        var $currentTab = $('.steps .current');
        var $currentPanel = $('#' + $currentTab.find('a').attr('aria-controls'));

        // Find the next tab and its content panel
        var $nextTab = $currentTab.next();
        if ($nextTab.length) {
            var $nextPanel = $('#' + $nextTab.find('a').attr('aria-controls'));

            // Update tabs
            $currentTab.removeClass('current').attr('aria-selected', 'false').addClass('disabled').attr('aria-disabled', 'true');
            $nextTab.addClass('current').attr('aria-selected', 'true').removeClass('disabled').attr('aria-disabled', 'false');

            // Update panels
            $currentPanel.hide().attr('aria-hidden', 'true');
            $nextPanel.show().attr('aria-hidden', 'false');

            // Update navigation buttons
            updateNavigationButtons();
        }
    });

    $('#previous-button').on('click', function (e) {
        e.preventDefault();

        // Find the current tab and its content panel
        var $currentTab = $('.steps .current');
        var $currentPanel = $('#' + $currentTab.find('a').attr('aria-controls'));

        // Find the previous tab and its content panel
        var $prevTab = $currentTab.prev();
        if ($prevTab.length) {
            var $prevPanel = $('#' + $prevTab.find('a').attr('aria-controls'));

            // Update tabs
            $currentTab.removeClass('current').attr('aria-selected', 'false').addClass('disabled').attr('aria-disabled', 'true');
            $prevTab.addClass('current').attr('aria-selected', 'true').removeClass('disabled').attr('aria-disabled', 'false');

            // Update panels
            $currentPanel.hide().attr('aria-hidden', 'true');
            $prevPanel.show().attr('aria-hidden', 'false');

            // Update navigation buttons
            updateNavigationButtons();
        }
    });

    $('#submit-button').on('click', function (e) {
        if (!validateCurrentForm()) {
            e.preventDefault();
            alert('Please fill all required fields.');
        } else {
            $('#emp-form').submit();
        }
    });

    // Initialize navigation buttons
    updateNavigationButtons();
});