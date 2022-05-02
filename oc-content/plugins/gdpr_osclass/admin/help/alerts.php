<style>
pre, blockquote p {
    font-size: 1rem;
    padding-top: .66001rem;
}
pre code {
    padding: 0;
    font-size: inherit;
    color: inherit;
    white-space: pre-wrap;
    background-color: transparent;
    border-radius: 0;
    border: none;
}
.hljs-number, .hljs-command, .hljs-string, .hljs-tag .hljs-value, .hljs-rules .hljs-value, .hljs-phpdoc, .hljs-dartdoc, .tex .hljs-formula, .hljs-regexp, .hljs-hexcolor, .hljs-link_url {
    color: #2aa198;
}
.hljs-preprocessor, .hljs-preprocessor .hljs-keyword, .hljs-pragma, .hljs-shebang, .hljs-symbol, .hljs-symbol .hljs-string, .diff .hljs-change, .hljs-special, .hljs-attr_selector, .hljs-subst, .hljs-cdata, .css .hljs-pseudo, .hljs-header {
    color: #cb4b16;
}
.hljs-title, .hljs-localvars, .hljs-chunk, .hljs-decorator, .hljs-built_in, .hljs-identifier, .vhdl .hljs-literal, .hljs-id, .css .hljs-function {
    color: #268bd2;
}
.hljs-attribute, .hljs-variable, .lisp .hljs-body, .smalltalk .hljs-number, .hljs-constant, .hljs-class .hljs-title, .hljs-parent, .hljs-type, .hljs-link_reference {
    color: #b58900;
}
</style>

<div class="d-flex w-100 pt-2 mb-3">
    <a class="btn btn-primary btn-sm" style="height:inherit;" href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/settings.php'); ?>"><?php _e('Return to GDPR settings', 'gdpr_osclass'); ?></a>
</div>

<h1><a id="Alert__Bender_theme_4"></a>Alert - Bender theme</h1>
<p>Locate the following file and open it with your preferred editor.</p>
<pre><code class="language-sh">$ bender/alert-form.php
</code></pre>
<h3><a id="1_Add_I_agree_input_to_form_10"></a>1) Add “I agree” input to form.</h3>
<p>Paste this line inside <em>create alert form</em> to include “I agree” checkbox to alert form.</p>
<pre><code class="language-php"><span class="hljs-preprocessor">&lt;?php</span> osc_run_hook(<span class="hljs-string">'gdpr'</span>); <span class="hljs-preprocessor">?&gt;</span>
</code></pre>
<p>Locate alert form and call “gdpr” hook, here you have an example:</p>
<pre><code class="language-html"><span class="hljs-tag">&lt;<span class="hljs-title">form</span> <span class="hljs-attribute">action</span>=<span class="hljs-value">"<span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">echo</span> osc_base_url(<span class="hljs-keyword">true</span>); <span class="hljs-preprocessor">?&gt;</span></span>"</span> <span class="hljs-attribute">method</span>=<span class="hljs-value">"post"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"sub_alert"</span> <span class="hljs-attribute">id</span>=<span class="hljs-value">"sub_alert"</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"nocsrf"</span>&gt;</span>
        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::page_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>
        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::alert_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>

        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">if</span>(osc_is_web_user_logged_in()) { <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::user_id_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::email_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>

        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> } <span class="hljs-keyword">else</span> { <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::user_id_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::email_text(); <span class="hljs-preprocessor">?&gt;</span></span>

        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> }; <span class="hljs-preprocessor">?&gt;</span></span>

        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> osc_run_hook(<span class="hljs-string">'gdpr'</span>); <span class="hljs-comment">// &lt;-- ADD "I agree" checkbox using hooks ?&gt;</span></span>

        <span class="hljs-tag">&lt;<span class="hljs-title">button</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"submit"</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"sub_button"</span> &gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Subscribe now'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span>!<span class="hljs-tag">&lt;/<span class="hljs-title">button</span>&gt;</span>
<span class="hljs-tag">&lt;/<span class="hljs-title">form</span>&gt;</span>
</code></pre>
<h3><a id="2_Add_I_agree_input_to_ajax_call_41"></a>2) Add “I agree” input to ajax call.</h3>
<p>Add this code at the begining of your page. This will allow us to show the correct error message.</p>
<pre><code class="language-php"><span class="hljs-preprocessor">&lt;?php</span>  
<span class="hljs-variable">$gdpr_error_message</span> = osc_get_preference(<span class="hljs-string">'error_agree_text_'</span> . osc_current_user_locale(), <span class="hljs-string">'gdpr_osclass'</span>);
<span class="hljs-keyword">if</span>(<span class="hljs-variable">$gdpr_error_message</span>==<span class="hljs-string">""</span>) {
    <span class="hljs-variable">$gdpr_error_message</span> = __(<span class="hljs-string">'You must accept our "Terms and Conditions" and "Privacy Policy"'</span>, <span class="hljs-string">'theme_domain'</span>);
}
<span class="hljs-preprocessor">?&gt;</span>
</code></pre>
<p>Alerts are created via ajax request, so we will need to pass the new input checkbox. (In this case we are using jquery)</p>
<pre><code class="language-js">...
gdpr_osclass: $(<span class="hljs-string">'.alert_form input[name="gdpr_osclass"]:checked'</span>).val()
...
</code></pre>
<p>Add the previous line to the <code>$.post</code> call.</p>
<pre><code class="language-js">...
$.post(<span class="hljs-string">'&lt;?php echo osc_base_url(true); ?&gt;'</span>, {email:$(<span class="hljs-string">"#alert_email"</span>).val(), userid:$(<span class="hljs-string">"#alert_userId"</span>).val(), alert:$(<span class="hljs-string">"#alert"</span>).val(), page:<span class="hljs-string">"ajax"</span>, action:<span class="hljs-string">"alerts"</span>, gdpr_osclass: $(<span class="hljs-string">'.alert_form input[name="gdpr_osclass"]:checked'</span>).val()},
...
</code></pre>
<h3><a id="3_Handle_error_message_69"></a>3) Handle error message</h3>
<pre><code class="language-js"><span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params">data</span>)</span>{
    <span class="hljs-keyword">if</span>(data==<span class="hljs-number">1</span>) { alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(__('</span>You have sucessfully subscribed to the alert<span class="hljs-string">', '</span>bender<span class="hljs-string">')); ?&gt;'</span>); }
    <span class="hljs-keyword">else</span> <span class="hljs-keyword">if</span>(data==-<span class="hljs-number">1</span>) { alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(__('</span>Invalid email address<span class="hljs-string">', '</span>bender<span class="hljs-string">')); ?&gt;'</span>); }
    <span class="hljs-keyword">else</span> <span class="hljs-keyword">if</span>(data.indexOf(<span class="hljs-string">"99"</span>)==<span class="hljs-number">0</span>) { 
        alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(@$gdpr_error_message); ?&gt;'</span>); 
    }
    <span class="hljs-keyword">else</span> { alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(__('</span>There was a problem <span class="hljs-keyword">with</span> the alert<span class="hljs-string">', '</span>bender<span class="hljs-string">')); ?&gt;'</span>);
}
</code></pre>
<h3>Final result</h3>
<p>alert-form.php will end up like this:</p>
<pre><code class="language-html"><span class="php"><span class="hljs-preprocessor">&lt;?php</span>
<span class="hljs-variable">$current_locale</span> = osc_current_user_locale();
<span class="hljs-variable">$gdpr_error_message</span> = osc_get_preference(<span class="hljs-string">'error_agree_text_'</span> . <span class="hljs-variable">$current_locale</span>, <span class="hljs-string">'gdpr_osclass'</span>);
<span class="hljs-keyword">if</span>(<span class="hljs-variable">$gdpr_error_message</span>==<span class="hljs-string">""</span>) {
    <span class="hljs-variable">$gdpr_error_message</span> = __(<span class="hljs-string">'You must accept our "Terms and Conditions" and "Privacy Policy"'</span>, <span class="hljs-string">'bender'</span>);
}
<span class="hljs-preprocessor">?&gt;</span></span>
<span class="hljs-tag">&lt;<span class="hljs-title">script</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"text/javascript"</span>&gt;</span><span class="javascript">
$(<span class="hljs-built_in">document</span>).ready(<span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params"></span>)</span>{
    $(<span class="hljs-string">".sub_button"</span>).click(<span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params"></span>)</span>{
    $.post(<span class="hljs-string">'&lt;?php echo osc_base_url(true); ?&gt;'</span>, {email:$(<span class="hljs-string">"#alert_email"</span>).val(), userid:$(<span class="hljs-string">"#alert_userId"</span>).val(), alert:$(<span class="hljs-string">"#alert"</span>).val(), page:<span class="hljs-string">"ajax"</span>, action:<span class="hljs-string">"alerts"</span>, gdpr_osclass: $(<span class="hljs-string">'.alert_form input[name="gdpr_osclass"]:checked'</span>).val()},
            <span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params">data</span>)</span>{
                <span class="hljs-keyword">if</span>(data==<span class="hljs-number">1</span>) { alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(__('</span>You have sucessfully subscribed to the alert<span class="hljs-string">', '</span>bender<span class="hljs-string">')); ?&gt;'</span>); }
                <span class="hljs-keyword">else</span> <span class="hljs-keyword">if</span>(data==-<span class="hljs-number">1</span>) { alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(__('</span>Invalid email address<span class="hljs-string">', '</span>bender<span class="hljs-string">')); ?&gt;'</span>); }
                <span class="hljs-keyword">else</span> <span class="hljs-keyword">if</span>(data.indexOf(<span class="hljs-string">"99"</span>)==<span class="hljs-number">0</span>) { 
                    alert(<span class="hljs-string">'&lt;?php echo osc_esc_js($gdpr_error_message); ?&gt;'</span>); 
                }
                <span class="hljs-keyword">else</span> { alert(<span class="hljs-string">'&lt;?php echo osc_esc_js(__('</span>There was a problem <span class="hljs-keyword">with</span> the alert<span class="hljs-string">', '</span>bender<span class="hljs-string">')); ?&gt;'</span>);
            }
        });
        <span class="hljs-keyword">return</span> <span class="hljs-literal">false</span>;
    });

    <span class="hljs-keyword">var</span> sQuery = <span class="hljs-string">'&lt;?php echo osc_esc_js(AlertForm::default_email_text()); ?&gt;'</span>;

    <span class="hljs-keyword">if</span>($(<span class="hljs-string">'input[name=alert_email]'</span>).val() == sQuery) {
        $(<span class="hljs-string">'input[name=alert_email]'</span>).css(<span class="hljs-string">'color'</span>, <span class="hljs-string">'gray'</span>);
    }
    $(<span class="hljs-string">'input[name=alert_email]'</span>).click(<span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params"></span>)</span>{
        <span class="hljs-keyword">if</span>($(<span class="hljs-string">'input[name=alert_email]'</span>).val() == sQuery) {
            $(<span class="hljs-string">'input[name=alert_email]'</span>).val(<span class="hljs-string">''</span>);
            $(<span class="hljs-string">'input[name=alert_email]'</span>).css(<span class="hljs-string">'color'</span>, <span class="hljs-string">''</span>);
        }
    });
    $(<span class="hljs-string">'input[name=alert_email]'</span>).blur(<span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params"></span>)</span>{
        <span class="hljs-keyword">if</span>($(<span class="hljs-string">'input[name=alert_email]'</span>).val() == <span class="hljs-string">''</span>) {
            $(<span class="hljs-string">'input[name=alert_email]'</span>).val(sQuery);
            $(<span class="hljs-string">'input[name=alert_email]'</span>).css(<span class="hljs-string">'color'</span>, <span class="hljs-string">'gray'</span>);
        }
    });
    $(<span class="hljs-string">'input[name=alert_email]'</span>).keypress(<span class="hljs-function"><span class="hljs-keyword">function</span>(<span class="hljs-params"></span>)</span>{
        $(<span class="hljs-string">'input[name=alert_email]'</span>).css(<span class="hljs-string">'background'</span>,<span class="hljs-string">''</span>);
    });
});
</span><span class="hljs-tag">&lt;/<span class="hljs-title">script</span>&gt;</span>

<span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"alert_form"</span>&gt;</span>
    <span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">if</span>(function_exists(<span class="hljs-string">'osc_search_alert_subscribed'</span>) &amp;&amp; osc_search_alert_subscribed()) { <span class="hljs-preprocessor">?&gt;</span></span>
        <span class="hljs-tag">&lt;<span class="hljs-title">h3</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">strong</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Already subscribed to this search'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">strong</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">h3</span>&gt;</span>
    <span class="php"><span class="hljs-preprocessor">&lt;?php</span> } <span class="hljs-keyword">else</span> { <span class="hljs-preprocessor">?&gt;</span></span>
        <span class="hljs-tag">&lt;<span class="hljs-title">h3</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">strong</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Subscribe to this search'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">strong</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">h3</span>&gt;</span>
        <span class="hljs-tag">&lt;<span class="hljs-title">form</span> <span class="hljs-attribute">action</span>=<span class="hljs-value">"<span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">echo</span> osc_base_url(<span class="hljs-keyword">true</span>); <span class="hljs-preprocessor">?&gt;</span></span>"</span> <span class="hljs-attribute">method</span>=<span class="hljs-value">"post"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"sub_alert"</span> <span class="hljs-attribute">id</span>=<span class="hljs-value">"sub_alert"</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"nocsrf"</span>&gt;</span>
            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::page_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::alert_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>

            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">if</span>(osc_is_web_user_logged_in()) { <span class="hljs-preprocessor">?&gt;</span></span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::user_id_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::email_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>

            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> } <span class="hljs-keyword">else</span> { <span class="hljs-preprocessor">?&gt;</span></span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::user_id_hidden(); <span class="hljs-preprocessor">?&gt;</span></span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> AlertForm::email_text(); <span class="hljs-preprocessor">?&gt;</span></span>

            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> }; <span class="hljs-preprocessor">?&gt;</span></span>

            <span class="php"><span class="hljs-preprocessor">&lt;?php</span> osc_run_hook(<span class="hljs-string">'gdpr'</span>); <span class="hljs-preprocessor">?&gt;</span></span>

            <span class="hljs-tag">&lt;<span class="hljs-title">button</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"submit"</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"sub_button"</span> &gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Subscribe now'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span>!<span class="hljs-tag">&lt;/<span class="hljs-title">button</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">form</span>&gt;</span>
    <span class="php"><span class="hljs-preprocessor">&lt;?php</span> } <span class="hljs-preprocessor">?&gt;</span></span>
<span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
</code></pre>
