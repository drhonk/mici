<?PHP
/**
 * @todo 	To use this as a template replace:
 * 
 * 			@ICON@ = Icon Used
 *			@CONTROLLER@ = Controller Name ( lower case )
 * 			@TITLE@ = Title Name ( title case )
 *			@TITLE_PLURAL@ = Title Name Plural ( title case )
 *			@VAR@ = Variable Name ( lower case )
 *
 *			NOTE:  Don't use this template unless you are familiar with MICI
 */
?>
<h2><img src="<?PHP echo $media_url;?>admin/images/icons/24x24/@ICON@.png" alt="icon" style="vertical-align: middle; margin-top: -4px;" /> @TITLE@ Management</h2>
<p><?php echo anchor('admin/@CONTROLLER@/index', '&laquo; Back to @TITLE_PLURAL@', array('title' => 'Back to @TITLE@ Listing', 'class' => 'button'));?></p>
<br />
<div class="content-box">
    <div class="content-box-header">
        <h3><?PHP echo $add_or_edit; ?> @TITLE@</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
        <div class="tab-content default-tab" id="form">
        <?PHP if( !empty($error_message)): ?>
            <div class="notification error png_bg">
                <a href="#" class="close"><img src="<?PHP echo $media_url;?>system/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div><?PHP echo $error_message; ?></div>
            </div>
        <?PHP endif; ?>
            <?PHP
            $attributes = array(
                'name' => '@VAR@_management',
                'id' => '@VAR@_management'
            );
            echo form_open(current_url(), $attributes);
            ?>
                <table>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <input class="button" type="submit" value="Submit"/>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td width="125" class="right"><label for="name">Name:</label></td>
                            <td><input type="text" name="name" id="name" class="text-input medium-input" value="<?PHP echo $@VAR@->name; ?>" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="clear"></div>
            </form>
            <div class="notification png_bg" id="validation_notification" style="display: none;">
                <a href="#" class="close"><img src="<?PHP echo $media_url;?>admin/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
                <div>&nbsp;</div>
            </div>
        </div>
        <div class="tab-content" id="help"></div>
    </div>
</div>