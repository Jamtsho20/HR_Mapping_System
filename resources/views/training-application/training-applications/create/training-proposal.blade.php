 <div class="card mt-4" id="training-proposal" style="display:none;">
     <div class="card-header bg-primary text-white">
         <h5 class="mb-0"><i class="fa fa-users me-2"></i> Add Training Proposals</h5>
     </div>
     <div class="card-body">
         <div class="table-responsive">
             <table id="proposal-table" class="table table-bordered table-sm">
                 <thead class="thead-light">
                     <tr>
                         <th class="text-center">#</th>
                         <th>Training Provider</th>
                         <th>Course</th>
                         <th>Location</th>
                         <th>Duration</th>
                         <th>Fee per Person</th>
                         <th>Total</th>
                         <th>Best Option</th>
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
                                 name="proposals[AAAAA][training_provider]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter provider">
                         </td>

                         <td>
                             <input type="text"
                                 name="proposals[AAAAA][course]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter course">
                         </td>

                         <td>
                             <input type="text"
                                 name="proposals[AAAAA][location]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter location">
                         </td>

                         <td>
                             <input type="text"
                                 name="proposals[AAAAA][duration]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="e.g. 5 days">
                         </td>

                         <td>
                             <input type="text"
                                 name="proposals[AAAAA][fee_per_person]"
                                 class="form-control fee-per-person resetKeyForNew"
                                 min="0" step="0.01" required>
                         </td>

                         <td>
                             <input type="text"
                                 name="proposals[AAAAA][total]"
                                 class="form-control total resetKeyForNew"
                                 required>
                         </td>
                         <td>
                             <select name="proposals[AAAAA][best_option]" class="form-control resetKeyForNew    ">
                                 <option value="0">No</option>
                                 <option value="1">Yes</option>
                             </select>
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