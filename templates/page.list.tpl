<!--

<div class="clear"></div>

<div class="wide" id="sort_options">
	<strong>Sort by: </strong>
	<div>
		<div id="sort_dir">
			<input type="radio" name="sortdir" value="asc" id="sort_asc" checked>
			<label for="sort_asc">Ascending</label>
			<input type="radio" name="sortdir" value="desc" id="sort_desc">
			<label for="sort_desc">Descending</label>
		</div>
		<div id="sort_list">
			<input type="radio" name="sort" value="provider" id="sort_provider" checked>
			<label for="sort_provider">Provider</label>
			<input type="radio" name="sort" value="name" id="sort_name">
			<label for="sort_name">Plan Name</label>
			<input type="radio" name="sort" value="platform" id="sort_platform">
			<label for="sort_platform">Platform</label>
			<input type="radio" name="sort" value="price" id="sort_price">
			<label for="sort_price">Price</label>
			<input type="radio" name="sort" value="guaranteed" id="sort_guaranteed">
			<label for="sort_guaranteed">Guaranteed RAM</label>
			<input type="radio" name="sort" value="burst" id="sort_burst">
			<label for="sort_burst">Burst RAM</label>
			<input type="radio" name="sort" value="cpu" id="sort_cpu">
			<label for="sort_cpu">CPU</label>
			<input type="radio" name="sort" value="disk" id="sort_disk">
			<label for="sort_disk">Disk</label>
			<input type="radio" name="sort" value="traffic" id="sort_traffic">
			<label for="sort_traffic">Traffic</label>
			<input type="radio" name="sort" value="bandwidth" id="sort_bandwidth">
			<label for="sort_bandwidth">Bandwidth</label>
			<input type="radio" name="sort" value="backup" id="sort_backup">
			<label for="sort_backup">Backup Space</label>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div class="clear"></div>

<div class="top-notices">
	<div class="notice"><strong>NOTE: An 'x 3' (or any other number) suffix in the Price column indicates a minimum billing period.</strong> Example: "$4.99 (x 3)" would indicate a
	monthly price of $4.99, but a billing period of 3 months. This means you pay $4.99 x 3 = $14.97 per 3 months.</div>
	<div class="warning"><strong>WARNING:</strong> That a provider is listed here does NOT mean they offer a good service or have a good reputation. Any provider
	can submit their plans to this site, as long as they have a website. Please do your research before ordering a service at any provider.</div>
</div>

-->


<div id="table_main">
	<div class="loading">
		Loading...
	</div>
	<!-- <div id="javascript_notice">You need Javascript for this table to work.</div> -->
	<table id="list">
		<thead>
			<tr>
				<th onclick="sort_table(column_map['provider'], this, false);" class="row-provider"><div class="rotate">Provider</div></th>
				<th onclick="sort_table(column_map['name'], this, false);" class="row-plan"><div class="rotate inside-plan">Plan Name</div></th>
				<th onclick="sort_table(column_map['platform'], this, true);" class="row-platform"><div class="rotate">Platform</div></th>
				<th onclick="sort_table(column_map['price'], this, true);" class="row-price"><div class="rotate">Price / month</div></th>
				<th onclick="sort_table(column_map['guaranteed'], this, true);" class="row-guaranteed"><div class="rotate">Guaranteed RAM</div></th>
				<th onclick="sort_table(column_map['burst'], this, true);" class="row-burstable"><div class="rotate">Burstable RAM</div></th>
				<th onclick="sort_table(column_map['cpu'], this, true);" class="row-cpu"><div class="rotate">CPU</div></th>
				<th onclick="sort_table(column_map['disk'], this, true);" class="row-disk"><div class="rotate">Disk</div></th>
				<th onclick="sort_table(column_map['traffic'], this, true);" class="row-traffic"><div class="rotate">Traffic</div></th>
				<th onclick="sort_table(column_map['bandwidth'], this, true);" class="row-bandwidth"><div class="rotate">Bandwidth</div></th>
				<th onclick="sort_table(column_map['ipv4'], this, true);" class="row-ipv4"><div class="rotate">IPv4</div></th>
				<th onclick="sort_table(column_map['ipv6'], this, true);" class="row-ipv6"><div class="rotate">IPv6</div></th>
				<th onclick="sort_table(column_map['irc'], this, true);" class="row-irc"><div class="rotate">Allows IRC</div></th>
				<th onclick="sort_table(column_map['dns'], this, true);" class="row-dns"><div class="rotate">Free DNS</div></th>
				<th onclick="sort_table(column_map['overage'], this, true);" class="row-overage"><div class="rotate">Overage Billing</div></th>
				<th onclick="sort_table(column_map['backup'], this, true);" class="row-backup"><div class="rotate">Backup Space</div></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
				<th class="data"></th>
			</tr>
		</thead>
		<tbody>
			<%?list>
		</tbody>
	</table>
	<!-- <div class="stretch"></div> -->
</div>
<div id="table_sidebar">
	<div id="filterbar">
		<div class="filter">
			<label>Guaranteed RAM: 
				<span id="filter_ram_min"><%?min-ram></span>MB - 
				<span id="filter_ram_max"><%?max-ram></span>MB
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_ram"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>Burstable RAM: 
				<span id="filter_burst_min"><%?min-burst></span>MB - 
				<span id="filter_burst_max"><%?max-burst></span>MB
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_burst"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>Disk space: 
				<span id="filter_disk_min"><%?min-disk></span>GB - 
				<span id="filter_disk_max"><%?max-disk></span>GB
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_disk"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>Traffic: 
				<span id="filter_traffic_min"><%?min-traffic></span>GB - 
				<span id="filter_traffic_max"><%?max-traffic></span>GB
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_traffic"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>Bandwidth: 
				<span id="filter_bandwidth_min"><%?min-bandwidth></span>mbit - 
				<span id="filter_bandwidth_max"><%?max-bandwidth></span>mbit
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_bandwidth"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>CPU Cores: 
				<span id="filter_cpu_min"><%?min-cpu></span> cores - 
				<span id="filter_cpu_max"><%?max-cpu></span> cores
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_cpu"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>Price: 
				$<span id="filter_price_min"><%?min-price></span> - 
				$<span id="filter_price_max"><%?max-price></span>
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_price"></div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="filter">
			<label>Free backup space: 
				<span id="filter_backup_min"><%?min-backup></span>GB - 
				<span id="filter_backup_max"><%?max-backup></span>GB
			</label>
			<div class="filter_select">
				<div class="filter_slider" id="slider_backup"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	
	<div id="pagination">
		<a class="left" href="#" onclick="previous_page(); return false;">◀</a>
		<a class="right" href="#" onclick="next_page(); return false;">▶</a>
		<div id="pagecounter"></div>
		<div class="clear"></div>
	</div>
</div>
