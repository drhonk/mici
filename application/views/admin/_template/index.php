<?PHP
/**
 * @todo 	To use this as a template replace:
 * 
 * 			@ICON@ = Icon Used
 *			@CONTROLLER@ = Controller Name ( lower case )
 * 			@TITLE@ = Title Name ( title case )
 *			@TITLE_PLURAL@ = Title Name Plural ( title case )
 *			@VAR@ = Variable Name ( lower case )
 *			@VAR_PLURAL@ = Variable Name Plural ( lower case )
 *
 *			NOTE:  Don't use this template unless you are familiar with MICI
 */
?>
<h2><img src="<?PHP echo $media_url;?>admin/images/icons/24x24/@ICON@.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> @TITLE@ Management</h2>
<p id="page-intro">Manage @TITLE_PLURAL@</p>
<div class="content-box">
    <div class="content-box-header">
        <h3>@TITLE_PLURAL@</h3>
        <p style="float: right; margin-right: 10px;"><?php echo anchor('admin/@CONTROLLER@/add', '[+] Add @TITLE@', array('title' => 'Add a new tag', 'class' => 'button')); ?></p>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab">
        <?PHP if (!empty($error_message)): ?>
            <div class="notification error png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div><?PHP echo $error_message; ?></div>
            </div>
        <?PHP endif; ?>
        <?php if (!empty($display_message)): ?>
            <div class="notification <?php echo (!empty($display_message_type)) ? $display_message_type : 'information'; ?> png_bg">
                <a href="#" class="close"><img src="<?php echo $media_url; ?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div><?php echo $display_message; ?></div>
            </div>
        <?php endif; ?>
        <?PHP if (count($@VAR_PLURAL@) > 0): ?>
            <div class="notification png_bg" style="display: none;" id="validation_notice">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
            <?PHP
            $attributes = array(
                'name' => 'tag_management',
                'id' => 'tag_management',
                'onsubmit' => 'return validate();'
            );
            echo form_open(current_url(), $attributes);
            ?>
                <table width="100%" id="data_table" class="display">
                    <thead>
                        <tr>
                            <th width="25"><div style="background: url('<?PHP echo $media_url; ?>admin/images/icons/16X16/checkmark.png') left top no-repeat; width: 16px; height: 16px; display: block;">&nbsp;</div></th>
                            <th width="200">Name</th>
                            <th width="125">Created</th>
                            <th>Last Modified</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <div class="bulk-actions align-left">
                                    <select name="action" id="action">
                                        <option value="" selected="selected">Choose an action...</option>
                                        <option value="delete" style="color: #990000;">Delete</option>
                                    </select>
                                    <input type="submit" class="button" value="Apply to Selected" /> <input class="check-all" type="checkbox" name="checkall" id="checkall" /><label for="checkall" class="check-all-label">select all</label>
                                </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                    <?PHP foreach ($@VAR_PLURAL@ as $@VAR@): ?>
                        <tr>
                            <td><input type="checkbox" value="<?PHP echo $@VAR@['id']; ?>" name="tags[]" /></td>
                            <td><?PHP echo anchor('admin/@CONTROLLER@/edit/id/' . $@VAR@['id'], $@VAR@['name'], array('title' => 'Edit ' . $@VAR@['name'])); ?></td>
                            <td><?PHP echo $@VAR@['created_at']; ?></td>
                            <td><?PHP echo $@VAR@['updated_at']; ?></td>
                        </tr>
                    <?PHP endforeach; ?>
                    </tbody>
                </table>
                <div class="clear"></div>
            </form>
        <?PHP else: ?>
            <div class="notification information png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url; ?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>There are currently no @TITLE_PLURAL@.</div>
            </div>
        <?PHP endif; ?>
        </div>
    </div>
</div>
<?PHP if (count($@VAR_PLURAL@) > 0): ?>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>admin/js/dt/jquery.data_tables.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>admin/js/dt/plugins/column_visible.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>admin/js/dt/plugins/fixed_header.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>admin/js/dt/jquery.data_tables_init.js"></script>
<script type="text/javascript" language="javascript" src="<?PHP echo $media_url; ?>admin/js/index_validate.js"></script>
<?PHP endif; ?>