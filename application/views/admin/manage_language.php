<div class="box">

	<div class="box-header">

    

    	<!------CONTROL TABS START------->

		<ul class="nav nav-tabs nav-tabs-left">

			<li class="active">

            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 

					<?php echo ('Phrase List');?>

                    	</a></li>

		</ul>

    	<!------CONTROL TABS END------->

        

	</div>

	<div class="box-content padded">

		<div class="tab-content">

            <!----TABLE LISTING STARTS--->

            <div class="tab-pane box active" id="list">

                <table cellpadding="0" cellspacing="0" border="0" class="table table-normal">

                	<thead>

                    	<tr>

                        	<th><?php echo ('Phrase');?></th>

                        </tr>

                    </thead>

                    <tbody>

                    	<?php

								$phrases = $this->db->get('language')->result_array();

								foreach ($phrases as $phrase) {

									echo '<tr><td>' . $phrase['phrase'] . '</td></tr>';

								}

                        ?>

                    </tbody>

                </table>

			</div>

            <!----TABLE LISTING ENDS--->

		</div>

	</div>

</div>