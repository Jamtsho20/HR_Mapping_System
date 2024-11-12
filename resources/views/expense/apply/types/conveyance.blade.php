  <!-- Dynamic Form Sections -->
  <div id="conveyance_expense_form" class="dynamic-form" style="display: none;  ">
      <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  <label for="travel_type">Travel Type<span class="text-danger">*</span></label>
                  <select class="form-control" id="travel_type" name="travel_type">
                      <option value="" disabled selected hidden>Select your option</option>
                      @foreach(config('global.travel_types') as $key => $label)
                        <option value="{{ $key }}" {{ old('travel_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="travel_mode">Mode of Travel<span class="text-danger">*</span></label>
                  <select class="form-control" id="travel_mode" name="mode_of_travel">
                      <option value="" disabled selected hidden>Select your option</option>
                      @foreach(config('global.travel_modes') as $key => $label)
                        <option value="{{ $key }}" {{ old('mode_of_travel') == $key ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="travel_from_date">Travel From Date<span class="text-danger">*</span></label>
                  <input type="date" class="js-datepicker form-control" name="travel_from_date" value="{{ old('travel_from_date') }}" placeholder="dd/mm/yy" />
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-md-4">
              <div class="form-group">
                  <label for="travel_to_date">Travel to Date<span class="text-danger">*</span></label>
                  <input type="date" class="js-datepicker form-control" name="travel_to_date" value="{{ old('travel_to_date') }}" placeholder="dd/mm/yy" />
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="travel_from">Travel From<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="travel_from" value="{{ old('travel_from') }}" />
              </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                  <label for="travel_to">Travel To<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="travel_to" value="{{ old('travel_to') }}" />
              </div>
          </div>
      </div>
  </div>