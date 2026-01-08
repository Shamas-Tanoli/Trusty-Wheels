<header class=" d-none d-md-block gauto-main-header-area">
   <div class="container p-0">
      <div class="row">
         <div class="col-md-3">
            <div class="site-logo">
               <a href="{{ url('/') }}">
                   @include('frontend.partials.logo')
               </a>
            </div>
         </div>
         <div class="col-lg-6 col-sm-9">
            <div class="header-promo">
               <div class="single-header-promo">
                  <div class="header-promo-icon">
                   <img src="{{ asset('assets/frontend/img/globe.png') }}" alt="globe">
                  </div>
                  <div class="header-promo-info">
                     <h3>UNIT 3 11 WOODGATE</h3>
                     <p>LEICESTER LE35GH, UK</p>
                  </div>
               </div>
               <div class="single-header-promo">
                  <div class="header-promo-icon">
                     <img src={{ asset('assets/frontend/img/clock.png') }} alt="clock">
                  </div>
                  <div class="header-promo-info">
                     <h3>Monday to Saturday</h3>
                     <p>9:00am - 5:00pm</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="header-action">
            <a class="btn btn-toggle-modal" href="#" data-toggle="modal" data-target="#modalAddYourItem">
  <i class="icon-addcar"></i><span class="tt-text">PART EXCHANGE</span>
</a>
             
            </div>
         </div>
      </div>
   </div>
</header>


<div class="modal fade" id="modalAddYourItem" tabindex="-1" role="dialog" aria-label="myModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">
			<div class="modal-body modal-layout-dafault">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icon-close"></span></button>
				<h5 class="modal-title">Add your Item</h5>
				<p class="tt-default-color02">
					Trading in your current car can help serve as a springboard into your new one. One of our team members will be in touch with a quote for your trade in right away.
				</p>
				<form class="tt-form-default02 tt-form-default02 tt-form-review" method="post" enctype="multipart/form-data" action="https://hispeedcarsales.co.uk/storetrade" data-gtm-form-interact-id="0">
					<input type="hidden" name="_token" value="8DnJZ4Pcb70Dc5iNESOZOmMl7tFAxgcmmW5QIFta" autocomplete="off">					<div class="form-group">
						<input type="text" name="name" class="form-control" placeholder="Your name*">
					</div>
					<div class="row">
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<input type="email" name="email" class="form-control" placeholder="E-mail*">
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<input type="text" name="phone" class="form-control" placeholder="Phone #">
							</div>
						</div>
					</div>
					<h6 class="tt-title">Vehicle Info</h6>
					<div class="form-group">
						<input type="text" name="year" class="form-control" placeholder="Year">
					</div>
					<div class="form-group">
						<input type="text" name="make" class="form-control" placeholder="Make">
					</div>
					<div class="form-group">
						<input type="text" name="model" class="form-control" placeholder="Model">
					</div>
					<div class="form-group">
						<input type="text" name="mileage" class="form-control" placeholder="Mileage">
					</div>
					<div class="tt-row-radio">
						<div class="tt-title">Exterior Condition*</div>
						<div>
							<label class="radio">
								<input id="radio11" type="radio" name="exterior_condition" value="1">
								<span class="outer"><span class="inner"></span></span>CLEAN
							</label>
						</div>
						<div>
							<label class="radio">
								<input id="radio12" type="radio" name="exterior_condition" value="2">
								<span class="outer"><span class="inner"></span></span>AVERAGE
							</label>
						</div>
						<div>
							<label class="radio">
								<input id="radio13" type="radio" name="exterior_condition" value="3">
								<span class="outer"><span class="inner"></span></span>ROUGH
							</label>
						</div>
					</div>
					<div class="tt-row-radio">
						<div class="tt-title">Interior Condition*</div>
						<div>
							<label class="radio">
								<input id="radio21" type="radio" name="interior_condition" value="1">
								<span class="outer"><span class="inner"></span></span>CLEAN
							</label>
						</div>
						<div>
							<label class="radio">
								<input id="radio22" type="radio" name="interior_condition" value="2">
								<span class="outer"><span class="inner"></span></span>AVERAGE
							</label>
						</div>
						<div>
							<label class="radio">
								<input id="radio23" type="radio" name="interior_condition" value="3">
								<span class="outer"><span class="inner"></span></span>ROUGH
							</label>
						</div>
					</div>
					<div class="tt-row-radio">
						<div class="tt-title">Been in Accident?*</div>
						<div>
							<label class="radio">
								<input id="radio31" type="radio" name="been_accident" value="0">
								<span class="outer"><span class="inner"></span></span>NO
							</label>
						</div>
						<div>
							<label class="radio">
								<input id="radio32" type="radio" name="been_accident" value="1">
								<span class="outer"><span class="inner"></span></span>YES
							</label>
						</div>
					</div>
					<h6 class="tt-title">Upload your car Photos</h6>
					<div class="input-group tt-input-file">
						<label class="input-group-btn">
							<span class="tt-btn-icon">
								choose file... <input name="pictures[]" type="file" style="display: none;" multiple="" accept="image/*">
							</span>
						</label>
						<input type="text" readonly="">
					</div>
					<ul class="list-form-column">
						<li>
							<div class="checkbox-group">
								<input type="checkbox" id="checkBox11" name="agree" checked="" data-gtm-form-interact-field-id="0">
								<label for="checkBox11">
								<span class="check"></span>
								<span class="box"></span>
								I AGREE TO RECEIVE EMAILS FROM CAR LEADER
								</label>
							</div>
						</li>
					</ul>
			
					

               <button type="submit" class="gauto-theme-btn"><i class="fa fa-paper-plane"></i> MAKE AN OFFER</button>
				</form>
			</div>
			
		</div>
	</div>
</div>