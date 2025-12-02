 <div class="card mt-4" id="air-fare" style="display:none;">
     <div class="card-header bg-primary text-white">
         <h5 class="mb-0"><i class="fa fa-users me-2"></i> Add Air Fare</h5>
     </div>
     <div class="card-body">
         <div class="table-responsive">
             <table id="air-fare-table" class="table table-bordered table-sm">
                 <thead class="thead-light">
                     <tr>
                         <th class="text-center">#</th>
                         <th>Airline</th>
                         <th>Departure Date</th>
                         <th>Return Date</th>
                         <th>Journey</th>
                         <th>Grand Total</th>
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
                                 name="airfares[AAAAA][airline]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter Airline">
                         </td>

                         <td>
                             <input type="date"
                                 name="airfares[AAAAA][departure_date]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter Departure Date">
                         </td>

                         <td>
                             <input type="date"
                                 name="airfares[AAAAA][return_date]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter Return Date">
                         </td>

                         <td>
                             <input type="text"
                                 name="airfares[AAAAA][journey]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter Journey">
                         </td>

                         <td>
                             <input type="text"
                                 name="airfares[AAAAA][grand_total]"
                                 class="form-control resetKeyForNew"
                                 required placeholder="Enter Grand Total">
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