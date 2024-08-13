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


    }
    return {
        Initialize: initialize
    }
}();

$(document).ready(function() {
    hrms.Initialize();
});