<?php $this->region('Module\Common:head') ?>
<?php $this->region('Module\Common:sidebar') ?>
    <style>
        img {
            max-width: none;
        }
    </style>
    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" title="" class="tip-bottom">餐车定位</a></div>
        </div>
        <!--End-breadcrumbs-->

        <!--Action boxes-->
        <div class="container-fluid">
            <?php echo $messages ?>
            <div class="row-fluid">
                <div class="span12">
                    <a href="#" id="flashCar" class="btn btn-info">刷新餐车位置</a>
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>餐车定位</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <div id="container" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>

                </div>
            </div>
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
    <script src="<?php echo $this->misc('js/matrix.tables.js'); ?>"></script>、
    <script src="<?php echo $this->misc('js/jquery.peity.min.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/bootstrap-wysihtml5.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo $this->misc('js/matrix.form_validation.js'); ?>"></script>、
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=DxjKQhv6VyFfSmhfqzGhFgEB"></script>
    <script>
        // 百度地图API功能
        var map = new BMap.Map("container");
        map.enableScrollWheelZoom();
        map.addControl(new BMap.OverviewMapControl());              //添加默认缩略地图控件
        map.addControl(new BMap.OverviewMapControl({isOpen:true, anchor: BMAP_ANCHOR_TOP_RIGHT}));   //右上角，打开
        map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
        map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
//        map.centerAndZoom("青岛",12);
        // 编写自定义函数,创建标注
        function addMarker(point,label){
            var marker = new BMap.Marker(point);
            map.addOverlay(marker);
            map.addOverlay(label);
        }
        // 随机向地图添加25个标注
        var setMap = function(){
            map.reset();
            map.clearOverlays();
            $.get(
                "/api/merchant/diningcar/getLocation",
                function(data){
                    if(data.code==200){
                        var content = data.content;
                        var points = [];
                        for(var i=0;i<=content.length - 1;i++){
                            var point = new BMap.Point(content[i].longitude,content[i].latitude);
                            points.push(point);
                            var label = new BMap.Label(content[i].diner_name, {
                                position : point,    // 指定文本标注所在的地理位置
                                offset   : new BMap.Size(0, 0)    //设置文本偏移量
                            });
                            addMarker(point,label);
                        }
                        map.setViewport(points, {zoomFactor: -1});
                    }
                }
            );
        }
        setMap();
        $("#flashCar").click(function(){
            setMap();
        });
    </script>
<?php $this->region('Module\Common:foot') ?>