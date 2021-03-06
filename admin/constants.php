<?php

$GLOBALS['constGlobalCSS'] = '
	/* Header */
	header { border-bottom: solid 1px #ccc; box-shadow: 0 0 5px #888888; }
	.header-top { display: flex; }
	.header-top-left { width: 33%; text-align:left; }
	.header-top-center { width: 34%; text-align:center; }
	.header-top-right { width: 33%; text-align:right; }
	.logo { height: 55px; width: 120px; margin: 0 auto; }
	.header-inner { max-width: 1200px; padding: 20px 15px; margin: 0 auto; }
  	.hamburger_wrapper { padding: 5px; z-index: 10; margin-top: 10px; }
  	#hamburger { width: 43px; height: 35px; position: relative; cursor: pointer; outline: none; }
  	#hamburger span { display: block; position: absolute; height: 5px; width: 100%; background:#333; border-radius: 9px; opacity: 1;  left: 0; transform: rotate(0deg); transition: .5s ease-in-out; }
  	#hamburger span:nth-child(1) { top: 0px; transform-origin: left center; }
  	#hamburger span:nth-child(2) { top: 15px; transform-origin: left center; }
  	#hamburger span:nth-child(3) { top: 30px; transform-origin: left center; }
  	#hamburger.close span:nth-child(1) { transform: rotate(45deg); }
  	#hamburger.close span:nth-child(2) { width: 0%; opacity: 0; transition: .1s; }
  	#hamburger.close span:nth-child(3) { transform: rotate(-45deg); }
  	#nav-menu { position: absolute; transform: translateX(-100vw); opacity: 0; z-index: 10; transition: transform .5s ease, opacity ease .2s; }
  	#nav-menu.now-active { position: relative; transform: translateX(0); transition: transform .5s ease, opacity ease .2s; opacity: 1; }
  	.nav-list { padding: 10px; list-style-type: none; font-size: 1.4em; line-height: 1.2; }
  	.nav-list a { color: #333; }
  	#search { padding: 5px; margin: 10px 0 0 auto; }
  	#search .header-close-icon { display: none; }
  	#search.close .header-search-icon { display: none; }
  	#search.close .header-close-icon { display: block; }
  	#nav-search { position: absolute; transform: translateX(100vw); opacity: 0; z-index: 10; transition: transform .5s ease, opacity ease .2s;     text-align: right; width: 100%; }
  	#nav-search.now-active { position: relative; transform: translateX(0); transition: transform .5s ease, opacity ease .2s; opacity: 1; }
  	#nav-search form { padding: 20px 0 0; }
  	#nav-search input { padding: 3px; border: solid 1px #333; border-radius: 10px; font-size: 1.2em; width: 220px; }
  	#nav-search input[type="submit"] { background: #333; color: #fff; padding: 3px 10px; width: 80px; }
  	@media only screen and (min-width:768px) {
  		.logo { height: 60px; width: 149px; margin: 0 auto; }
  	}
	/* share icons */ 
	amp-social-share.custom-style { background-color: #008080; background-image: url(https://raw.githubusercontent.com/google/material-design-icons/master/social/1x_web/ic_share_white_48dp.png); background-repeat: no-repeat; background-position: center; background-size: contain; }
	amp-social-share.rounded { border-radius: 50%; background-size: 60%; color: #fff; background-color: #005AF0; }
	/* structure */
	* { box-sizing: border-box; }
	.content { max-width: 1000px; margin: 40px auto; padding: 0 15px; min-height: 50vh; padding-bottom: 2.5rem; }
	.sidebar-template .sidebar { border-top: solid 1px #ccc; }
  .sidebar-template .main { width: 100%; }
	@media only screen and (min-width:768px) {
		.sidebar-template .content { display: flex; max-width: 1200px; }
		.sidebar-template .sidebar { min-width: 280px; padding-left: 20px; border-top: none; border-left: solid 1px #ccc; margin-left: 20px; }
	}
	.displayPostInner { padding: 10px; margin: 5px 0; border: solid 1px #ccc; border-top: solid 3px #888; display: block; text-decoration: none; color: #333; }
	.displayPostInner:hover { box-shadow: 5px 5px 8px #888888; }
  .displayPostInner h3 { margin-bottom: 10px; font-size: 2em; }
  .displayPostInner p.date { margin-top: 0; font-style: italic; }
	@media only screen and (min-width:768px) {
		.displayPostsInner { display: flex; flex-flow: wrap; }
		.displayPost { width: 50%; }
		.displayPostsInner .displayPost:nth-child(3n - 1) .displayPostInner { margin-right: 5px; }
		.displayPostsInner .displayPost:nth-child(3n - 0) .displayPostInner { margin-left: 5px; }
		.displayPostFullWidth { width: 100%; margin: 5px 0; }
		.displayPostFullWidth .displayPostInner { display: flex; width: 100%; }
		.displayPostFullWidth .displayPostImage { min-width: 40%; padding-right: 20px; }
	}
  .databaseCTA { text-align: center; padding: 10px 20px; }
  .databaseCTA h3 { margin: 10px 0 0; }
  .databaseCTA p { margin: 10px 0; }
  @media only screen and (min-width:515px) {
    .databaseCTAs { display: flex; flex-flow: wrap; align-items: center; justify-content: center; }
  }
	footer { border-top: solid 1px #ccc; padding: 15px; }
	.footer-inner { max-width: 1200px; margin: 0 auto; }
  .button { background: #333; color: #fff; padding: 5px 15px; line-height: 1.4; border-radius: 20px; display: inline-block; text-decoration: none; }
  .button:hover { background: #000; box-shadow: 0 0 5px #888888; }
  .button.invert { background: #fff; color: #333; }
  .button.invert:hover { color: #000; box-shadow: 0 0 5px #222222; }
	.hide { display: none; }
	.show { display: block; }
	.sreaders-only:not(:focus):not(:active) { clip: rect(0 0 0 0); clip-path: inset(50%); height: 1px; overflow: hidden; position: absolute; white-space: nowrap;  width: 1px; }
	.text-center { text-align: center; }

';



// Amp Boilerplate https://amp.dev/documentation/examples/introduction/hello_world/?format=websites
$GLOBALS['ampBoilerplate'] = '
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
';


$GLOBALS['logoSVG'] = '
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="100%" height="100%" viewBox="0 0 497 199" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;fill:#333333;">
	<title>AMPto11</title>
    <g id="Artboard1" transform="matrix(0.936724,0,0,0.818833,-68.4121,-87.3047)">
        <rect x="73.033" y="106.621" width="529.579" height="242.04" style="fill:none;"/>
        <g transform="matrix(10.1865,0,0,11.6531,-1062.19,-2707.63)">
            <g transform="matrix(12.0521,0,0,12.0521,112.878,260.823)">
                <path d="M0.489,-0.28L0.401,-0.531L0.313,-0.28L0.489,-0.28ZM0.541,-0.131L0.261,-0.131L0.216,0L0.007,0L0.294,-0.754L0.508,-0.754L0.795,0L0.586,0L0.541,-0.131Z" style="fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(12.0521,0,0,12.0521,122.543,260.823)">
                <path d="M0.045,0L0.173,-0.754L0.367,-0.754L0.518,-0.352L0.668,-0.754L0.862,-0.754L0.99,0L0.795,0L0.73,-0.434L0.552,0L0.474,0L0.305,-0.434L0.24,0L0.045,0Z" style="fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(12.0521,0,0,12.0521,134.179,260.823)">
                <path d="M0.275,-0.408L0.34,-0.408C0.412,-0.408 0.448,-0.439 0.448,-0.502C0.448,-0.565 0.412,-0.596 0.34,-0.596L0.275,-0.596L0.275,-0.408ZM0.275,0L0.079,0L0.079,-0.754L0.391,-0.754C0.476,-0.754 0.541,-0.732 0.586,-0.688C0.631,-0.644 0.653,-0.582 0.653,-0.502C0.653,-0.422 0.631,-0.36 0.586,-0.316C0.541,-0.272 0.476,-0.25 0.391,-0.25L0.275,-0.25L0.275,0Z" style="fill-rule:nonzero;"/>
            </g>
        </g>
        <g transform="matrix(6.6475,0,0,7.52225,-2408.14,-1964.97)">
            <g transform="matrix(12,0,0,12,421.703,305.416)">
                <path d="M0.365,-0.588L0.365,0L0.169,0L0.169,-0.588L0.008,-0.588L0.008,-0.754L0.526,-0.754L0.526,-0.588L0.365,-0.588Z" style="fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(10.4484,0,0,12,427.775,305.416)">
                <path d="M0.25,-0.377C0.25,-0.347 0.256,-0.319 0.267,-0.294C0.278,-0.269 0.294,-0.247 0.313,-0.228C0.332,-0.209 0.355,-0.195 0.381,-0.185C0.406,-0.174 0.433,-0.169 0.462,-0.169C0.491,-0.169 0.518,-0.174 0.544,-0.185C0.569,-0.195 0.592,-0.209 0.612,-0.228C0.631,-0.247 0.647,-0.269 0.658,-0.294C0.669,-0.319 0.675,-0.347 0.675,-0.377C0.675,-0.407 0.669,-0.435 0.658,-0.46C0.647,-0.485 0.631,-0.507 0.612,-0.526C0.592,-0.545 0.569,-0.559 0.544,-0.57C0.518,-0.58 0.491,-0.585 0.462,-0.585C0.433,-0.585 0.406,-0.58 0.381,-0.57C0.355,-0.559 0.332,-0.545 0.313,-0.526C0.294,-0.507 0.278,-0.485 0.267,-0.46C0.256,-0.435 0.25,-0.407 0.25,-0.377ZM0.045,-0.377C0.045,-0.433 0.055,-0.485 0.076,-0.534C0.097,-0.582 0.125,-0.624 0.162,-0.66C0.199,-0.696 0.243,-0.724 0.294,-0.745C0.345,-0.765 0.401,-0.775 0.462,-0.775C0.523,-0.775 0.579,-0.765 0.63,-0.745C0.681,-0.724 0.725,-0.696 0.763,-0.66C0.8,-0.624 0.828,-0.582 0.849,-0.534C0.87,-0.485 0.88,-0.433 0.88,-0.377C0.88,-0.321 0.87,-0.269 0.849,-0.221C0.828,-0.172 0.8,-0.13 0.763,-0.094C0.725,-0.058 0.681,-0.03 0.63,-0.01C0.579,0.011 0.523,0.021 0.462,0.021C0.401,0.021 0.345,0.011 0.294,-0.01C0.243,-0.03 0.199,-0.058 0.162,-0.094C0.125,-0.13 0.097,-0.172 0.076,-0.221C0.055,-0.269 0.045,-0.321 0.045,-0.377Z" style="fill-rule:nonzero;"/>
            </g>
        </g>
        <g transform="matrix(10.5048,0,0,12.0172,-5345.74,-3328.88)">
            <g transform="matrix(11.6869,0,0,11.6869,555.172,304.617)">
                <path d="M0.436,0L0.232,0L0.232,-0.577L0.151,-0.577L0.151,-0.702L0.436,-0.754L0.436,0Z" style="fill-rule:nonzero;"/>
            </g>
            <g transform="matrix(11.6869,0,0,11.6869,559.891,304.617)">
                <path d="M0.436,0L0.232,0L0.232,-0.577L0.151,-0.577L0.151,-0.71L0.436,-0.754L0.436,0Z" style="fill-rule:nonzero;"/>
            </g>
        </g>
        <g transform="matrix(0.840533,0,0,0.961548,92.9964,-13.3199)">
            <path d="M285.358,237.231C285.358,237.231 322.584,159.988 428.2,161.464C535.214,162.959 569.744,242.829 569.744,242.829L588.799,238.846C588.799,238.846 548.405,143.637 429.47,143.277C303.824,142.896 264.507,237.701 264.507,237.701L285.358,237.231Z"/>
        </g>
        <g transform="matrix(0.840533,0,0,0.961548,130.334,-17.9585)">
            <path d="M440.25,165.422L416.781,231.978L429.041,236.301L452.51,169.745L440.25,165.422Z"/>
        </g>
    </g>
</svg>
';


$GLOBALS['searchSVG'] = '
<!-- Generated by IcoMoon.io -->
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<title>search</title>
<path fill="#333333" d="M31.008 27.231l-7.58-6.447c-0.784-0.705-1.622-1.029-2.299-0.998 1.789-2.096 2.87-4.815 2.87-7.787 0-6.627-5.373-12-12-12s-12 5.373-12 12 5.373 12 12 12c2.972 0 5.691-1.081 7.787-2.87-0.031 0.677 0.293 1.515 0.998 2.299l6.447 7.58c1.104 1.226 2.907 1.33 4.007 0.23s0.997-2.903-0.23-4.007zM12 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"></path>
</svg>
';

$GLOBALS['closeSVG'] = '
<!-- Generated by IcoMoon.io -->
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
<title>close</title>
<path fill="#333333" d="M31.708 25.708c-0-0-0-0-0-0l-9.708-9.708 9.708-9.708c0-0 0-0 0-0 0.105-0.105 0.18-0.227 0.229-0.357 0.133-0.356 0.057-0.771-0.229-1.057l-4.586-4.586c-0.286-0.286-0.702-0.361-1.057-0.229-0.13 0.048-0.252 0.124-0.357 0.228 0 0-0 0-0 0l-9.708 9.708-9.708-9.708c-0-0-0-0-0-0-0.105-0.104-0.227-0.18-0.357-0.228-0.356-0.133-0.771-0.057-1.057 0.229l-4.586 4.586c-0.286 0.286-0.361 0.702-0.229 1.057 0.049 0.13 0.124 0.252 0.229 0.357 0 0 0 0 0 0l9.708 9.708-9.708 9.708c-0 0-0 0-0 0-0.104 0.105-0.18 0.227-0.229 0.357-0.133 0.355-0.057 0.771 0.229 1.057l4.586 4.586c0.286 0.286 0.702 0.361 1.057 0.229 0.13-0.049 0.252-0.124 0.357-0.229 0-0 0-0 0-0l9.708-9.708 9.708 9.708c0 0 0 0 0 0 0.105 0.105 0.227 0.18 0.357 0.229 0.356 0.133 0.771 0.057 1.057-0.229l4.586-4.586c0.286-0.286 0.362-0.702 0.229-1.057-0.049-0.13-0.124-0.252-0.229-0.357z"></path>
</svg>
';


?>