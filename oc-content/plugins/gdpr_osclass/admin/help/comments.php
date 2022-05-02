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

<h1><a id="Comment_form__Bender_theme_1"></a>Comment form - Bender theme</h1>
<p>Locate the following file and open it with your preferred editor.</p>
<pre><code class="language-sh">$ bender/item.php
</code></pre>
<h3><a id="1_Add_I_agree_input_to_form_7"></a>1) Add “I agree” input to form.</h3>
<p>Paste this line inside <em>create comment form</em> to include “I agree” checkbox to alert form.</p>
<pre><code class="language-php"><span class="hljs-preprocessor">&lt;?php</span> osc_run_hook(<span class="hljs-string">'gdpr'</span>); <span class="hljs-preprocessor">?&gt;</span>
</code></pre>
<p>Here with bender styles:</p>
<pre><code class="language-html"><span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-group"</span>&gt;</span>
    <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"controls"</span>&gt;</span>
        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> osc_run_hook(<span class="hljs-string">'gdpr'</span>); <span class="hljs-preprocessor">?&gt;</span></span>
    <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
<span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
</code></pre>
<h3><a id="Final_result_21"></a>Final result</h3>
<p>Comment form section will end up like this:</p>
<pre><code class="language-html"><span class="hljs-tag">&lt;<span class="hljs-title">form</span> <span class="hljs-attribute">action</span>=<span class="hljs-value">"<span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">echo</span> osc_base_url(<span class="hljs-keyword">true</span>); <span class="hljs-preprocessor">?&gt;</span></span>"</span> <span class="hljs-attribute">method</span>=<span class="hljs-value">"post"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"comment_form"</span> <span class="hljs-attribute">id</span>=<span class="hljs-value">"comment_form"</span>&gt;</span>
    <span class="hljs-tag">&lt;<span class="hljs-title">fieldset</span>&gt;</span>

        <span class="hljs-tag">&lt;<span class="hljs-title">input</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"hidden"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"action"</span> <span class="hljs-attribute">value</span>=<span class="hljs-value">"add_comment"</span> /&gt;</span>
        <span class="hljs-tag">&lt;<span class="hljs-title">input</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"hidden"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"page"</span> <span class="hljs-attribute">value</span>=<span class="hljs-value">"item"</span> /&gt;</span>
        <span class="hljs-tag">&lt;<span class="hljs-title">input</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"hidden"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"id"</span> <span class="hljs-attribute">value</span>=<span class="hljs-value">"<span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">echo</span> osc_item_id(); <span class="hljs-preprocessor">?&gt;</span></span>"</span> /&gt;</span>
        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">if</span>(osc_is_web_user_logged_in()) { <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="hljs-tag">&lt;<span class="hljs-title">input</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"hidden"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"authorName"</span> <span class="hljs-attribute">value</span>=<span class="hljs-value">"<span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">echo</span> osc_esc_html( osc_logged_user_name() ); <span class="hljs-preprocessor">?&gt;</span></span>"</span> /&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">input</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"hidden"</span> <span class="hljs-attribute">name</span>=<span class="hljs-value">"authorEmail"</span> <span class="hljs-attribute">value</span>=<span class="hljs-value">"<span class="php"><span class="hljs-preprocessor">&lt;?php</span> <span class="hljs-keyword">echo</span> osc_logged_user_email();<span class="hljs-preprocessor">?&gt;</span></span>"</span> /&gt;</span>
        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> } <span class="hljs-keyword">else</span> { <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-group"</span>&gt;</span>
                <span class="hljs-tag">&lt;<span class="hljs-title">label</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-label"</span> <span class="hljs-attribute">for</span>=<span class="hljs-value">"authorName"</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Your name'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">label</span>&gt;</span>
                <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"controls"</span>&gt;</span>
                    <span class="php"><span class="hljs-preprocessor">&lt;?php</span> CommentForm::author_input_text(); <span class="hljs-preprocessor">?&gt;</span></span>
                <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
            <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-group"</span>&gt;</span>
                <span class="hljs-tag">&lt;<span class="hljs-title">label</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-label"</span> <span class="hljs-attribute">for</span>=<span class="hljs-value">"authorEmail"</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Your e-mail'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">label</span>&gt;</span>
                <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"controls"</span>&gt;</span>
                    <span class="php"><span class="hljs-preprocessor">&lt;?php</span> CommentForm::email_input_text(); <span class="hljs-preprocessor">?&gt;</span></span>
                <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
            <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
        <span class="php"><span class="hljs-preprocessor">&lt;?php</span> }; <span class="hljs-preprocessor">?&gt;</span></span>
        <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-group"</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">label</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-label"</span> <span class="hljs-attribute">for</span>=<span class="hljs-value">"title"</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Title'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">label</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"controls"</span>&gt;</span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> CommentForm::title_input_text(); <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
        <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-group"</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">label</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-label"</span> <span class="hljs-attribute">for</span>=<span class="hljs-value">"body"</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Comment'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">label</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"controls textarea"</span>&gt;</span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> CommentForm::body_input_textarea(); <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>

        <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"control-group"</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"controls"</span>&gt;</span>
                <span class="php"><span class="hljs-preprocessor">&lt;?php</span> osc_run_hook(<span class="hljs-string">'gdpr'</span>); <span class="hljs-preprocessor">?&gt;</span></span>
            <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>

        <span class="hljs-tag">&lt;<span class="hljs-title">div</span> <span class="hljs-attribute">class</span>=<span class="hljs-value">"actions"</span>&gt;</span>
            <span class="hljs-tag">&lt;<span class="hljs-title">button</span> <span class="hljs-attribute">type</span>=<span class="hljs-value">"submit"</span>&gt;</span><span class="php"><span class="hljs-preprocessor">&lt;?php</span> _e(<span class="hljs-string">'Send'</span>, <span class="hljs-string">'bender'</span>); <span class="hljs-preprocessor">?&gt;</span></span><span class="hljs-tag">&lt;/<span class="hljs-title">button</span>&gt;</span>
        <span class="hljs-tag">&lt;/<span class="hljs-title">div</span>&gt;</span>

    <span class="hljs-tag">&lt;/<span class="hljs-title">fieldset</span>&gt;</span>
<span class="hljs-tag">&lt;/<span class="hljs-title">form</span>&gt;</span>
</code></pre>