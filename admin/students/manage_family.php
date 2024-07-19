<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `children` WHERE id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
	img#cimg{
		height: 17vh;
		width: 25vw;
		object-fit: scale-down;
	}
</style>
<div class="container-fluid">
    <form action="" id="family-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="student_id" value="<?php echo isset($_GET['student_id']) ? $_GET['student_id'] : '' ?>">
        <div class="row">
        <div class="col-md-6 form-group">
    <label for="name" class="control-label">Name</label>
    <input type="text" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" class="form-control form-control-border form-control-sm" required>
</div>
<div class="col-md-6 form-group">
    <label for="age" class="control-label">Age</label>
    <input type="text" id="age" name="age" value="<?= isset($age) ? htmlspecialchars($age) : '' ?>" class="form-control form-control-border form-control-sm" required>
</div>
<div class="col-md-6 form-group">
    <label for="status" class="control-label">Status</label>
    <input type="text" id="status" name="status" value="<?= isset($status) ? htmlspecialchars($status) : '' ?>" class="form-control form-control-border form-control-sm" required>
</div>
 <div class="form-group col-md-4">
                                <label for="dob" class="control-label">Date of Birth</label>
                                <input type="date" name="birthdate" id="dob" value="<?= isset($birthdate) ? $birthdate : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
<div class="col-md-6 form-group">
    <label for="birthplace" class="control-label">Birthplace</label>
    <input type="text" id="birthplace" name="birthplace" value="<?= isset($birthplace) ? htmlspecialchars($birthplace) : '' ?>" class="form-control form-control-border form-control-sm" required>
</div>

    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#amount').focus();
            $('#course_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal #family-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            if(_this[0].checkValidity() == false){
                _this[0].reportValidity();
                return false;
            }
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_family",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>