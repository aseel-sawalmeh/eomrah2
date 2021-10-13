<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">

                    <?=form_open(site_url('gman/faqs/add'))?>
                    <div class="form-group">
                        <label><?=lang('catname')?></label>
                        <select class="form-control" name="faq_cat">
                            <?php foreach ($cat as $c) : ?>
                            <option value="<?php echo $c->cat_id; ?>"><?php echo $c->cat_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?=lang('question')?></label>
                       
						<textarea id="editor" name="question" class="form-control" rows="10"></textarea>

                    </div>
                    <div class="form-group">
                        <label><?=lang('answer')?></label>
                        <textarea id="editor2" name="answer" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="form-group">
						<label><?=lang('language')?></label>
						<select class="form-control" name="lang_to">
							<?php foreach(langs() as $lang): ?>
							<option value="<?=$lang->code?>"><?=$lang->lang_name?></option>
							<?php endforeach; ?>
						</select>
                    </div>

                    <div>
                        <input type="submit" value="Submit" class="btn btn-warning">
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