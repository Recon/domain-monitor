<?php require(__DIR__ . '/common/header_light.php') ?>

<div class="col-md-12">
    <form class="form-horizontal" method="POST" action="install_submit">

        <div class="text-center">
            <h1>Installation</h1>
        </div>

        <?php if (!empty($form_errors)): ?>
            <div class="alert alert-danger">
                <p>
                    <strong>There are some errors: </strong><br>
                </p>
                <ul>
                    <?php foreach ($form_errors as $form_error) : ?>
                        <li><?php echo $form_error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif ?>


        <h3>Database</h3>
        <hr>

        <div class="form-group">
            <label class="col-md-2 control-label">MySQL Server</label>
            <div class="col-md-10">
                <input type="text"
                       class="form-control"
                       name="db_host"
                       value="<?php echo $data->db_host ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">MySQL Port</label>
            <div class="col-md-10">
                <input type="text"
                       class="form-control"
                       name="db_port"
                       value="<?php echo $data->db_port ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">MySQL Databse</label>
            <div class="col-md-10">
                <input
                    type="text"
                    class="form-control"
                    name="db_name"
                    value="<?php echo $data->db_name ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">MySQL User</label>
            <div class="col-md-10">
                <input
                    type="text"
                    class="form-control"
                    name="db_user"
                    value="<?php echo $data->db_user ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">MySQL Password</label>
            <div class="col-md-10">
                <input
                    type="password"
                    class="form-control"
                    name="db_pass"
                    value="<?php echo $data->db_pass ?>">
            </div>
        </div>


        <br>
        <h3>Mailer</h3>
        <hr>

        <div class="form-group">
            <label class="col-md-2 control-label">Transport</label>
            <div class="col-md-3">
                <select
                    class="form-control"
                    id="select-mail-transport"
                    name="mail_transport">
                    <option
                        <?php echo $data->mail_transport == 'mail' ? 'selected' : '' ?>
                        value="mail">
                        mail() function
                    </option>
                    <option
                        <?php echo $data->mail_transport == 'smtp' ? 'selected' : '' ?>
                        value="smtp">
                        SMTP
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group transport-smtp">
            <label class="col-md-2 control-label">SMTP Server</label>
            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control"
                    name="smtp_server"
                    value="<?php echo $data->smtp_server ?>">
            </div>
        </div>
        <div class="form-group transport-smtp">
            <label class="col-md-2 control-label">SMTP Port</label>
            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control"
                    name="smtp_port"
                    value="<?php echo $data->smtp_port ?>">
            </div>
        </div>
        <div class="form-group transport-smtp">
            <label class="col-md-2 control-label">SMTP Encryption</label>
            <div class="col-md-3">
                <select
                    class="form-control"
                    name="smtp_encryption">
                    <option
                        value=""
                        <?php echo $data->smtp_encryption == '' ? 'selected' : '' ?>>
                        None
                    </option>
                    <option
                        value="ssl"
                        <?php echo $data->smtp_encryption == 'ssl' ? 'selected' : '' ?>>
                        SSL
                    </option>
                    <option
                        value="tls"
                        <?php echo $data->smtp_encryption == 'tls' ? 'selected' : '' ?>>
                        TLS
                    </option>
                </select>
            </div>
        </div>
        <div class="form-group transport-smtp">
            <label class="col-md-2 control-label">SMTP User</label>
            <div class="col-md-3">
                <input
                    type="text"
                    class="form-control"
                    name="smtp_user"
                    value="<?php echo $data->smtp_user ?>">
            </div>
        </div>
        <div class="form-group transport-smtp">
            <label class="col-md-2 control-label">SMTP Password</label>
            <div class="col-md-3">
                <input
                    type="password"
                    class="form-control"
                    name="smtp_pass"
                    value="<?php echo $data->smtp_pass ?>">
            </div>
        </div>

        <br>
        <h3>Administrator</h3>
        <hr>

        <div class="form-group">
            <label class="col-md-2 control-label">Email</label>
            <div class="col-md-10">
                <input
                    type="text"
                    class="form-control"
                    name="admin_email"
                    value="<?php echo $data->admin_email ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Password</label>
            <div class="col-md-10">
                <input
                    type="password"
                    class="form-control"
                    name="admin_pass"
                    value="<?php echo $data->admin_pass ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">Password (again)</label>
            <div class="col-md-10">
                <input
                    type="password"
                    class="form-control"
                    name="admin_pass_repeat"
                    value="<?php echo $data->admin_pass_repeat ?>">
            </div>
        </div>

        <br>
        <hr>

        <div class="form-group">
            <div class="col-md-3">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-save"></i> Submit
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    (function ($) {
        var updateMailConfigurationFields = function () {
            var type = $('#select-mail-transport').val();
            $('.form-group.transport-smtp').toggle(type === 'smtp');
        }

        $('#select-mail-transport').on('change', updateMailConfigurationFields);
        updateMailConfigurationFields();
    })(jQuery);
</script>

<?php require(__DIR__ . '/common/footer_light.php') ?>
