<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>

    <!--main-container-part-->
    <div id="content" style="height:auto !important;">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">新增提报</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <form action="#" method="post" name="myform">
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5><?php echo date('Y-m-d', time())?>&nbsp;销售提报</h5>
                        </div>
                        <div class="widget-content" style="overflow:hidden;zoom:1;">
                            
                            <div class="widget-box">
                                <div class="widget-content">
                                <div style="display:inline-block;text-align:left;width:33%;">线上销售额：<span id="myonline"></span></div>
                                <div style="display:inline-block;text-align:center;width:33%;">线下销售额：<span class="myoffline"></span></div>
                                <div style="display:inline-block;text-align:right;width:33%;">本日销售额：<span id="mytotal"></span></div>
                                </div>
                            </div>
                            <table class="table table-bordered" id="mytable">
                                <thead>
                                <tr>
                                    <th>餐品名称</th>
                                    <th>餐品数量</th>
                                    <th>单价</th>
                                    <th>小计</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                            <div class="widget-box">
                                <div class="widget-content">
                                    在此添加餐品&nbsp;&nbsp;
                                    
                                    <select name="" id="myselect" style="width:200px;">
                                    <?php 
                                        
                                        if (! empty($foods)) {
                                            foreach ($foods as $item) {
                                                echo "<option value=\"{$item->food_id}\">{$item->food_name}</option>";
                                            }
                                        }
                                    ?>
                                        
                                    </select>&nbsp;&nbsp;
                                    <a href="#" class="btn btn-info btn-mini" id="myadd">添加</a>
                                </div>
                            </div>
                            <hr>
                            <div style="float:left;">线下总计：<span class="myoffline"></span></div>
                            <div style="float:right;"><a href="#" class="btn btn-info btn" onclick="mysubmit()">提交并转账</a></div>
                        </div>
                        
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    
    <!--end-main-container-part-->
    <script src="<?php echo $this->misc('js/jquery.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.ui.custom.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-timepicker.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/masked.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.uniform.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/select2.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>
    <script src="<?php echo $this->misc('libs/uploadify/jquery.uploadify.min.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
var foods = {};
var offline;
var online = <?php echo $online?:0;?>;

$(document).ready(function(){
    foods.content = <?php echo json_encode($foods);?>;

    $("#myonline").html(online);
    $("#mytotal").html(online);
});

function mysubmit()
{
    if ($("#mytable tbody").find("tr").length<=0){
        alert("您还没有添加餐品数据哦！");
        return;
    }
    if (confirm("确认要提交提报吗？")) {
        document.myform.submit();
    }
}

function mydelete(index)
{
    $.each(foods.content, function (i, v) {
        if (v.food_id == index) {
            $("#myselect").append("<option value='"+index+"'>"+v.food_name+"</option>");
        };
    });

    $("#mytr"+index).remove();
    $("#myselect").select2('val', '');
    mycal();
}

function mychange()
{
    $("#mytable input[type=text]").each(function () {    
        if (!( /^(0|[1-9]\d*)$/.test($(this).val()))) {
            alert("餐品数量只能为非负整数");
            $(this).val(0);
        }
    });
    mycal();
}

function mycal()
{

    var offline = 0.0;
    $("#mytable tbody tr").each(function(){
        index = $(this).find("input[name^='index']").val();
        count = $(this).find("input[name^='count']").val();

        $.each(foods.content, function (i, v) {
            if (v.food_id == index) {
                amount = parseFloat(v.sale_price) * parseFloat(count);
                $(".mysum"+index).html(amount);
                offline += amount;
            };
        });
    });
    offline = Math.round(offline*100)/100;
    $(".myoffline").html(offline);
    $("#mytotal").html(offline+online);
}

$("#myadd").click(function(){
    var selectedObj = {};
    var selected = $("#myselect").select2('data');

    $.each(foods.content, function (i, v) {
        if (v.food_id == selected.id) {
            selectedObj = v;
        }
    });

    $("#myselect option[value="+selected.id+"]").remove();
    $("#myselect")[0].selectedIndex=0;
    $("#myselect").select2('val', '');
    
    $("#mytable").append('<tr id="mytr'+selected.id+'" class="mytr"><td style="vertical-align:middle;">'+selected.text+'</td><td style="vertical-align:middle;"><input type="text" name="count[]" id="" style="margin-bottom:0;" onchange="mychange()" value="0"><input type="hidden" value="'+selected.id+'" name="index[]"></td><td style="vertical-align:middle;">'+selectedObj.sale_price+'</td><td style="vertical-align:middle;" class="mysum'+selected.id+'">0</td><td style="vertical-align:middle;"><a href="#" class="btn btn-danger btn-delete btn-mini" onclick="mydelete('+selected.id+')">删除</a></td></tr>');

});
</script>
<?php $this->region('Module\Common:foot') ?>