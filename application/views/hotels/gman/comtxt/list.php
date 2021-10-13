<!-- Table Cutt of users -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                <?php if($this->session->flashdata('com_edit_mgs') !== NULL){
                            echo "<h4 style='text-align:center; color:Green'>";
                            echo $this->session->flashdata('product_add_mgs');
                            echo "</h4>";
                        }?>
                    <h3 class="uk-text-center"><?=comtrans('note')." ".comtrans('readonly')?></h3>
                    <table class="uk-table uk-table-nowrap table_check">
                        <thead>
                        <tr>
                            <th class="uk-width-1-10 uk-text-center small_col"><input type="checkbox" data-md-icheck class="check_all"></th>
                            <th class="uk-width-1-10 uk-text-center">Component ID</th>
                            <th class="uk-width-2-10 uk-text-center">Component Key</th>
                            <th class="uk-width-1-10 uk-text-center">Component En Value</th>
                            <th class="uk-width-1-10 uk-text-center">Component Ar Value</th>
                            <th class="uk-width-2-10 uk-text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($coms) : foreach($coms as $com): ?>
                            <tr>
                                <td class="uk-text-center uk-table-middle small_col"><input type="checkbox" data-md-icheck class="check_row"></td>
                                <td class="uk-text-center"><?=$com->Com_ID?></td>
                                <td class="uk-text-center">  <?=$com->Com_Key?> </td>
                                <td class="uk-text-center"> <?=$com->en?> </td>
                                <td class="uk-text-center"> <?=$com->ar?> </td>
                                <td class="uk-text-center">
                                <a href="<?=base_url('gman')?>/comtxt/edit/<?=$com->Com_ID?>"><i class="md-icon material-icons">&#xE254;</i></a>
                                <a href="#"><i class="md-icon material-icons">&#xE88F;</i></a>
                                </td>

                            </tr>
                            <?php endforeach; else:?>
                            <tr>
                                <h4 class="uk-text-center"><?=comtrans('msgnodata')?></h4>
                            </tr>
                    <?php endif;?>
                        </tbody>
                    </table>
                </div>
         <!-- pagination generated -->
         <!-- pagination generated -->
            </div>
        </div>
    </div>
</div>