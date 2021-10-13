<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
           
			<?=$this->session->flashdata('about_updated')?>
        </div>
        <div class="card-body">
            <!-- <div class="row">
                <div class="col-md-12 mx-auto">
					<?php echo form_open("gman/main/about");?>
                        <div class="form-group">
                            <label class="">CONTENT</label>
                            <label class="text-center text-danger"><?php echo form_error('about_content'); ?></label>
                            <textarea id="editor" rows="10" cols="5" class="form-control" id="editor" name="about_content" placeholder="Insert Content"><?=$about->content?></textarea>
						
                        </div>
                        <div class="">
                            <input type="submit" value="Insert" class="btn btn-warning">
                        </div>
                    </form>
                </div>
			</div> -->
			<!-- <div class="row">
                <div class="col-md-12 mx-auto">
					<?php echo form_open('gman/main/translate_about');?>
                        <div class="form-group">
							<input type="hidden" id="editor" class="form-control" id="editor" name="about_edit" value="<?=$about->content?>" />
							<input type="hidden" name="about_id" value="<?php echo $about->id; ?>"/>
							<select class="form-control mt-2"  name="about_lang_to">
								<?php foreach(langs() as $lang): ?>
									<option value="<?=$lang->code?>"><?=$lang->lang_name?></option>
								<?php endforeach; ?>
							</select>
                        </div>
                        <div class="mt-2">
                            <input type="submit" value="Translate" class="btn btn-warning">
						</div>
					</form>
					<?=validation_errors()?>
					<?=$this->session->flashdata('about_translated')?>
                </div>
            </div>
			<hr> -->
			<h3><?=lang('aboutus')?></h3>
			<hr>

			<div class="row">
                <div class="col-md-12 mx-auto">
					<?php echo form_open("gman/main/update_about/".$about->id);?>
                        <div class="form-group">
                            <label class="">CONTENT</label>
                            <label class="text-center text-danger"><?php echo form_error('about_update'); ?></label>
                            <textarea id="editor3" rows="10" cols="5" class="form-control" id="editor" name="about_update" placeholder="Insert Content"><?php echo tolang($about->id, 'about_text');?></textarea>
                        </div>
                        <div class="">
                            <input type="submit" value="<?=lang('edit')?>" class="btn btn-primary">
                        </div>
                    </form>
                </div>
			</div>
           
        </div>
    </div>
</div>

<script src="<?= base_url('admin_design/editor/ckeditor.js') ?>"></script>

<script>
	if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );


CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = ( function() {
	var wysiwygareaAvailable = isWysiwygareaAvailable(),
		isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

	return function() {
		var editorElement = CKEDITOR.document.getById( 'editor' );

		
		if ( isBBCodeBuiltIn ) {
			editorElement.setHtml(
				'Hello world!\n\n' +
				'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
			);
		}

		if ( wysiwygareaAvailable ) {
			CKEDITOR.replace( 'editor' );
			CKEDITOR.replace( 'editor3' );
		} else {
			editorElement.setAttribute( 'contenteditable', 'true' );
			CKEDITOR.inline( 'editor' );

		
		}
	};

	function isWysiwygareaAvailable() {
		
		if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
			return true;
		}

		return !!CKEDITOR.plugins.get( 'wysiwygarea' );
	}
} )();

initSample();
</script>