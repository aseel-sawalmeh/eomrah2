<!-- edit Text Translation -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <h3><?=$this->session->flashdata('statusMsg')?></h3>
            <div class="md-card-content">
                <div class="uk-overflow-container">
                    <a class="md-btn md-btn-primary" href="<?=base_url("gman/hotel_system/husers/activate_step3/$user_id")?>" > Continue activation</a><hr>
                <?php echo validation_errors(); ?>
                <?php echo form_open(); ?>
                <input type="hidden" name="hotel_id" value="<?=$hotel_id?>" />
                <label>Source Language</label>
                <select name="from_lang">
                <?php foreach($langs as $lang){echo "<option value='{$lang->code}'>{$lang->lang_name}</option> ";}?>
                </select>
                <br>
                <br>
                <input type="hidden" name="txt_type" value="<?=$txt_type?>" />
                <input type="hidden" name="txt_id" value="<?=$txts['Text_ID']?>" />
                <p>Hotel Description</p>
                <textarea name="s_txt" cols="40" rows="5"><?=$txts['en']?></textarea>
               <button class="md-btn md-btn-primary" type="submit" >Translate to all languages</button>
                </form>
                <hr>
                <p>translate to seleted languages</p>
        <div>
     <?php foreach($langs as $lang): ?>
<form method="post" action="<?=base_url()?>gman/prodtxt/edit_single_txt/<?=$hotel_id."/".$txts['Text_ID']."/".$txt_type."/".$lang->code?>">
 <fieldset>
  <legend><?=$lang->lang_name?>:</legend>
  Text: <input size="50" type="text" name="<?=$lang->code?>" value="<?=$txts[$lang->code]?>">
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