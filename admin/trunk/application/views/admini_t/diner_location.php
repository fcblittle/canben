<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">餐车定位</a>
    </div>
    <h1>餐车定位</h1>
  </div>
    <hr>

        <!--Action boxes-->
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-content" style="overflow: hidden">
                            <form>
                                <select class="span2" id="select-city">
                                    <option  value="-1">请选择城市</option>
                                    <?php foreach ($citys as $v): ?>
                                    <option value="<?=$v['id']?>"><?=$v['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <select class="span2" id="select-area">
                                    <option value="-1">全部区域</option>
                                </select>
                                <select class="span2" id="select-diner">
                                    <option value="-1">全部餐车</option>
                                </select>
                                <a href="#" id="flashCar" class="btn btn-info span1">定位</a>
                            </form>
                        </div>
                    </div>
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>餐车定位</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <div id="container" style="width: 100%; height: 500px;"></div>
                            <input type="hidden" name="pointx" value="120.386937"/>
                            <input type="hidden" name="pointy" value="36.094005"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/html" id="t-select-area">
        <option value="0">选择全部</option>
        <% _.each(data,function(v){ %>
            <option value="<%- v.id %>"><%- v.area %></option>
        <% }) %>
    </script>
    <script type="text/html" id="t-select-diner">
        <option value="0">选择全部</option>
        <% _.each(data,function(v){ %>
            <option value="<%- v.id %>" latitude="v.latitude" longitude="v.longitude"><%- v.diner_name %></option>
        <% }) %>
    </script>
    <!--end-main-container-part-->
<?php echo $footer;?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=DxjKQhv6VyFfSmhfqzGhFgEB"></script>
<script src="http://cdn.staticfile.org/underscore.js/1.6.0/underscore-min.js"></script>
<script>
    $(function(){
        var urlGetAreas = "<?=site_url('admini/diner/get_areas') ?>";
        var urlGetDiners = "<?=site_url('admini/diner/get_diners') ?>";
        // 百度地图API功能
        var map = new BMap.Map("container");
        map.enableScrollWheelZoom();
        map.addControl(new BMap.OverviewMapControl());              //添加默认缩略地图控件
        map.addControl(new BMap.OverviewMapControl({isOpen:true, anchor: BMAP_ANCHOR_TOP_RIGHT}));   //右上角，打开
        map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
        map.centerAndZoom("青岛",12);
        //添加标记
        var addMarker =function(point,label){
            var marker = new BMap.Marker(point);
            map.addOverlay(marker);
            map.addOverlay(label);
        };
        //搜索定位
        var setMap = function(data){
            map.clearOverlays();
            var pts = [];
            _.each(data,function(v){
                console.log(v);
                var point = new BMap.Point(v.longitude,v.latitude);
                pts.push(point);
                var label = new BMap.Label(v.diner_name, {
                    position : point,    // 指定文本标注所在的地理位置
                    offset   : new BMap.Size(0, 0)    //设置文本偏移量
                });
                addMarker(point,label);
            });
            map.setViewport(pts,{margins:[30, 20, 0, 20]});
        }
        //刷新select
        var flashSelect = function(select){
            var id = select.attr("id");
            var getDiner = function(){
                var  a = {
                    city:$("#select-city").val(),
                    area:$("#select-area").val()
                }
                $.get(urlGetDiners,a,function(data){
                    var data = JSON.parse(data);
                    var option={
                        data:data
                    }
                    var html = _.template($("#t-select-diner").html(),option);
                    $("#select-diner").html(html);
                    $("#select-diner").select2();
                });
            }
            if(id=="select-diner"){return;}
            if(id=="select-city"){
                $.get(urlGetAreas,"pid="+select.val(),function(data){
                    var data = JSON.parse(data);
                    var option={
                        data:data
                    }
                    var html = _.template($("#t-select-area").html(),option);
                    $("#select-area").html(html);
                    $("#select-area").select2();
                    getDiner();
                });
            }else{
                getDiner();
            }

        }
        //定位
        $("#flashCar").click(function(){
            var data = {
                city:$("#select-city").val(),
                area:$("#select-area").val(),
                diner:$("#select-diner").val()
            }
            if(data.city>=1){
                $.get(urlGetDiners,data,function(data){
                    var data = JSON.parse(data);
                    setMap(data);
                });
            }
        });
        //联动
        $("body").find("select").change(function(){
            // flashSelect($(this));
            
        })
    });

</script>
<!--SCRIPT START-->
<script type="text/javascript">
    // This function is called from the pop-up menus to transfer to
    // a different page. Ignore if the value returned is a null string:
    function goPage (newURL) {

        // if url is empty, skip the menu dividers and reset the menu selection to default
        if (newURL != "") {

            // if url is "-", it is this page -- reset the menu:
            if (newURL == "-" ) {
                resetMenu();
            }
            // else, send page to designated URL
            else {
                document.location.href = newURL;
            }
        }
    }

    // resets the menu selection upon entry to this page:
    function resetMenu() {
        document.gomenu.selector.selectedIndex = 2;
    }
</script>
<style>img { max-width: none;};</style>
<!--SCRIPT END-->
</body>
</html>
