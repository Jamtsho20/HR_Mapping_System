$(document).ready(function() {
    // Select/Deselect all checkboxes
    $('.select_all').click(function() {
        var itemClass = $(this).data('item-class');
        $('.' + itemClass).prop('checked', this.checked);
    });

    // Bulk approval/rejection
    $('.buttonsubmit').click(function() {
        var action = $(this).data('value');
        var selectedItems = [];
        var routeUrl = $(this).data('route');
        var itemClass = $(this).data('item-class');
        var itemName = $(this).data('item-name');

        // Collect selected item IDs
        $('.' + itemClass + ':checked').each(function() {
            selectedItems.push($(this).val());
        });

        // Check if any items are selected
        if (selectedItems.length === 0) {
            alert('Please select at least one ' + itemName);
            return;
        }

        // Send AJAX request
        $.ajax({
            url: routeUrl,
            type: 'POST',
            data: {
                // _token: '{{ csrf_token() }}',
                item_ids: selectedItems,
                action: action
            },
            success: function(response) {
                alert(response.success);
                location.reload(); // Reload to reflect changes
            },
            error: function() {
                alert('An error occurred while processing your request');
            }
        });
    });
});