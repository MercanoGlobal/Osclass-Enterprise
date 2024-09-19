<div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
    <div style="padding: 0 20px 20px;">
        <div>
            <fieldset>
                <legend>
                    <h1>Seller Verification Help</h1>
                </legend>
                <h2>
                    What does this plugin do?
                </h2>
                <p>
                    This plugin enables the admin to mark users as verified.
                </p>
                <h2>
                    How to use it:
                </h2>
                <p>
                    From the admin menu, select Users > Edit User.
                </p>
                <p>
                    There are 2 inputs for the admin to use:
                </p>
                <p>
                    1. Seller Verification - Input type checkbox. Check if you want to show the user verified image/badge.
                </p>
                <p>
                    2. Seller Description - Input type string. That description will show as title of user verified image. It means that this input will show if you hover the cursor over the user verified image.
                </p>
                <p>
                    If you want to chage the badge/image of the verified users, just replace the image inside the plugin's images folder.
                </p>
                <h2>
                    How to integrate it in your theme:
                </h2>
                <p>
                    1. <i>*Already integrated in <a href="https://github.com/MercanoGlobal/Osclass-Enterprise" target="_blank">Osclass Enterprise</a></i> - Apply the following hook in <code>oc-admin\themes\modern\users\frm.php</code> line ~321 above this code <code>&lt;?php if(!$aux['edit']) { osc_run_hook('user_register_form'); }; ?&gt;</code>:
                </p>
                <pre>&lt;?php osc_run_hook('seller_verification', isset($user['pk_i_id']) ? $user['pk_i_id'] : ''); ?&gt;</pre>
                <p>
                    2. Apply the following hook wherever you want to show the verified seller badge. The recommended places to apply it are: <code>main.php</code>, <code>search_list.php</code>, <code>item.php</code>.
                </p>
                <pre>&lt;?php osc_run_hook('seller_verification_show', osc_item_user_id()); ?&gt;</pre>
                <p>
                    3. To show the badge on the User Public Profile, you need to add the following hook in your theme's <code>user-public-profile.php</code>, usually around the user's name area:
                </p>
                <pre>&lt;?php osc_run_hook('seller_verification_show', osc_user_id()); ?&gt;</pre>
                <p>
                    4. If you want to show it on <code>main.php</code> and <code>search_list.php</code>, you must apply this hook first:
                </p>
                <pre>&lt;?php View::newInstance()->_exportVariableToView('user', User::newInstance()->findByPrimaryKey(osc_item_user_id())); ?&gt;
&lt;?php $user_var = User::newInstance()->findByPrimaryKey(osc_item_user_id()); ?&gt;
</pre>
                <p><b>Enjoy!</b></p>
            </fieldset>
        </div>
    </div>
</div>