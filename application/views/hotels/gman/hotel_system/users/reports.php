<!-- Table Cutt of users -->
<div id="page_content">
    <div id="page_content_inner">
    <a href="<?=base_url('gman')?>/users/add" class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light">Add New Hotel User</a>
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                <?php if($this->session->flashdata('user_add_mgs') !== NULL){
                            echo "<h4 style='text-align:center; color:Green'>";
                            echo $this->session->flashdata('user_add_mgs');
                            echo "</h4>";
                        }?>
                    <h2>Hotel System Active Users</h2>
                    <table class="uk-table uk-table-nowrap table_check">
                        <thead>
                        <tr>
                            <th class="uk-width-1-10 uk-text-center small_col"><input type="checkbox" data-md-icheck class="check_all"></th>
                            <th class="uk-width-1-10 uk-text-center">User Image</th>
                            <th class="uk-width-2-10">User Full Name</th>
                            <th class="uk-width-1-10 uk-text-center">User Login Name</th>
                            <th class="uk-width-1-10 uk-text-center">User Email</th>
                            <th class="uk-width-1-10 uk-text-center">Phone</th>
                            <th class="uk-width-2-10 uk-text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($next_hotel_users){ foreach($next_hotel_users as $user): ?>
                            <tr>
                                <td class="uk-text-center uk-table-middle small_col"><input type="checkbox" data-md-icheck class="check_row"></td>
                                <td class="uk-text-center"><img class="md-user-image" src="<?=base_url()?>admin_design/assets/img/avatars/avatar_01_tn.png" alt=""/></td>
                                <td><?=$user->H_User_FullName?></td>
                                <td class="uk-text-center"><?=$user->H_UserName?></td>
                                <td class="uk-text-center"><?=$user->H_User_Email?></td>
                                <td class="uk-text-center"><?=$user->H_User_Phone?></td>
                                <td class="uk-text-center">
                                <a href="<?=base_url('gman')?>/users/edit/<?=$user->H_User_ID?>"><i class="md-icon material-icons">&#xE254;</i></a>
                                <a href="#"><i class="md-icon material-icons">&#xE88F;</i></a>
                                </td>
                            </tr>
                            <?php endforeach; }else{echo "<tr><h2 style='color:red; text-align:center'> No Active Users Yet </tr>";} ?>

                        </tbody>
                    </table>
                </div>
         <!-- pagination generated -->
         <div id="pagination">
         <?=$pagination_links?>
         </div>
         <!-- pagination generated -->
            </div>
        </div>
    </div>
</div>
