<div id="allocate" class="modal hide fade in" style="display: none; ">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3>工资分配</h3>
</div>
<form id="allocation" method="post" class="form-horizontal">
<div class="modal-body">
<?php if(! empty($dinerStaff[1])):?>
    <?php foreach($dinerStaff[1] as $item):?>
        <div class="control-group">
            <lable class="control-label">店长(<?=$item->realname;?>)：</lable>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="<?=$item->id;?>" class="form-control text-center salary" />
                    <span class="add-on">￥</span>
                </div>
            </div>
        </div>
    <?php endforeach;?>
<?php endif;?>
<?php if(! empty($dinerStaff[2])):?>
    <?php foreach($dinerStaff[2] as $item):?>
        <div class="control-group">
            <lable class="control-label">店小二(<?=$item->realname;?>)：</lable>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="<?=$item->id;?>" class="text-center salary" />
                    <span class="add-on">￥</span>
                </div>
            </div>
        </div>
    <?php endforeach;?>
<?php endif;?>
</div>
<div class="modal-footer">
    <div class="pull-left balance">
        <span style="padding: 10px;">
            <label class="btn">剩余金额:&nbsp;&nbsp;
                <font class="badge badge-info effectiveSalary" id="surplus">￥0.00</font>
            </label>
        </span>
    </div>
<input type="hidden" name="date"/>
<button type="submit" class="btn btn-success">确定</button>
<a href="#" class="btn" data-dismiss="modal">取消</a>
</div>
</form>
</div>