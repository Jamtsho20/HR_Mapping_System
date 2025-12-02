 <div class="card mt-4" id="employee-selection" style="display:none;">
     <div class="card-header bg-primary text-white">
         <h5 class="mb-0"><i class="fa fa-users me-2"></i> Assign Employees to Training</h5>
     </div>
     <div class="card-body">
         <div class="table-responsive">
             <table id="employee-table" class="table table-bordered table-sm">
                 <thead class="thead-light">
                     <tr>
                         <th class="text-center">#</th>
                         <th>Employee</th>
                         <th>Designation</th>
                         <th>Department</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td class="text-center">
                             <a href="#" class="delete-table-row btn btn-danger btn-sm">
                                 <i class="fa fa-times"></i>
                             </a>
                         </td>
                         <td>
                             <select name="employees[AAAAA][employee_id]"
                                 class="form-control select2 employee-select resetKeyForNew" required>
                                 <option value="" disabled selected hidden>Select Employee</option>
                                 @foreach(employeeList() as $employee)
                                 <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                 @endforeach
                             </select>
                         </td>
                         <td>
                             <input type="hidden"
                                 name="employees[AAAAA][designation_id]"
                                 class="designation-id-field resetKeyForNew">
                             <input type="text"
                                 class="form-control designation-field resetKeyForNew"
                                 readonly>
                         </td>
                         <td>
                             <input type="hidden"
                                 name="employees[AAAAA][department_id]"
                                 class="department-id-field resetKeyForNew">

                             <input type="text"
                                 class="form-control department-field resetKeyForNew"
                                 readonly>
                         </td>
                     </tr>
                     <tr class="notremovefornew">
                         <td colspan="3"></td>
                         <td class="text-right">
                             <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                         </td>
                     </tr>
                 </tbody>
             </table>
         </div>
     </div>
 </div>


 @push('page_scripts')
 <script>
$(document).on('change', '.employee-select', function() {
    let employeeId = $(this).val();
    let row = $(this).closest('tr');

    if (!employeeId) return;

    $.ajax({
        url: `/employee-job/${employeeId}`,
        type: 'GET',
        success: function(data) {
            row.find('.designation-field').val(data.designation_name);
            row.find('.department-field').val(data.department_name);

            // Set the IDs in hidden inputs
            row.find('.designation-id-field').val(data.designation_id);
            row.find('.department-id-field').val(data.department_id);
        }
    });
});

 </script>
 @endpush