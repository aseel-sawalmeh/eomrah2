
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                   
                    <div class="md-card">
                        <div class="md-card-content">
                           
                            <h2 class="text-center">Provider Hotels List</h2>

                            <label>Search Hotel</label>
                            <input type='text' id='h_search'/>
                            <table id="resulttable" class="uk-table uk-table-nowrap table_check">
                                <thead>
                                    <tr>
                                        <th class="uk-width-1-10 uk-text-center">Hotel Name</th>
                                        <th class="uk-width-1-10 uk-text-center">Hotel Address</th>
                                        <th class="uk-width-1-10 uk-text-center">Phone</th>
                                        <th class="uk-width-1-10 uk-text-center">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php  foreach($provider_hotels as $p):?>
                                    <tr>
                                        <td class="uk-text-center"><?php echo $p->Hotel_Name;?></td>
                                        <td class="uk-text-center"><?php echo $p->Hotel_Address;?></td>
                                        <td class="uk-text-center"><?php echo $p->Hotel_Phone;?></td>
                                        <td class="uk-text-center"><?php echo $p->Hotel_Email;?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                   <?php echo $pagination_links; ?>
                </div>
            </div>
        </div>
    </div>
</div>