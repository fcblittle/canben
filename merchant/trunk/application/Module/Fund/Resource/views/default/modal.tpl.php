<div id="operation" class="modal hide fade in" style="display: none; ">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3>资金操作</h3>
</div>
<form id="operator" method="post" class="form-horizontal">
<div class="modal-body">
	<div class="control-group">
		<lable class="control-label">转账摘要：</lable>
		<div class="controls">
			<select name="variationType">
				<?php foreach($variationType as $item):?>
					<option value="<?=$item->id;?>"><?=$item->name;?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<lable class="control-label">付款账户：</lable>
		<div class="controls">
			<select name="accountType"  disabled="disabled">
				<option value="1">我的钱包</option>
				<option value="2">经营账户</option>
				<option value="3" selected="selected">银行账户</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<lable class="control-label">收款账户：</lable>
		<div class="controls">
			<select name="accountTo" disabled="disabled">
				<option value="1" selected="selected">我的钱包</option>
				<option value="2">经营账户</option>
				<option value="3">银行账户</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<lable class="control-label">金额：</lable>
		<div class="controls">
			<div class="input-append">
				<input type="text" name="amount" />
				<span class="add-on">￥</span>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="ajax" value="true">
</form>
<form id="pay_form" target="_blank" name="pay_form" method="post">
	<input type="hidden" name="version" id="version" value="">
	<input type="hidden" name="charset" id="charset" value="">
	<input type="hidden" name="merId" id="merId" value="">
	<input type="hidden" name="merAbbr" id="merAbbr" value="">
	<input type="hidden" name="transType" id="transType" value="">
	<input type="hidden" name="backEndUrl" id="backEndUrl" value="">
	<input type="hidden" name="frontEndUrl" id="frontEndUrl" value="">
	<input type="hidden" name="orderTime" id="orderTime" value="">
	<input type="hidden" name="orderNumber" id="orderNumber" value="">
	<input type="hidden" name="orderAmount" id="orderAmount" value="">
	<input type="hidden" name="orderCurrency" id="orderCurrency" value="">
	<input type="hidden" name="customerIp" id="customerIp" value="">
	<input type="hidden" name="signature" id="signature" value="">
	<input type="hidden" name="signMethod" id="signMethod" value="">
</form>
<div class="modal-footer">
<button class="btn btn-success ok">去银联转账</button>
<a href="#" class="btn" data-dismiss="modal">取消</a>
</div>
</div>


<div id="alert" class="modal hide fade in" style="display: none; ">
<div class="modal-header">
<a class="close" data-dismiss="modal">×</a>
<h3></h3>
</div>
<form id="alertForm" method="post" class="form-horizontal">
<div class="modal-body">
	
</div>
</form>
<div class="modal-footer">
<button class="btn btn-success ok"></button>
<a href="#" class="btn cancel"></a>
</div>
</div>