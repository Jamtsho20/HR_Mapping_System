<div class="row">
    <div class="form-group col-md-3">
        <label for="">Full Name</label>
        <input type="text" class="form-control form-control-sm" name="full_name" required>
    </div>
    <div class="form-group col-md-3">
        <label for="">Gender </label>
        <select name="gender" class="form-control form-control-sm" required>
            <option value="">SELECT ONE</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="">DoB </label>
        <div class="input-group input-group-sm">
            <input type="date" class="form-control form-control-sm" name="dob" data-mask placeholder="dd-mm-yyyy" required>
        </div>
    </div>
    <div class="form-group col-md-3">
        <label for="">CID No.</label>
        <input type="text" class="form-control form-control-sm" name="cid" data-inputmask='"mask": "99999999999"' data-mask required>
    </div>
    <div class="form-group col-md-3">
        <label for="">Marital Status </label>
        <select name="marital_status" class="form-control form-control-sm" required>
            <option value="">SELECT ONE</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
        </select>
    </div>
</div>