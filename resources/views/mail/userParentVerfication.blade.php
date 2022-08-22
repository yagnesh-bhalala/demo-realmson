@include('mail.mailHeader')

<body style="max-width: 600px;width:100%;margin:auto;padding:1rem 0rem;">
	<div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 1.5rem 0rem;text-align: center;background: #D20000;margin-bottom:1rem;">
			<h4 style="font-size: 20px;color: #fff;margin:unset;margin: 0rem;font-family:Poppins;font-weight:500;">
				<span style="font-family:Arial,Helvetica,sans-serif;">Welcome to The FIVE DOLLAR BILL HELPER<sup>TM</sup>  &ndash; Parental Authorization.</span>
			</h4>
		</div>
	</div>

	<div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 0rem 1.5rem;">
			<p style="color: #000;font-size: 20px;font-weight: bolder;line-height: 1.75;margin: unset;margin-bottom: .75rem;">
				<span style="font-family:Arial,Helvetica,sans-serif;">Hey <?php echo $user->parentName; ?>,</span>
			</p>
		</div>
	</div>

	<div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 0rem 1.5rem;">
			<p style="color: #000;font-size: 16px;line-height: 1.75;margin: unset;margin-bottom: .75rem;">
				<span style="font-family:Arial,Helvetica,sans-serif;">Your child <?php echo $user->firstName; ?>&nbsp;<?php echo $user->lastName; ?>, has requested an account. Please review our <strong>Terms & Conditions </strong>before authorizing your child to have an account with </span>
				<strong>The FIVE DOLLAR BILL HELPER<sup>TM</sup></strong>
			</p>
		</div>
	</div>

	<!-- <p style="color: #000;font-size: 16px;line-height: 1.75;margin: unset;margin-bottom: .75rem;"><span style="font-family:Arial,Helvetica,sans-serif;">Start by completing&nbsp;your registration process:</span></p>
	</div>
	</div> -->

	<div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 0rem 1.5rem;text-align: center;">
			<!--<p style="color: #000;font-size: 15px;line-height: 1.75;margin: unset;margin-bottom: .75rem;">To get started, you should first complete your registration process by</p>-->
			<span style="font-family:Arial,Helvetica,sans-serif;">
				<a href="<?php echo env('APP_URL').('verify-parent-account/'.$verifyId); ?>" style="padding: 1rem 1.75rem;background: #D20000;color: #fff;text-decoration: none;font-size: 18px;border-radius: 10px;display: inline-block;margin-bottom: .75rem;">Authorize your child access to The 5 DOLLAR BILL HELPER<sup>TM</sup>.</strong>
				</a>
			</span>
		</div>
	</div>

	<div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 0rem 1.5rem;">
			<!-- <p style="color: #000;font-size: 16px;line-height: 1.75;margin: unset;margin-bottom: .75rem;">
				<span style="font-family:Arial,Helvetica,sans-serif;">As a <span style="color:#000;font-weight:bold">Child,</span> you will have access to all internship opportunities.</span>
			</p> -->
			<!-- <p style="color: #000;font-size: 16px;line-height: 1.75;margin: unset;margin-bottom: .75rem;">
				<span style="font-family:Arial,Helvetica,sans-serif;">Complete your Permission and get ready to start sending applications.</span>
			</p> -->
		</div>
	</div>

	<!-- <div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 0rem 1.5rem;">
			<p style="color: #000;font-size: 16px;margin: unset;margin-bottom:0;">
				<span style="font-family:Arial,Helvetica,sans-serif;">Happy Internships! :)</span>
			</p>
		</div>
	</div> -->

	<div style="max-width: 600px;display: grid;margin:auto;">
		<div style="padding: 0rem 1.5rem;">
			<p style="color: #000;font-size: 16px;margin: unset;margin-bottom: 1.5rem;margin-top: .5rem;font-weight:bold">
				<span style="font-family:Arial,Helvetica,sans-serif;">Team <br>FIVE DOLLAR BILL HELPER LLC</span>
			</p>
		</div>
	</div>
</body>
@include('mail.mailFooter')
