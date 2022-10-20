<?php
// check to see if empty 
// if not empty display it
if(@$detail['b_seller_verification'] != 0) { ?>
    <img src="<?php echo osc_base_url()?>oc-content/plugins/seller_verification/images/verified-seller.png" title="<?php echo @$detail['s_seller_description'] ?>">
<?php } /* else { ?>
    <img src="<?php echo osc_base_url()?>oc-content/plugins/seller_verification/images/unverified-seller.png" title="Unverified">
<?php } */ ?>
