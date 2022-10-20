Version 1.1

This plugin enables the admin to mark users as verified.

How to use:
1. Log in the admin area
2. Select Users > Edit User

There are 2 inputs for the admin to use:
1. Seller Verification - Input type checkbox. Check if you want to show the user verified image/badge.
2. Seller Description - Input type string. That description will show as title of user verified image. It means that this input will show if you hover the cursor over the user verified image.

If you want to chage the image of the verified users, just replace the image inside the plugin's images folder.

How to install:
1. [Already integrated in Osclass Enterprise] - Apply the following hook in oc-admin\themes\modern\users\frm.php line ~321 above this code <?php if(!$aux['edit']) { osc_run_hook('user_register_form'); }; ?> :
    <?php osc_run_hook('seller_verification', isset($user['pk_i_id']) ? $user['pk_i_id'] : ''); ?>

2. Apply the following hook wherever you want to show the verified seller badge. The recommended places to apply it are: main.php, search_list.php, item.php.
    <?php osc_run_hook('seller_verification_show', osc_item_user_id()); ?>

3. To show the badge on the User Public Profile, you need to add the following hook in your theme's user-public-profile.php, usually around the user's name area:
    <?php osc_run_hook('seller_verification_show', osc_user_id()); ?>

4. If you want to show it on main.php and search_list.php, you must apply this code first:
    <?php View::newInstance()->_exportVariableToView('user', User::newInstance()->findByPrimaryKey(osc_item_user_id())); ?>
    <?php $user_var = User::newInstance()->findByPrimaryKey(osc_item_user_id()); ?>
