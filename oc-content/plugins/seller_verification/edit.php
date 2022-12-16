<h3 class="render-title">User Verification</h3>
<div class="form-row">
    <div class="form-label">
        <label for="seller_verification" title="Show the verification badge/image?">Seller Verification</label>
	</div>
    <div class="form-controls">
        <input type="checkbox" name="plugin_seller_verification" value="1" <?php if(@$detail['b_seller_verification'] == '1') { ?> checked="checked" <?php } ?> />
    </div>
</div>
<div class="form-row">
    <div class="form-label">
        <label for="seller_description" title="Will show if you mouseover the verification image">Seller Description</label>
    </div>
    <div class="form-controls">
        <input type="text" name="plugin_seller_description" value="<?php echo osc_esc_html(@$detail['s_seller_description']); ?>" />
            <p class="help-inline">You can also use <a href="https://fsymbols.com/" target="_blank" rel="noopener nofollow">symbols</a> like: ✔ ♛ ツ ✪ ★ ► ◄ ⚡</p>
    </div>
</div>