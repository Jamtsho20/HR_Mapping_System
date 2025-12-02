 <div class="card mt-4" id="training-fees" style="display:none;">
     <div class="card-header bg-primary text-white">
         <h5 class="mb-0"><i class="fa fa-users me-2"></i> Add Training Fees</h5>
     </div>
     <div class="card-body">
         <div class="table-responsive">
             <table id="training-fees-table" class="table table-bordered table-sm">
                 <thead class="thead-light">
                     <tr>
                         <th class="text-center">#</th>
                         <th>Institute</th>
                         <th>Training Name</th>
                         <th>Location</th>
                         <th>Participants</th>
                         <th>Total Cost</th>
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
                             <input type="text"
                                 name="fees[AAAAA][institute]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter institute">
                         </td>

                         <td>
                             <input type="text"
                                 name="fees[AAAAA][training_name]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter training name">
                         </td>

                         <td>
                             <input type="text"
                                 name="fees[AAAAA][location]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter location">
                         </td>

                         <td>
                             <input type="text"
                                 name="fees[AAAAA][participants]"
                                 class="form-control resetKeyForNew"
                                 required>
                         </td>

                         <td>
                             <input type="text"
                                 name="fees[AAAAA][total_cost]"
                                 class="form-control resetKeyForNew"
                                 required>
                         </td>
                     </tr>

                     <tr class="notremovefornew">
                         <td colspan="8" class="text-right">
                             <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px">
                                 <i class="fa fa-plus"></i> Add New Row
                             </a>
                         </td>
                     </tr>

                 </tbody>
             </table>
         </div>
     </div>
 </div>



 @push('page_scripts')
 <script>

 </script>
 @endpush