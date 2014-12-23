<!--Footer-part-->

<div class="row-fluid">
    <div id="footer" class="span12"> &copy; 餐本 <a href="http://shitong.com/">食通</a> </div>
</div>
<?php $this->region('Module\Common:scripts') ?>
<script>
    $(function(){
        if ($("#content").height() < $("body").height() - 104) {
            $("#content").height($("body").height() - 104);
        }
        //sidebar active
        var path = $.trim(Util.getPath());
        if (path) {
            $("#sidebar").find("a").each(function() {
                if(new RegExp(Util.getPath() + "$").test($(this).attr("href"))){
                    $(this).parent().addClass("active");
                    $(this).parents(".submenu").addClass("open");
                }
            });
        }
    });
</script>
</body>
</html>
