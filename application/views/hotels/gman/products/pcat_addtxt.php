<!-- Add Text Translation -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                <?php echo validation_errors(); ?>
                <?php echo form_open(); ?>
                <input type="hidden" name="pcat_id" value="<?=$pcat_id?>" />
                <label>Source Language</label>
                <select name="from_lang">
                <?php foreach($langs as $lang){echo "<option value='{$lang->code}'>{$lang->lang_name}</option> ";}?>
                </select>
                <br>
                <br>
                <input type="hidden" name="txt_type" value="<?=$txt_type?>" />
                <p>Product Category <?php if($txt_type == 'pcattitle'){
                        echo "Title";
                    }elseif($txt_type == 'pcatdesc'){
                        echo "Description";
                    }else{
    echo "error happened";
}
                    ?></p>
                <textarea name="s_txt"></textarea>
                <hr>
                <p>translate to seleted languages</p>
        <div>
     <?php foreach($langs as $lang){
            echo "<label>{$lang->lang_name}</label>";
            echo "<input type='checkbox' value='{$lang->code}' name='tolangs[{$lang->code}]' />";
        }?>
        </div>
                    <hr>
                   <button type="submit" >Translate</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>