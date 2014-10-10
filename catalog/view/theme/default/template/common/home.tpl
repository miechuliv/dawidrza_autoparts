<?php echo $header; ?><?php // echo $column_left; $column_right; $content_bottom; $content_top; ?>

<div class="home-con top">
	<div class="small">
		<div class="leftmenu">
			<div>
				<?php echo $column_left; ?>
			</div>
		</div>
	</div>
	<div class="center">
		<div>
			<div id="searchhome">
				<form>
					<h3>SZUKAJ PO MARCE</h3>
					<div id="serchinp">
						<div class="table">
							<div>
								<select name="marka" id="mara">
									<option>Marke</option>
									<option value="Audi">Audi</option>
									<option value="BMW">BMW</option>
								</select>
							</div>
							<div>
								<select name="model" id="model">
									<option>Model</option>
									<option value="A8">A8</option>
									<option value="E39">E39</option>
								</select>
							</div>
							<div>
								<select name="typ" id="typ">
									<option>Typ</option>
									<option value="Hatchback">Hatchback</option>
									<option value="Sedan">Sedan</option>
								</select>
							</div>
							<div>
								<button>
									<i class="fa fa-search"></i>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="small banerki">
		
	</div>
</div>

<div class="home-con bot">
	<div class="small">
		<div class="leftmenu">
			<div>
				<ul>
					<li><a href="#">Lorem ipsum lorem</a></li>
					<li><a href="#">Lorem lorem</a></li>
					<li><a href="#">Lorem ipsum lorem lor</a></li>
					<li><a href="#">Em ipsum lorem</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="center">
		<div>
			<h3>Popular</h3>
			<ul id="popular">
				<li><a href="#">Lorem ipsum lorem</a></li>
				<li><a href="#">Lorem lorem</a></li>
				<li><a href="#">Lorem lor</a></li>
				<li><a href="#">Em ipsum lorem</a></li>
				<li><a href="#">Lorem ipsum lorem</a></li>
				<li><a href="#">Lorem lore lorem</a></li>
				<li><a href="#">Lorem ipsum lorem lor</a></li>
			</ul>
		</div>
	</div>
	<div class="small">	
		<div>
			<h2>Käuferschutz</h2>
			<img src="./image/trusted.jpg" alt="trusted shop" style="margin:15px 0 0"/>
			<div id="ikonki">
				<img src="./image/paym-brw/dhl.png" alt="">
				<img src="./image/paym-brw/last.png" alt="">
				<img src="./image/paym-brw/master.png" alt="">
				<img src="./image/paym-brw/visa.png" alt="">
				<img src="./image/paym-brw/paypal.png" alt="">
				<img src="./image/paym-brw/sofort.png" alt="">
			</div>
		</div>
	</div>
</div>


<?php echo $footer; ?>