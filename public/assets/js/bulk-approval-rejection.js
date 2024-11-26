$(document).ready(function() {
    // Select/Deselect all checkboxes
    $('#select_all').click(function() {
        var checkedStatus = this.checked; // Get the status of the select all checkbox
        $('.bulk_checkbox').each(function() {
            $(this).prop('checked',
                checkedStatus); // Set each checkbox to match select all status
        });
    });

    // Bulk approval/rejection
    $('.buttonsubmit').click(function() {
        var action = $(this).data('value');
        var selectedItems = [];
        var routeUrl = $(this).data('route');
        var itemClass = $(this).data('item-class');
        var itemName = $(this).data('item-name');
        var itemType = $(this).data('item-type');

        console.log(itemType)
        // Modal close manually
        $('.close').click(function() {
            $('#rejectModal').modal('hide'); // Manually hide the modal
        });

        // Collect selected item IDs
        $('.' + itemClass + ':checked').each(function() {
            selectedItems.push($(this).val());
        });

        // Check if any items are selected
        if (selectedItems.length === 0) {
            alert('Please select at least one ' + itemName);
            return;
        }

        // Check if reject action is clicked
        if (action === 'reject') {
            // Show reject remarks modal
            $('#rejectModal').modal('show');

            // Handle reject confirmation
            $('#confirmReject').click(function() {
                var rejectRemarks = $('#rejectRemarks').val();

                if (rejectRemarks.trim() === '') {
                    alert('Please provide reject remarks.');
                    return;
                }

                // Send AJAX request to reject
                $.ajax({
                    url: routeUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        item_ids: selectedItems,
                        action: action,
                        reject_remarks: rejectRemarks,
                        item_type: itemType
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload(); // Reload to reflect changes
                    },
                    error: function() {
                        alert(
                            'An error occurred while processing your request'
                        );
                    }
                });

                // Close the modal
                $('#rejectModal').modal('hide');
            });
        } else {
            // Proceed with approval if action is approve
            $.ajax({
                url: routeUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item_ids: selectedItems,
                    action: action,
                    item_type: itemType
                },
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Reload to reflect changes
                },
                error: function() {
                    alert('An error occurred while processing your request');
                }
            });
        }
    });
});
