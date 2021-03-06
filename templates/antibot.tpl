{* $Id$ *}
{if empty($user) || $user eq 'anonymous' || !empty($showantibot)}
	{$labelclass = 'col-md-3'}
	{$inputclass = 'col-md-9'}
	{$captchaclass = 'col-md-5 col-sm-7 col-sm-offset-3'}
	{if $form === 'register'}
		{$labelclass = 'col-md-4 col-sm-3'}
		{$inputclass = 'col-md-4 col-sm-6'}
		{$captchaclass = 'col-md-5 col-sm-7 col-md-offset-4 col-sm-offset-3'}
	{/if}
	<div class="form-group">
		{if $captchalib->type eq 'recaptcha'}
			<div class="form-group">
				<div class="{$captchaclass}">
					{$captchalib->render()}
				</div>
			</div>
		{elseif $captchalib->type eq 'questions'}
			<input type="hidden" name="captcha[id]" id="captchaId" value="{$captchalib->generate()}">
			<div class="form-group">
				<label class="col-md-4 col-sm-3 control-label">
					{$captchalib->render()}
				</label>
				<div class="{if !empty($inputclass)}{$inputclass}{else}col-md-8 col-sm-9{/if}">
					<input class="form-control" type="text" maxlength="8" size="22" name="captcha[input]" id="antibotcode">
				</div>
				{if $showmandatory eq 'y'}
					<div class="col-md-1 col-sm-1">
						<span class='text-danger tips' title=":{tr}This field is manadatory{/tr}">*</span>
					</div>
				{/if}
			</div>
		{else}
			<input type="hidden" name="captcha[id]" id="captchaId" value="{$captchalib->generate()}">
			<div class="form-group">
				<label class="control-label {$labelclass}" for="antibotcode">{tr}Enter the code below{/tr}{if $showmandatory eq 'y' && $form ne 'register'}<strong class="mandatory_star"> *</strong>{/if}</label>
				<div class="{if !empty($inputclass)}{$inputclass}{else}col-md-8 col-sm-9{/if}">
					<input class="form-control" type="text" maxlength="8" name="captcha[input]" id="antibotcode">
				</div>
				{if $showmandatory eq 'y'}
					<div class="col-md-1 col-sm-1">
						<span class='text-danger tips' title=":{tr}This field is manadatory{/tr}">*</span>
					</div>
				{/if}
			</div>
			<div class="form-group">
				<div class="{$captchaclass}">
					{if $captchalib->type eq 'default'}
						<img id="captchaImg" src="{$captchalib->getPath()}" alt="{tr}Anti-Bot verification code image{/tr}" height="50">
					{else}
						{* dumb captcha *}
						{$captchalib->render()}
					{/if}
				</div>
				{if $captchalib->type eq 'default'}
					{button _id='captchaRegenerate' _class='' href='#antibot' _text='{tr}Try another code{/tr}' _icon_name="refresh" _onclick="generateCaptcha();return false;"}
				{/if}
			</div>
		{/if}
	</div>
{/if}
{jq}
if($("#antibotcode").parents('form').data("validator")) {
	$( "#antibotcode" ).rules( "add", {
		required: true,
		remote: {
			url: "validate-ajax.php",
			type: "post",
			data: {
				validator: "captcha",
				parameter: function() {
					return $("#captchaId").val();
				},
				input: function() {
					return $("#antibotcode").val();
				}
			}
		}
	});
} else if (jqueryTiki.validate) {
	$("#antibotcode").parents('form').validate({
		rules: {
			"captcha[input]": {
				required: true,
				remote: {
					url: "validate-ajax.php",
					type: "post",
					data: {
						validator: "captcha",
						parameter: function() {
							return $("#captchaId").val();
						},
						input: function() {
							return $("#antibotcode").val();
						}
					}
				}
			}
		},
		messages: {
			"captcha[input]": { required: "This field is required"}
		},
		submitHandler: function(){form.submit();}
	});
}
{/jq}
