<div class="tab-pane ">
    <div class="row gap-2">
        <!-- Permanent Address Section -->
        <div class="col-5 border">
            <h5 class="card-title"><label for=""><strong>Permanent Address</strong></label></h5>
            <form id="permanent-address-form">
                <div class="form-group">
                    <label for="dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                    <select name="permenant_address[mas_dzongkhag_id]" id="dzongkhag_id"
                        class="form-control form-control-sm" required
                        data-initial-value="{{ isset($employee->empPermenantAddress->masDzongkhag->id) ? $employee->empPermenantAddress->masDzongkhag->id : '' }}">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}"
                                {{ old('permenant_address.mas_dzongkhag_id', isset($employee->empPermenantAddress->masDzongkhag->id) ? $employee->empPermenantAddress->masDzongkhag->id : '') == $dzongkhag->id ? 'selected' : '' }}>
                                {{ $dzongkhag->dzongkhag }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gewog Dropdown -->
                <div class="form-group">
                    <label for="gewog_id">Gewog <span class="text-danger">*</span></label>
                    <select name="permenant_address[mas_gewog_id]" id="gewog_id" class="form-control form-control-sm"
                        required
                        data-initial-value="{{ isset($employee->empPermenantAddress->masGewog->id) ? $employee->empPermenantAddress->masGewog->id : '' }}">
                        <option value="" disabled selected hidden>Select a gewog</option>
                        @if (isset($employee->empPermenantAddress->masDzongkhag))
                            @foreach ($employee->empPermenantAddress->masDzongkhag->gewogs as $gewog)
                                <option value="{{ $gewog->id }}"
                                    {{ old('permenant_address.mas_gewog_id', $employee->empPermenantAddress->masGewog->id ?? '') == $gewog->id ? 'selected' : '' }}>
                                    {{ $gewog->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="mas_village_id">Village <span class="text-danger">*</span></label>
                    <select name="permenant_address[mas_village_id]" id="village_id"
                        class="form-control form-control-sm" required
                        data-initial-value="{{ isset($employee->empPermenantAddress->masVillage->id) ? $employee->empPermenantAddress->masVillage->id : '' }}">
                        @if (isset($employee->empPermenantAddress->masGewog))
                            @foreach ($employee->empPermenantAddress->masGewog->villages as $village)
                                <option value="{{ $village->id }}"
                                    {{ old('permenant_address.mas_village_id', $employee->empPermenantAddress->masVillage->id ?? '') == $village->id ? 'selected' : '' }}>
                                    {{ $village->village }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Thram Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="permenant_address[thram_no]"
                        value="{{ old('permenant_address.thram_no', isset($employee->empPermenantAddress->thram_no) ? $employee->empPermenantAddress->thram_no : '') }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="">House Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="permenant_address[house_no]"
                        value="{{ old('permenant_address.house_no', isset($employee->empPermenantAddress->house_no) ? $employee->empPermenantAddress->house_no : '') }}"
                        required>
                </div>

            </form>
        </div>

        <!-- Current Address Section -->
        <div class="col-6 border">
            <h5 class="card-title"><label for=""><strong>Current Address</strong></label></h5>
            <div class="form-group">
                <label for="">Dzongkhag <span class="text-danger">*</span></label>
                <select name="current_address[mas_dzongkhag_id]" id="mas_dzongkhag_id"
                    class="form-control form-control-sm" required>
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach ($dzongkhags as $dzongkhag)
                        <option value="{{ $dzongkhag->id }}"
                            {{ old('current_address.mas_dzongkhag_id', isset($employee->empPresentAddress->masDzongkhag->id) ? $employee->empPresentAddress->masDzongkhag->id : '') == $dzongkhag->id ? 'selected' : '' }}>
                            {{ $dzongkhag->dzongkhag }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Gewog</label>
                <select name="current_address[mas_gewog_id]" id="mas_gewog_id" class="form-control form-control-sm">
                    <option value="" disabled selected hidden>Select your option</option>
                    @if (isset($employee->empPresentAddress->masDzongkhag))
                        @foreach ($employee->empPresentAddress->masDzongkhag->gewogs as $gewog)
                            <option value="{{ $dzongkhag->id }}"
                                {{ old('current_address.mas_gewog_id', isset($employee->empPresentAddress->masGewog->id) ? $employee->empPresentAddress->masGewog->id : '') == $gewog->id ? 'selected' : '' }}>
                                {{ $gewog->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="">City/State <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="current_address[city]"
                    value="{{ old('current_address.city', isset($employee->empPresentAddress->city) ? $employee->empPresentAddress->city : '') }}"
                    required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-sm" name="current_address[postal_code]"
                    value="{{ old('current_address.postal_code', isset($employee->empPresentAddress->postal_code) ? $employee->empPresentAddress->postal_code : '') }}"
                    required>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('refresh-btn').addEventListener('click', function() {
        // Get the form and all selects inside the form
        const form = document.getElementById('permanent-address-form');

        // Reset only select elements to their initial value
        form.querySelectorAll('select').forEach(function(select) {
            const initialValue = select.getAttribute('data-initial-value');
            if (initialValue) {
                select.value = initialValue; // Set the value back to its initial state
            } else {
                select.selectedIndex = 0; // Reset to the first option (default state)
            }
        });

        // Reset all input fields within the form
        form.querySelectorAll('input').forEach(function(input) {
            input.value = input.defaultValue; // Reset the input to its original value
        });
    });
</script>
