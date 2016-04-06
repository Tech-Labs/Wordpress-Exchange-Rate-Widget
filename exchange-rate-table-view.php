<div class="table-responsive">
	<table class="currency table">
		<thead>
			<tr>
				<th><p class="p-prayer"><?php _e( 'Currency' , 'exchange-rate' )?></p></th>
				<th><p class="p-prayer"><?php _e( 'Price' , 'exchange-rate' )?></p></th>
			</tr>
		</thead>
		<tbody>
        <?php foreach ($data as $key => $val){ ?>
			<tr>
				<td><p class="p-prayer"><?php echo strtoupper($key)?></p></td>
				<td><p class="p-prayer"><i class="fa fa-money"></i> <?php echo $val.' '.$instance['Currency_title']?></p></td>
			</tr>
        <?php } ?>
		</tbody>
	</table>
</div>