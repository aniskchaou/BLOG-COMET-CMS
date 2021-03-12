<?php
//
class ESSBAddonsHelper {

	public $base_addons_data;
	
	private static $instance = null;

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	} // end get_instance;

	function __construct() {
		$remote_result = $this->default_addons();
		
		$remote_result = htmlspecialchars_decode ( $remote_result );
		$remote_result = stripslashes ( $remote_result );
		$info = json_decode($remote_result, true);
		$this->base_addons_data = $info;
	}

	public function get_addons() {
		$addons = $this->base_addons_data;
		
		if (!is_array($addons)) {
			$addons = array();
		}
		
		return $addons;
	}
	
	public function default_addons() {
		return '{"essb-fomo":{"slug":"essb-social-proof-notifications","name":"Social Proof Notifications Pro","description":"Instantly increase conversions on your Website with Social Proof. Add attention-grabbing social notifications to grow shares, followers, subscribers.","check":{"param":"essb_social_proof_run","type":"function"},"icon":"icon-fomo","price":"$25","page":"https://codecanyon.net/item/social-proof-notifications-addon-for-easy-social-share-buttons/24197749","requires":"6.0","version7":"true"},"essb-templates-rainbow":{"slug":"essb-templates-rainbow","name":"Rainbow Templates Pack","description":"60 awesome looking gradient templates for Easy Social Share Buttons for WordPress","check":{"param":"essb_rainbow_initialze","type":"function"},"icon":"icon-template","price":"$10","page":"https://codecanyon.net/item/rainbow-templates-pack-for-easy-social-share-buttons/22753541","requires":"5.0","version7":"true"},"essb-video-share-events":{"slug":"essb-video-share-events","name":"Video Sharing","description":"A must have tool for each video marketing campaign. Add beautiful call to actions on specific events to increase your social shares, social following, mailing list, your marketing message at the right time or just share buttons.","price":"$29","icon":"icon-video","page":"https://codecanyon.net/item/video-sharing-addon-for-easy-social-share-buttons/8434467","demo_url":"http://preview.codecanyon.net/item/video-sharing-addon-for-easy-social-share-buttons/full_screen_preview/8434467","check":{"param":"ESSB3_VSE_VERSION","type":"param"},"requires":"4.0","version7":"true"},"essb-self-short-url":{"slug":"essb-self-short-url","name":"Self-Hosted Short URLs","icon":"icon-url","description":"Generate self hosted short URLs directly from your WordPress without external services like http://domain.com/axWsa or custom based http://domain.com/essb.","price":"$19","page":"http://codecanyon.net/item/self-hosted-short-urls-addon-for-easy-social-share-buttons/15066447","check":{"param":"ESSB3_SSU_VERSION","type":"param"},"requires":"3.1.2","version7":"true"},"hello-followers":{"slug":"hello-followers","name":"Hello Followers - Social Counter Plugin","icon":"icon-like","description":"Beatiful and unique extension of your current social followers with cover boxes, layout builder, advanced customizer, profile analytics. Try the live demo to test.","price":"$25","page":"http://codecanyon.net/item/hello-followers-social-counter-plugin-for-wordpress/15801729","check":{"param":"HF_VERSION","type":"param"},"requires":"1.0","version7":"true"},"essb-network-parler":{"slug":"essb-network-parler","name":"Share to Parler","description":"Add support for Parler sharing.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-network-parler","requires":"7.0","check":{"param":"ESSB_PARLER_URL","type":"param"},"icon":"icon-parler","actual_version":"1.0","version7":"true"},"essb-tiktok-feed":{"slug":"essb-tiktok-feed","name":"TikTok Feed","description":"Display TikTok feed for your latest videos on the website","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-tiktok-feed","requires":"7.5","check":{"param":"ESSB_TTF_VERSION","type":"param"},"icon":"icon-tiktok","actual_version":"1.0","version7":"true"},"essb-network-msteams":{"slug":"essb-network-msteams","name":"Share to Microsoft Teams","description":"Add support for Microsoft Teams sharing.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-network-msteams","requires":"7.0","check":{"param":"ESSB_MSTEAMS_URL","type":"param"},"icon":"icon-msteams","actual_version":"1.0","version7":"true"},"essb-social-contact-lite":{"slug":"essb-social-contact-lite","name":"Social Contact Lite","description":"Display contact us via various social messengers or apps. Use it with an automated display, shortcode or Elementor widget.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-social-contact-lite","requires":"7.2","check":{"param":"ESSB_SCL_VERSION","type":"param"},"icon":"icon-contact","actual_version":"1.0","version7":"true"},"essb-network-snapchat":{"slug":"essb-network-snapchat","name":"Share to Snapchat","description":"Add support for Snapchat Creative Kit for Web. With Creative Kit for Web, publishers and brands can add a Share to Snapchat button to their website so Snapchatters can share content from a mobile or desktop website into Snapchat.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-network-snapchat","requires":"7.2","check":{"param":"ESSB_SNAPCHAT_ROOT","type":"param"},"icon":"icon-snapchat","actual_version":"1.0","version7":"true"},"essb-network-wykop":{"slug":"essb-network-wykop","name":"Share to Wykop.pl","description":"Add support for Wykop.pl sharing.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-network-wykop","requires":"7.0","check":{"param":"ESSB_WYKOP_URL","type":"param"},"icon":"icon-wykop","actual_version":"1.0","version7":"true"},"essb-post-views":{"slug":"essb-post-views","name":"Post Views Counter","description":"Track and display post views/reads with your share buttons. Cache plugin compatible update and view mode. Show views also with shortcode or function. Most viewed posts shortcode and widget also present.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-post-views","requires":"3.0","check":{"param":"ESSB3_PV_VERSION","type":"param"},"icon":"icon-addon","actual_version":"3.0","version7":"true"},"essb-facebook-comments":{"slug":"essb-facebook-comments","name":"Facebook Comments","description":"Automatically include Facebook comments to your blog with moderation option below posts, pages, products","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-facebook-comments","requires":"3.0","check":{"param":"ESSB3_FC_VERSION","type":"param"},"icon":"icon-addon","version7":"true","actual_version":"2.0"},"essb-bimber-extension":{"slug":"essb-bimber-extension","name":"Bimber Theme Share Buttons Replace","description":"Include replacement of default theme share buttons with Easy Social Share Buttons (theme specific functions)","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-bimber-extension","requires":"4.1.8","check":{"param":"ESSB_BIMBER_REPLACE","type":"param"},"icon":"icon-display","version7":"true","actual_version":"2.0"},"essb-display-woocommercethankyou":{"slug":"essb-display-woocommercethankyou","name":"WooCommerce Thank You Page Share Products","description":"Add list of purchased products with share buttons on your WooCommerce thank you after purchase page","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-display-woocommercethankyou","check":{"param":"ESSB_DM_WTB_PLUGIN_ROOT","type":"param"},"icon":"icon-shop","requires":"4.1.8","version7":"true"},"essb-extended-buttons-pack":{"slug":"essb-extended-buttons-pack","name":"Extended Social Networks Pack","description":"Networks: Hatena, Douban, Tencent QQ, Naver, Renren","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-extended-buttons-pack","requires":"4.1.8","check":{"param":"ESSB_EP_ROOT","type":"param"},"icon":"icon-share","version7":"true","actual_version":"2.0"},"essb-functional-buttons-pack":{"slug":"essb-functional-buttons-pack","name":"Functional Share Buttons Pack","description":"Include usage of functional buttons set: Previous Post, Next Post, Copy Link, Bookmark, QR Code Generator","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-funcitonal-buttons-pack","requires":"4.1.8","check":{"param":"ESSB_FP_ROOT","type":"param"},"icon":"icon-share","version7":"true"},"essb-beaverbuilder-theme-integration":{"slug":"essb-beaverbuilder-theme-integration","name":"Beaver Builder Theme Integration","description":"Custom display positions for Beaver Builder Theme: Before/After content","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-beaverbuilder-theme-integration","requires":"7.0","check":{"param":"ESSB_BBT_CUSTOM_BOILERPLATE","type":"param"},"icon":"icon-display","version7":"true","actual_version":"2.0"},"essb-template-christmas":{"slug":"essb-template-christmas","name":"Templates: Christmas pack","description":"Prepare your site for upcoming Christmas and New Year with two special Christmas templates","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-template-christmas","requires":"4.0.2","check":{"param":"ESSB_TEMPLATEPACK_CHRISTMAS","type":"param"},"icon":"icon-template","version7":"true"},"essb-display-woocommercebar":{"slug":"essb-display-woocommercebar","name":"WooCommerce Bar Display Method","description":"Special designed share bar for WooCommerce stores with shaer buttons, product title/price and buy now button","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-display-woocommercebar","requires":"4.0.2","check":{"param":"ESSB_DM_VP_PLUGIN_URL","type":"param"},"icon":"icon-shop","version7":"true"},"essb-display-viralpoint":{"slug":"essb-display-viralpoint","name":"Display Method Viral Point","description":"Super cool share point design with automatic trigger on hover, eye catching design and animations","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-display-viralpoint","requires":"4.0.2","check":{"param":"ESSB_DM_VP_PLUGIN_URL","type":"param"},"icon":"icon-display","version7":"true"},"essb-display-superpostfloat":{"slug":"essb-display-superpostfloat","name":"Display Method Super Post Float","description":"Extended version of post vertical float with call to action message and display of total/comments count","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-display-superpostfloat","requires":"4.0.2","check":{"param":"ESSB_DM_SPF_PLUGIN_URL","type":"param"},"icon":"icon-display","version7":"true"},"essb-display-superpostbar":{"slug":"essb-display-superpostbar","name":"Display Method Super Post Bar","description":"Extend your bottom display method with super post bar. Super post bar allows display of previous/next post too.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-display-superpostbar","requires":"4.0.2","check":{"param":"ESSB_DM_SPB_PLUGIN_URL","type":"param"},"icon":"icon-display","version7":"true"},"essb-display-mobile-sharebarcta":{"slug":"essb-display-mobile-sharebarcta","name":"Display Method Mobile Share Bar with Call to Action Button","description":"Include mobile share bar with custom call to action button next to share button.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-display-mobile-sharebarcta","requires":"4.0.2","check":{"param":"ESSB_DM_MSBCTA_PLUGIN_URL","type":"param"},"icon":"icon-display","version7":"true"},"essb-subscribe-connector-jetpack":{"slug":"essb-subscribe-connector-jetpack","name":"JetPack Subscription Integrator","description":"Enable integration with the JetPack subscription module in the subscribe forms.","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-subscribe-connector-jetpack","requires":"4.0.2","check":{"param":"ESSB_SUBSCRIBE_CONNECTOR_JETPACK","type":"param"},"icon":"icon-addon","version7":"true"},"essb-multistep-sharerecovery":{"slug":"essb-multistep-sharerecovery","name":"Multi-step Share Counter Recovery","description":"Include additional recovery rules that can be used if you have made additional changes in the past - up to 3 additional recovery rules in help of the primary","price":"FREE","page":"http://get.socialsharingplugin.com/?download=essb-multistep-sharerecovery","check":{"param":"ESSB_MSSR_VERSION","type":"param"},"icon":"icon-addon","requires":"5.1.3"}}';
	}
}

?>