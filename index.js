var _ = require('lodash');
window.moment = require('moment')

// angular
require('angular')
require('angular-animate')
require('oclazyload')
require('angular-google-maps')
require('angular-resource')
require('angular-route')
require('angular-simple-logger')
require('angular-ui-bootstrap')
require('angular-br-filters')
require('angular-credit-cards')
require('angular-ui-mask')
require('angular-input-masks')
require('moment-timezone')
require('angular-br-filters')
require('angularjs-slider')
require('angular-tooltips')
require('ng-file-upload')
require('ngprogress')
require('angular-owl-carousel')

// local Scripts
require('./lib/angular-locale/angular-locale_pt-br.js')
require('./assets/js/ng-img-crop.js')
require('./lib/angular-input-masks/input-mask-2.js')

// jQuery
window.jQuery = window.$ = $jq = require("jquery");
require('./assets/js/scripts.js') // main jQuery
require('jquery.mmenu')
require('bootstrap')
require('owl.carousel')
require('bootstrap-slider')
require('./assets/js/menu/app.js')

require('./main.css')
