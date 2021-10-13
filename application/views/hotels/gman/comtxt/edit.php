<!-- edit Text Translation -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <h3 class="uk-text-center"><?=$this->session->flashdata('statusMsg')?></h3>
            <div class="md-card-content">
                <div class="uk-overflow-container">
                <?php echo validation_errors(); ?>
                <?php echo form_open(); ?>
                <input type="hidden" name="com_id" value="<?=$com->Com_ID?>" />
                <label>Source Language</label>
                <select name="from_lang">
                <?php foreach(langs() as $lang){echo "<option value='{$lang->code}'>{$lang->lang_name}</option> ";}?>
                </select>
                <br>
                <p>The component Key :- [<?=$com->Com_Key?>]</p>
                <textarea name="s_txt" cols="100" rows="5"><?=$com->en?></textarea>
                <br>
               <button class="md-btn md-btn-primary" type="submit" ><?=comtrans('trns2all')?></button>
                </form>
                <hr>
                <p><?=comtrans('trns2selected')?></p>
        <div>
     <?php foreach(langs() as $lang): ?>
        <form method="post" action="<?=base_url()?>gman/comtxt/edit_com/<?=$com->Com_ID."/".$lang->code?>">
        <fieldset>
        <legend><?=$lang->lang_name?>:</legend>
        Text: <input size="50" type="text" name="<?=$lang->code?>" value="<?=$com->{$lang->code}?>">
            <input type="submit" class="md-btn md-btn-success" value="Update" />
        </fieldset>
        </form>
        <?php endforeach; ?>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>