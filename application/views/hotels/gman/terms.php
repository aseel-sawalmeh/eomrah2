<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <!-- <h3 class="text-center">Insert Terms</h3> -->
			<?php echo $this->session->flashdata('terms_updated'); ?>

        </div>
        <div class="card-body">
            <!-- <div class="row">
                <div class="col-md-12 mx-auto">
                    <?=form_open(site_url('gman/main/terms'))?>
                    <div class="form-group">
                        <label>CONTENT</label>
                        <label class="text-center text-danger"><?php echo form_error('term_content'); ?></label>
                        <textarea id="editor" rows="12" cols="5" class="form-control" name="term_content"
                            placeholder="Insert Content"><?=$terms->content;?></textarea>
                    </div>
                    <div >
                        <input type="submit" value="Insert" class="btn btn-warning">
                    </div>
                    <?=form_close()?>
                </div>
            </div> -->

            <!-- <div class="row">
                <div class="col-md-12 mx-auto">

                    <?=form_open(site_url('gman/main/translate_terms'))?>
                    <div class="form-group text-center">
                        <input type="hidden" class="form-control" name="edit_terms" value="<?=$terms->content;?>" />
                        <input type="hidden" class="form-control" name="term_id" value="<?=$terms->id;?>" />
                    </div>
                    <select class="form-control mt-3" name="terms_lang_to">
                        <?php foreach(langs() as $lang): ?>
                        <option value="<?=$lang->code?>"><?=$lang->lang_name?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="mt-3">
                        <input type="submit" value="Translate" class="btn btn-warning">
                    </div>
                    <?=form_close();?>
                    <?=validation_errors()?>
                    <?=$this->session->flashdata('Terms_translated')?>
                </div>
            </div>

            <hr> -->
            <h3><?=lang('terms')?></h3>
			<hr>

            <div class="row mt-4">
                <div class="col">
					<?= form_open('gman/main/update_terms/'.$terms->id)?>
                    <div class="form-group">
                        
                        <textarea value="" name="terms_update" class="form-control" id="editor2" rows="12" cols="5">
						<?php echo tolang($terms->id, 'terms_text');?>
						</textarea>
                    </div>
					<input type="submit" value="<?=lang('edit')?>" class="btn btn-primary"/>
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