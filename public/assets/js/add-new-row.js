var tashicellHrms=function(){

    function randomKey() {
        var key = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++) {
            key += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return key;
    };
    
    function addNewRow(tableId) {
        var lastRow = $('#'+ tableId +' tr:not(.notremovefornew):last');
        var row = lastRow.clone();
        row.find('span.help-block').remove();
        row.find('input,select').removeClass('error');
        row.insertAfter(lastRow);

        var key = randomKey();
        row.find('td').each(function () {
            var $this = $(this);
            $this.find('.resetKeyForNew').each(function (index, item) {
                var aa = $(item).attr('name');
                if(aa) {
                    var startIndexOfKey = aa.indexOf('[');
                    var lastKey = aa.substring(startIndexOfKey+1);
                    lastKey=lastKey.substring(0,lastKey.indexOf(']'));
                    $(item).attr('name', aa.replace(lastKey,key));
                }
            });
            var vClear = $this.find('input:not(.notclearfornew)');
            if (vClear) vClear.val('');vClear.attr("placeholder","");
            var vSelect = $this.find('select:not(.notclearfornew)');
            if (vSelect) vSelect.val('');
            var vCheck = $this.find('input[type="checkbox"]');
            if (vCheck) vCheck.removeAttr('checked');
            var vTextAreaClear = $this.find('textarea');
            var vAClear = $this.find('a.url');
            if (vAClear) vAClear.val('');vAClear.removeAttr("href");
            if (vTextAreaClear) vTextAreaClear.val('');vTextAreaClear.attr("placeholder");
            vCheck.parents('span').removeClass('checked');
            $this.find('div.add-row-input-group-ddl').removeClass('show').addClass('hide');
            $this.find('div.add-row-input-group-txt').removeClass('hide').addClass('show');
        });
        $('#' + tableId + ' tr:last td:first' + ' .rowIndex').attr("value", key);
        return key;
    };
    function initialize(){


        $(document).on('click','.add-table-row',function (e){
            e.preventDefault();
            var table=$(this).closest('table').attr('id');
            addNewRow(table);
        });
        $(document).on('click','.delete-table-row',function(e){
            e.preventDefault();
            var thisrow=$(this);
            var table=thisrow.closest('table').attr('id');
            var rowCount = $('#'+table+' >tbody >tr').length;
            for(i=0;i<=rowCount;i++){
                if(rowCount==2){
                    $('#alertMessage').find('p.alert-message').html("You cannot delete all the rows.");
                    $('#alertMessage').modal('show');
                    return false;
                } else {
                    thisrow.closest('tr').remove();
                }
            }
        });
        $('.formConfirm').on('click', function(e) {
            e.preventDefault();
            var el = $(this).next();
            var title = el.attr('data-title');
            var msg = el.attr('data-message');
            var dataForm = el.attr('data-form');

            $('#formConfirm')
            .find('#frm_body').html(msg)
            .end().find('#frm_title').html(title)
            .end().modal('show');

            $('#formConfirm').find('#frm_submit').attr('data-form', dataForm);
        });

        $('#formConfirm').on('click', '#frm_submit', function(e) {
            var id = $(this).attr('data-form');
            $(this).parent().find('div#post-loading-container').removeClass('hide');
            $(id).submit();
            $(this).attr('disabled', true);
        });

        $(document).on('keyup', 'input[type="text"].numeric-only', function(){
            if($(this).val() != ""){
                if(isNaN($(this).val()) || $(this).val() < 0) {
                    $('#alertMessage').find('p.alert-message').html("Invalid input. Only numbers are accepted.");
                    $('#alertMessage').modal('show');
                    $(this).val(0);
                    return false;
                }
            }
        });
    }
    return{
        RandomKey:randomKey,
        AddNewRow:addNewRow,
        Initialize:initialize
    }
}();
$(document).ready(function(){
    tashicellHrms.Initialize();
});
