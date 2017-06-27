<p>
<!-- AGID - SPID IDP BUTTON SMALL "ENTRA CON SPID" * begin * -->
<?php
$cls = new Spid_Wordpress_Public();

?>

<a href="#" class="italia-it-button italia-it-button-size-s button-spid" spid-idp-button="#spid-idp-button-small-get" aria-haspopup="true" aria-expanded="false">
    <span class="italia-it-button-icon"><img src="<?php $cls->get_img('spid-ico-circle-bb.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-ico-circle-bb.png'); ?>'; this.onerror=null;" alt="" /></span>
    <span class="italia-it-button-text">Entra con SPID</span>
</a>

<div id="spid-idp-button-small-get" class="spid-idp-button spid-idp-button-tip spid-idp-button-relative">
    <ul id="spid-idp-list-small-root-get" class="spid-idp-button-menu" aria-labelledby="spid-idp">
        <li class="spid-idp-button-link" data-idp="arubaid">
            <a href="#"><span class="spid-sr-only">Aruba ID</span><img src="<?php $cls->get_img('spid-idp-arubaid.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-idp-arubaid.png'); ?>'; this.onerror=null;" alt="Aruba ID" /></a>
        </li>
        <li class="spid-idp-button-link" data-idp="infocertid">
            <a href="#"><span class="spid-sr-only">Infocert ID</span><img src="<?php $cls->get_img('spid-idp-infocertid.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-idp-infocertid.png'); ?>'; this.onerror=null;" alt="Infocert ID" /></a>
        </li>
        <li class="spid-idp-button-link" data-idp="namirialid">
            <a href="#"><span class="spid-sr-only">Namirial ID</span><img src="<?php $cls->get_img('spid-idp-namirialid.svg');?>" onerror="this.src='<?php $cls->get_img('spid-idp-namirialid.png');?>'; this.onerror=null;" alt="Namirial ID" /></a>
        </li>
        <li class="spid-idp-button-link" data-idp="posteid">
            <a href="#"><span class="spid-sr-only">Poste ID</span><img src="<?php $cls->get_img('spid-idp-posteid.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-idp-posteid.png');?>'; this.onerror=null;" alt="Poste ID" /></a>
        </li>
        <li class="spid-idp-button-link" data-idp="sielteid">
            <a href="#"><span class="spid-sr-only">Sielte ID</span><img src="<?php $cls->get_img('spid-idp-sielteid.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-idp-sielteid.png');?>'; this.onerror=null;" alt="Sielte ID" /></a>
        </li>
        <li class="spid-idp-button-link" data-idp="spiditalia">
            <a href="#"><span class="spid-sr-only">SPIDItalia Register.it</span><img src="<?php $cls->get_img('spid-idp-spiditalia.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-idp-spiditalia.png');?>'; this.onerror=null;" alt="SpidItalia" /></a>
        </li>
        <li class="spid-idp-button-link" data-idp="timid">
            <a href="#"><span class="spid-sr-only">Tim ID</span><img src="<?php $cls->get_img('spid-idp-timid.svg'); ?>" onerror="this.src='<?php $cls->get_img('spid-idp-timid.png');?>'; this.onerror=null;" alt="Tim ID" /></a>
        </li>
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
<!-- AGID - SPID IDP BUTTON SMALL "ENTRA CON SPID" * end * -->
</p>