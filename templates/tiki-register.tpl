<h2>{tr}Register as a new user{/tr}</h2>
<br/>
{if $showmsg eq 'y'}
{$msg}
{else}
<form action="tiki-register.php" method="post"> <br/>
<table class="normal">
<tr><td class="formcolor">{tr}Name{/tr}:</td><td class="formcolor"><input  type="text" name="name" /></td></tr>
{if $useRegisterPasscode eq 'y'}
<tr><td class="formcolor">{tr}Passcode to register (not your user password){/tr}:</td><td class="formcolor"><input  type="password" name="passcode" /></td></tr>
{/if}
<tr><td class="formcolor">{tr}Password{/tr}:</td><td class="formcolor"><input id='pass1' type="password" name="pass" /></td></tr>
<tr><td class="formcolor">{tr}Again{/tr}:</td><td class="formcolor"><input id='pass2' type="password" name="pass2" /></td></tr>
<tr><td class="formcolor">{tr}Email{/tr}:</td><td class="formcolor"><input type="text" name="email" /></td></tr>
<tr><td  class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="register" value="{tr}register{/tr}" /></td></tr>
</table>
</form>
<br/>
<table class="normal">
<tr><td class="formcolor"><a class="link" href="javascript:genPass('genepass','pass1','pass2');">{tr}Generate a password{/tr}</a></td>
<td class="formcolor"><input id='genepass' type="text" /></td></tr>
</table>
{/if}

