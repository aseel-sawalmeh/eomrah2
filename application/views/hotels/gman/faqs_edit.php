<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
        <div class="row">
            <div class="col">
                <p class="text-success"><?php echo $this->session->flashdata('qs_translate'); ?></p>
                <p class="text-success"><?php echo $this->session->flashdata('ans_translate'); ?></p>
            </div>
        </div>
            <!-- <div class="row">
                <div class="col-md-12 mx-auto">

                    <?php echo form_open("gman/faqs/edit/$faq->id");?>
                    <div class="form-group">
                        <label class="">Question Category</label>
                        <select class="form-control" name="faq_cat">
                            <?php foreach ($cat as $c) : ?>
                            <option value="<?php echo $c->cat_id; ?>"><?php echo $c->cat_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="">Question</label>

                        <textarea id="editor" name="question_edit" class="form-control"><?=$faq->question?></textarea>

                    </div>
                    <div class="form-group">
                        <label class="">Answer</label>
                        <textarea id="editor2" name="answer_edit" value="" class="form-control"
                            rows="10"><?= $faq->answer ?></textarea>
                    </div>

                    <div class="">
                        <input type="submit" value="Submit" class="btn btn-warning">
                    </div>
                    <?php echo form_close();?>
                </div>
            </div> -->
<!-- 
            <div class="row">
                <div class="col-md-12 mx-auto">

                    <?php echo form_open("gman/faqs/translate/$faq->id");?>
                    <div class="form-group">

                        <input type="hidden" value="<?= $faq->question ?>" class="form-control" name="qs_edit">
                    </div>
                    <div class="form-group">

                        <input type="hidden" id="editor" name="ans_edit" class="form-control"
                            value="<?= $faq->answer?>" />
                    </div>

                    <div class="form-group">

                        <select name="lang_to">
                            <?php foreach(langs() as $lang): ?>
                            <option value="<?=$lang->code?>"><?=$lang->lang_name?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="">
                        <input type="submit" value="translate" class="btn btn-warning">
                    </div>
                    </form>
                </div>
                <?=validation_errors()?>
                <?=$this->session->flashdata('faq_translated')?>
            </div> -->
            <hr>
               <h3 class="text-center"><?= lang('edit')?></h3>
            <hr>
            <div class="row">
                <div class="col">
                    <?=form_open(site_url('gman/faqs/edit_qs_trans/').$faq->id)?>
                    <div class="form-group">
                        <label><?=lang('question')?></label>
                        <textarea name="qstrans_edit" class="form-control" id="editor3"><?php echo tolang($faq->id, 'qsfaq')?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="<?=lang('edit')?>"/>
                    </div>
                    <?=form_close()?>
                    <?=form_open(site_url('gman/faqs/edit_ans_trans/').$faq->id)?>
                    <div class="form-group">
                        <label><?=lang('answer')?></label>
                        <textarea name="anstrans_edit" class="form-control" id="editor4"><?php echo tolang($faq->id, 'ansfaq')?></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="<?=lang('edit')?>"/>
                    </div>
                    <?=form_close()?>
                </div>
            </div>
        </div>
    </div>

</div>


<script src="<?= base_url('admin_design/editor/ckeditor.js') ?>"></script>

<script>
if (CKEDITOR.env.ie && CKEDITOR.env.version < 9)
    CKEDITOR.tools.enableHtml5Elements(document);

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = (function() {
    var wysiwygareaAvailable = isWysiwygareaAvailable(),
        isBBCodeBuiltIn = !!CKEDITOR.plugins.get('bbcode');

    return function() {
        var editorElement = CKEDITOR.document.getById('editor');

        // :(((
        if (isBBCodeBuiltIn) {
            editorElement.setHtml(
                'Hello world!\n\n' +
                'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
            );
        }

        // Depending on the wysiwygarea plugin availability initialize classic or inline editor.
        if (wysiwygareaAvailable) {
            CKEDITOR.replace('editor');
            CKEDITOR.replace('editor2');
            CKEDITOR.replace('editor3');
            CKEDITOR.replace('editor4');
        } else {
            editorElement.setAttribute('contenteditable', 'true');
            CKEDITOR.inline('editor');

            // TODO we can consider displaying some info box that
            // without wysiwygarea the classic editor may not work.
        }
    };

    function isWysiwygareaAvailable() {
        // If in development mode, then the wysiwygarea must be available.
        // Split REV into two strings so builder does not replace it :D.
        if (CKEDITOR.revision == ('%RE' + 'V%')) {
            return true;
        }

        return !!CKEDITOR.plugins.get('wysiwygarea');
    }
})();

initSample();
</script>