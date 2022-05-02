<h3 class="render-title">User Verification</h3>
<div class="plugin-attributes box">
    <div class="form-row" style="margin-left:50px">  
        <label for="seller_verification" title="Show verification image?">Seller Verification  </label>
        <input type="checkbox" name="plugin_seller_verification" value="1" <?php if(@$detail['b_seller_verification'] == '1') { ?> checked="checked" <?php } ?> /><br />
        <label for="seller_description" title="Will show if you hover the verification image">Seller Description  </label>
        <input type="text" name="plugin_seller_description" value="<?php echo @$detail['s_seller_description']; ?>" /> - <span>You can also use <a href="https://fsymbols.com/" target="_blank">symbols</a> like: ✔ ♛ ツ ✪ ★ ► ◄ ⚡</span>
    </div>
</div>
 