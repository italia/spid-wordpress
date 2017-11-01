<?php
defined( 'WPINC' ) or die;
// this runs inside that class, there and only there: no need to import it again.
//$cls = new Spid_Wordpress_Public();
/** @var $this Spid_Wordpress_Login */
?>
<p class="spid-button-container">
	<!-- AGID - SPID IDP BUTTON SMALL "ENTRA CON SPID" * begin * -->
	<!-- TODO: attribute "spid-idp-button" is not valid. Should be "data-spid-idp-button"? -->
	<a href="#" class="italia-it-button italia-it-button-size-s button-spid"
	   spid-idp-button="#spid-idp-button-small-get" aria-haspopup="true" aria-expanded="false">
		<span class="italia-it-button-icon"><img src="<?php echo $this->get_img( 'spid-ico-circle-bb.svg' ); ?>"
		                                         onerror="this.src='<?php echo $this->get_img( 'spid-ico-circle-bb.png' ); ?>'; this.onerror=null;"
		                                         alt="Login"/></span>
		<span class="italia-it-button-text">Entra con SPID</span>
	</a>

<div id="spid-idp-button-small-get" class="spid-idp-button spid-idp-button-tip spid-idp-button-relative">
	<ul id="spid-idp-list-small-root-get" class="spid-idp-button-menu" aria-labelledby="spid-idp">
		<?php
		$idps = $this->get_idp_html_all();
		foreach ( $idps as $idp ) {
			echo $idp;
		}
		?>
		<li class="spid-idp-support-link">
			<a href="https://www.spid.gov.it">Maggiori informazioni</a>
		</li>
		<li class="spid-idp-support-link">
			<a href="https://www.spid.gov.it/richiedi-spid">Non hai SPID?</a>
		</li>
		<li class="spid-idp-support-link">
			<a href="https://www.spid.gov.it/serve-aiuto">Serve aiuto?</a>
		</li>
	</ul>
</div>
<?php
$this->add_button_scripts();
?>
<!-- AGID - SPID IDP BUTTON SMALL "ENTRA CON SPID" * end * -->
</p>
