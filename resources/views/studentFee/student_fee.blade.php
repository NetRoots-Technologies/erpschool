<hr style="background-color: darkgray">
<div class="box-body" style="margin-top:50px;">
    <h5>Create Fee</h5>
    <div class="row mt-2">
        <div class="col-lg-6">
            <label for="course_fee">Course Fee*</label>
            <input name="course_fee" type="number" class="form-control" id="course_fee" required="required"
                   readonly required/>
        </div>
        <div class="col-lg-6">
            <label for="student_fee">Student Pay Amount*</label>
            <input name="student_fee" type="number" min="0" class="form-control" required="required"
                   id="student_fee" required/>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-6">
            <label for="discount_amount">Discount Amount*</label>
            <input name="discount_amount" id="discount_amount" type="number" required="required" required="required"
                   required
                   class="form-control"/>

        </div>
        <div class="col-lg-6">
            <label for="installement_type">Installement Type*</label>
            <select name="installement_type" id="installement_type" required="required"
                    class="form-control">
                <option>Select Installement Type</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
        </div>
    </div>


</div>

<div id="installement_date_1"></div>
