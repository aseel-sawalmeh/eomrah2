
<!-- Add Text Translation -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                <?php echo validation_errors(); ?>
                <?php echo form_open('', 'id="htlform"'); ?>
                <input type="hidden" name="hotel_id" value="<?=$hotel_id?>" />
                <div id="divtranall"></div>
                <label>Source Language</label>
                <select name="from_lang">
                <?php foreach($langs as $lang){echo "<option value='{$lang->code}'>{$lang->lang_name}</option> ";}?>
                </select>
                <br>
                <br>
                <p>Hotel Name</p>
                <textarea name="s_txt"><?=$user_hotel->Hotel_Name?></textarea>
                <hr>
                <a class="md-btn" onclick="trns4all()" > Apply to All Languages</a>
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
<script>
var htlform = document.getElementById('htlform');
var divtranall = document.getElementById('divtranall');
function trns4all(){
    divtranall.innerHTML = "<input id='tranAll' type='hidden' name='transall' value='1' />";
    htlform.submit();
}
</script>