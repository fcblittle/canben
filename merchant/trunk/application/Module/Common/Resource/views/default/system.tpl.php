<div style="border:1px solid #ccc;background:#eee;padding:5px;margin:5px;position: fixed;bottom:0;right:0;width:200px;">
    <dl style="margin:0">
        <dt><strong>内存使用: </strong></dt><dd><?php echo round($system['memoryUsage'] / 1024, 3) ?> KB</dd>
        <dt><strong>执行时间: </strong></dt><dd><?php echo round($system['time'] * 1000, 3) ?> ms</dd>
        <dt><strong>加载文件: </strong></dt><dd><?php echo count($system['includedFiles']) + 1 ?> 个<br />
            <!--<ul>
            <?php foreach ($system['includedFiles'] as $k => $file): ?>
                <li><?php echo $k + 1, ' ', $file ?></li>
            <?php endforeach ?>
            </ul>-->
        </dd>
        <dt><strong>Mysql查询: </strong></dt><dd><?php echo $queries ?> 条</dd>
    </dl>
</div>
