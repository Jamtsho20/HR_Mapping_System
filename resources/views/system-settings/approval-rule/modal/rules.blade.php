  <!-- modal -->
  <div class="modal fade" id="conditions">
      <div class="modal-dialog modal-fullscreen p-5" role="document">
          <div class="modal-content">
              <div class="modal-body">
                  <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                  <!-- conditions -->
                  <div class="row">
                      <h4>Conditions</h4>
                      <br>
                      <div class="row">
                          <div class="form-group col-4">
                              <label for="mas_approval_head_type_id">Type <span class="text-danger">*</span></label>
                              <select class="form-control" name="mas_approval_head_type_id" required="required">
                                  <option value="" disabled selected hidden>Select your option</option>
                                  <option value="1">1</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group col-3">
                          <label for="condition">Condition <span class="text-danger">*</span></label>
                          <select class="form-control" name="condition" required="required">
                              <option value="" disabled selected hidden>Select your option</option>
                              <option value="AND">AND</option>
                              <option value="OR">OR</option>
                              <option value="NOT">NOT</option>
                              <option value="(">(</option>
                              <option value=")">)</option>
                          </select>
                      </div>
                      <div class="form-group col-3">
                          <label for="mas_condition_field_id">Fields <span class="text-danger">*</span></label>
                          <select class="form-control" name="mas_condition_field_id" required="required">
                              <option value="" disabled selected hidden>Select your option</option>
                              <option value="">NO of Days</option>
                              <option value="">User</option>
                          </select>
                      </div>
                      <div class="form-group col-3">
                          <label for="operator">Operator <span class="text-danger">*</span></label>
                          <select class="form-control" name="operator" required="required">
                              <option value="" disabled selected hidden>Select your option</option>
                              <option value="">Is</option>
                              <option value="">Is Not</option>
                              <option value="">Is Greater Than</option>
                              <option value="">Is Less Than</option>
                              <option value="">Is Less Than or Equal To</option>
                              <option value="">Is Greater Than or Equal To</option>

                          </select>
                      </div>
                      <div class="form-group col-3">
                          <label for="value">Value <span class="text-danger">*</span></label>
                          <input type="number" class="form-control">
                      </div>
                  </div>
                  <!-- end of conditions -->

                  <!-- buttons -->
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary ">Add this to Criteria</button>
                      <button type="reset" class="btn btn-primary ">Clear</button>
                  </div>
                  <!-- end of buttons  -->

                  <!-- formula -->
                  <div class="row">
                      <div class="col-12">
                          <label for="value">Formula :</label>
                          <textarea disabled class="form-control"></textarea>
                      </div>
                  </div>
                  <!-- end of formula -->
                  <br>

                  <div class="row ">
                      <h4>Approval</h4>
                      <br>
                      <!-- hierarchy -->
                      <div class="row my-4">
                          <div class="form-group col-2">
                              <label style="font-weight:400">
                                  <input type="checkbox" class="approval-option" id="hierarchy">
                                  Hierarchy
                              </label>
                          </div>
                          <div class="col-4">
                              <label for="name">Name <span class="text-danger">*</span></label>
                              <input type="text" id="name" value="1" disabled class="form-control">
                          </div>
                          <div class="col-4">
                              <label for="ddlStatus">Max Level <span class="text-danger">*</span></label>
                              <select class="form-control" id="ddlStatus" disabled>
                                  <option value="">test</option>
                              </select>
                          </div>
                      </div>
                      <!-- end of hierarchy -->

                      <!-- single user -->
                      <div class="row my-4">
                          <div class="col-2">
                              <label style="font-weight:400">
                                  <input type="checkbox" class="approval-option" id="single_user">
                                  Single User
                              </label>
                          </div>
                          <div class="col-4">
                              <label for="employee">Employee <span class="text-danger">*</span></label>
                              <select class="form-control" id="employee" disabled>
                                  <option value="" disabled selected hidden>Select your option</option>
                                  @foreach($employees as $employee)

                                  <option value="{{$employee->id}}">{{$employee->name}}</option>
                                  @endforeach
                              </select>
                          </div>
                      </div>
                      <!-- end of single user -->

                      <!-- auto approval -->
                      <div class="row my-4">
                          <div class="col-2">
                              <label style="font-weight:400">
                                  <input type="checkbox" class="approval-option" id="auto_approval">
                                  Auto Approval
                              </label>
                          </div>
                      </div>
                      <!-- end of auto approval -->
                  </div>

                  <!-- FYI -->
                  <div class="row my-4">
                      <h4>FYI</h4>
                      <div class="form-group col-2">
                          <label style="font-weight:400">
                              <input type="checkbox" class="fyi-checkbox" id="fyi">
                              FYI
                          </label>
                      </div>

                      <div class="col-3">
                          <label for="ddlStatus">Frequency <span class="text-danger">*</span></label>
                          <select class="form-control" id="frequency" name="" disabled>
                              <option value="" disabled selected hidden>Select your option</option>
                              @foreach(config('global.level_with_all') as $key => $value)
                              <option value="{{ $key }}">
                                  {{ $value }}
                              </option>
                              @endforeach
                          </select>
                      </div>

                      <div class="col-3">
                          <label for="email">Email <span class="text-danger">*</span></label>
                          <input type="email" class="form-control" id="email" disabled>
                      </div>

                      <div class="col-3">
                          <label for="employee">Employee <span class="text-danger">*</span></label>
                          <select class="form-control" id="employee_fyi" name="" disabled>
                              <option value="" disabled selected hidden>Select your option</option>

                              @foreach($employees as $employee)

                              <option value="{{$employee->id}}">{{$employee->name}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <!-- FYI -->

                  <!-- buttons -->
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary ">Submit</button>
                      <a href="" class="btn btn-primary ">Cancel</a>
                  </div>
                  <!-- end of buttons  -->




              </div>
          </div>
      </div>
  </div>
  <!-- end of modal -->

  <br><br>

  <script>
      const checkboxes = document.querySelectorAll('.approval-option');

      checkboxes.forEach((checkbox) => {
          checkbox.addEventListener('change', function() {
              if (this.checked) {
                  checkboxes.forEach((cb) => {
                      if (cb !== this) {
                          cb.checked = false;
                          disableAssociatedFields(cb.id, true);
                      }
                  });
                  disableAssociatedFields(this.id, false);
              } else {
                  disableAssociatedFields(this.id, true);
              }
          });
      });

      function disableAssociatedFields(id, disable) {
          switch (id) {
              case 'hierarchy':
                  document.getElementById('name').disabled = disable;
                  document.getElementById('ddlStatus').disabled = disable;
                  break;
              case 'single_user':
                  document.getElementById('employee').disabled = disable;
                  break;
          }
      }

      //FYI
      const fyiCheckbox = document.getElementById('fyi');
      const frequency = document.getElementById('frequency');
      const email = document.getElementById('email');
      const employee = document.getElementById('employee_fyi');

      fyiCheckbox.addEventListener('change', function() {
          const isChecked = fyiCheckbox.checked;
          frequency.disabled = !isChecked;
          email.disabled = !isChecked;
          employee.disabled = !isChecked;
      });
  </script>
