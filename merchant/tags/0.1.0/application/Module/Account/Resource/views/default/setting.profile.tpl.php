<form id="form" action="" method="post">
<input type="hidden" name="token" value="<?php echo $this->form()->getToken() ?>" />
<div class="from_table">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="120">用户名：</td>
    <td width="330">
      <input class="text_value" name="name" disabled="disabled" type="text" value="<?php echo $user->name ?>" /></td>
    <td>请联系管理员修改用户名</td>
  </tr>
  <tr>
    <td width="120">邮箱：</td>
    <td width="330">
      <input class="text_value" name="email" value="<?php echo $user->email ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>手机：</td>
    <td><input class="text_value" name="phone" type="text" value="<?php echo $profile->phone ?>" /></td>
    <td>&nbsp;</td>
  </tr>
</table>

</div>
<div class="send">
<button type="submit">保存</button>
</div>
</form>
