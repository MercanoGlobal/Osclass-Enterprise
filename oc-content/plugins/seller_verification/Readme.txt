Version 1.1

This plugin enables the admin to mark users as verified.

How to use
1. Login to admin site
2. Select edit user

There is 2 input admin should write.
1. Seller Verification - Input type checkbox. Check if you want to show the user verified image.
2. Seller Description - Input type string. That description will show as title of user verified image. It means this input will show if you hover cursor to user verified image.

If you want to chage the image of the verified users, just change the image in images folder.


How to install
1. Put this hook code <?php osc_run_hook('seller_verification', isset($user['pk_i_id']) ? $user['pk_i_id'] : ''); ?> in oc-admin\themes\modern\users\frm.php line above this code <?php if(!$aux['edit']) { osc_run_hook('user_register_form'); }; ?>

2. Put this hook code <?php osc_run_hook('seller_verification_show', osc_item_user_id()); ?> wherever you want to show the verified seller images. Recommended place is to put it on main.php, search_list.php, item.php.

3. If you want to show it on main.php and search_list.php, you must put this code first
<?php View::newInstance()->_exportVariableToView('user', User::newInstance()->findByPrimaryKey(osc_item_user_id())); ?>
<?php $user_var = User::newInstance()->findByPrimaryKey(osc_item_user_id()); ?>

   *To show it in McFly Theme, go to oc-content\themes\mcfly\parts\blocks\item_sidebar\item_sidebar-user.php under line 38

			<!-- Seller Verification -->
			<div><?php osc_run_hook('seller_verification_show', osc_item_user_id()); ?></div>
			<!-- End Seller Verification -->
