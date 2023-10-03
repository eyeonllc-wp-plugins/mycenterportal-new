<?php
$mycenterdeal = $this->mcd_settings['mycenterdeal'];

$deal_url = mcd_single_page_url('mycenterdeal');
$prev_url = '';
$next_url = '';

if( isset($mycenterdeal['prev']) ) {
	$prev_url = $deal_url.$mycenterdeal['prev']['slug'];
}
if( isset($mycenterdeal['next']) ) {
	$next_url = $deal_url.$mycenterdeal['next']['slug'];
}
?>

<?php if( is_array($mycenterdeal) ) : ?>

<div class="mycenterdeals-wrapper mycenterdeal">
	<?php if( isset( $mycenterdeal['error'] ) ) : ?>
		<div class="mcd-alert"><?= $mycenterdeal['error'] ?></div>
	<?php else: ?>
		<div id="mcd-deal" class="clearfix">
			<div class="mcd-prev-next-nav">
				<?php if( !empty($this->mcd_settings['deals_listing_page']) ) : ?>
					<a href="<?= get_permalink($this->mcd_settings['deals_listing_page']) ?>" class="item back">Back to Deals</a>
				<?php endif; ?>
				<a <?= (!empty($prev_url)?'href="'.$prev_url.'"':'') ?> class="item prev hide <?= (empty($prev_url)?'disabled':'') ?>"><i class="fas fa-chevron-left"></i><span>Prev</span></a>
				<a <?= (!empty($next_url)?'href="'.$next_url.'"':'') ?> class="item next hide <?= (empty($next_url)?'disabled':'') ?>"><span>Next</span><i class="fas fa-chevron-right"></i></a>
			</div>

			<div class="mcd-deal-cols">
				<div class="mcd-deal-image-col">
					<div class="mcd-deal-image">
						<img src="<?= $mycenterdeal['media']['url'] ?>" />
						<div class="mcd-retailer-logo">
							<img src="<?= $mycenterdeal['retailer']['media']['url'] ?>" />
						</div>
					</div>
					<div class="mcd-retailer-details">
						<div class="mcd-retailer-name"><?= $mycenterdeal['retailer']['name'] ?></div>
					</div>
				</div>

				<div class="mcd-deal-details">
					<div class="mcd-deal-title"><?= $mycenterdeal['title'] ?></div>
					<div class="mcd-deal-until"><span class="mcd-label">Valid Until:</span> <?= $mycenterdeal['end_date'] ?></div>
					<div class="mcd-deal-message"><?= $mycenterdeal['description'] ?></div>
          <?php if( isset($mycenterdeal['retailer']['phone']) ) : ?>
            <div class="mcd-retailer-phone"><span class="mcd-label">Phone:</span> <?= $mycenterdeal['retailer']['phone'] ?></div>
          <?php endif; ?>
					<div class="mcd-deal-center-location"><span class="mcd-label">Retailer Location:</span> <?= $mycenterdeal['retailer']['location'] ?></div>
					
					<?php if( $this->mcd_settings['deals_single_social_share'] ) : ?>
					<div class="mcd-deal-share clearfix">
						<span class="mcd-share-title mcd-label">Share</span>
						<ul class="mcd-social-icons">
							<li class="twitter"><a href="http://twitter.com/share?text=<?= urlencode($mycenterdeal['title']) ?>&url=<?= get_current_url() ?>" target="_blank">Twitter</a></li>
							<li class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?= get_current_url() ?>&quote=<?= urlencode($mycenterdeal['title']) ?>" target="_blank">Facebook</a></li>
							<!-- <li class="pinterest"><a href="http://pinterest.com/pin/create/button/?url=<?= get_current_url() ?>&media=<?= $mycenterdeal['deal_image'] ?>&description=<?= $mycenterdeal['title'] ?>" target="_blank">Pinterest</a></li> -->
							<li class="email"><a href="mailto:?subject=<?= $mycenterdeal['retailer_name'] ?> - <?= $mycenterdeal['title'] ?>&body=Hi,%0D%0A%0D%0ACheckout this Deal! - <?= urlencode(get_current_url()) ?>%0D%0A%0D%0A<?= $mycenterdeal['deal_title'] ?>%0D%0A%0D%0A<?= strip_tags($mycenterdeal['deal_message']) ?>%0D%0A%0D%0AValid Until: <?= $mycenterdeal['deal_end_date'] ?>%0D%0A%0D%0AStore: <?= $mycenterdeal['retailer_name'] ?>%0D%0ACenter Location: <?= $mycenterdeal['center_name'] ?>, <?= $mycenterdeal['center_location'] ?>%0D%0APhone: <?= $mycenterdeal['retailer_phone'] ?>%0D%0A%0D%0A">Email</a></li>
						</ul>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div id="mcd-deal-popup" class="mcd-popup">
			<div class="mcd-popup-wrapper">
				<div class="mcd-popup-overlay"></div>
				<div class="mcd-popup-content"></div>
			</div>
		</div>

		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#mcd-deal .mcd-deal-image img').click(function(event) {
				$('#mcd-deal-popup').fadeIn();
				$('#mcd-deal-popup .mcd-popup-content').html( event.target.outerHTML );
			});

			// close popup on clicking transparent overlay
			$('#mcd-deal-popup .mcd-popup-overlay').click(function(event) {
				$('#mcd-deal-popup').fadeOut(function() {
					$('#mcd-deal-popup .mcd-popup-content').html();
				});
			});
		});
		</script>

	<?php endif; ?>	
</div>

<?php endif; ?>

