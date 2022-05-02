
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="lead mb-3">GDPR checkbox</div> 

                    <div class="mb-2 p-2">
                        <ul class="list-unstyled text-justify">
                            <li class="mb-1">Consent requires a positive opt-in. Don’t use pre-ticked boxes or any other method of default consent.</li>
                            <li>Ask people to positively opt in.</li>
                        </ul>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/settings.php'); ?>" class="btn btn-primary btn-block">Manage</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="lead mb-3">Right to erasure</div>  

                    <div class="mb-2 p-2">
                        <ul class="list-unstyled text-justify">
                            <li class="mb-1">The GDPR introduces a right for individuals to have personal data erased.</li>
                            <li class="mb-1">The right to erasure is also known as ‘the right to be forgotten’.</li>
                            <li class="mb-1">Individuals can make a request for erasure verbally or in writing.</li>
                            <li>You have one month to respond to a request.</li>
                        </ul>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/erasure.php'); ?>" class="btn btn-primary btn-block">Manage</a>
                    </div> 
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="lead mb-3">Right to data portability</div> 
                    
                    <div class="mb-2 p-2">
                        <ul class="list-unstyled text-justify">
                            <li class="mb-1">The right to data portability allows individuals to obtain and reuse their personal data for their own purposes across different services.</li>
                            <li>It allows them to move, copy or transfer personal data easily from one IT environment to another in a safe and secure way, without affecting its usability.</li>
                        </ul>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo osc_admin_render_plugin_url('gdpr_osclass/admin/portability.php'); ?>" class="btn btn-primary btn-block">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>