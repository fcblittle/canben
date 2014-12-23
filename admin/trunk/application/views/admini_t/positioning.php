<?php echo $admini_head;?>
<!--close-Header-part-->
<?php echo $admini_nav;?>
<!--sidebar-menu-->
<?php echo $admin_sidebar;?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb">
        <a href="<?php echo site_url('/admini/dashboard');?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 控制面板</a>
        <a href="#" class="current">餐车列表</a>
    </div>
    <h1>餐车列表</h1>
  </div>
    <hr>

        <!--Action boxes-->
        <div class="container-fluid">
                        <div class="row-fluid">
                <div class="span12">
                    <a href="#" id="flashCar" class="btn btn-info">刷新餐车位置</a>
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

    <!--end-main-container-part-->

    <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=DxjKQhv6VyFfSmhfqzGhFgEB"></script>
    <script>
        var lng = "<?php echo $data[0]['longitude'];?>";
        var lat = "<?php echo $data[0]['latitude'];?>";
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
       // map.addEventListener("click", function (e) {
           // console.log(e.point)
       // });触及事件抓取点的经纬度
        // 编写自定义函数,创建标注
        function addMarker(point,label){
            var marker = new BMap.Marker(point);
            map.addOverlay(marker);
            map.addOverlay(label);
        };
        addMarker(new BMap.Point(lng, lat), '2222');
        return;
        // 随机向地图添加25个标注
        var setMap = function(){
            $.get(
                "/api/merchant/diningcar/getLocation",
                function(data){
                    if(data.code==200){
                        var content = data.content;
                        for(var i=0;i<=content.length - 1;i++){
                            var point = new BMap.Point(content[i].longitude,content[i].latitude);
                            var label = new BMap.Label(content[i].diner_name, {
                                position : point,    // 指定文本标注所在的地理位置
                                offset   : new BMap.Size(0, 0)    //设置文本偏移量
                            });
                            addMarker(point,label);
                        }
                    }
                }
            );
        }
        setMap();
        $("#flashCar").click(function(){
            setMap();
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
<?php echo $footer;?>
<!--SCRIPT END--></body>
</html>
