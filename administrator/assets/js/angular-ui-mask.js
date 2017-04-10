/*!
 * angular-ui-mask
 * https://github.com/angular-ui/ui-mask
 * Version: 1.8.1 - 2016-02-23T07:32:45.020Z
 * License: MIT
 */
!function(){"use strict";angular.module("ui.mask",[]).value("uiMaskConfig",{maskDefinitions:{9:/\d/,A:/[a-zA-Z]/,"*":/[a-zA-Z0-9]/},clearOnBlur:!0,clearOnBlurPlaceholder:!1,eventsToHandle:["input","keyup","click","focus"]}).provider("uiMask.Config",function(){var e={};this.clearOnBlur=function(n){return e.clearOnBlur=n},this.clearOnBlurPlaceholder=function(n){return e.clearOnBlurPlaceholder=n},this.eventsToHandle=function(n){return e.eventsToHandle=n},this.$get=["uiMaskConfig",function(n){var t=n;for(var r in e)t[r]=e[r];return t}]}).directive("uiMask",["uiMask.Config",function(e){function n(e){return e===document.activeElement&&(!document.hasFocus||document.hasFocus())&&!!(e.type||e.href||~e.tabIndex)}return{priority:100,require:"ngModel",restrict:"A",compile:function(){var t=e;return function(e,r,i,a){function u(e){return angular.isDefined(e)?($(e),q?(h(),d(),!0):f()):f()}function l(e){e&&(B=e,!q||0===r.val().length&&angular.isDefined(i.placeholder)||r.val(m(p(r.val()))))}function o(){return u(i.uiMask)}function c(e){return q?(H=p(e||""),_=g(H),a.$setValidity("mask",_),_&&H.length?m(H):void 0):e}function s(e){return q?(H=p(e||""),_=g(H),a.$viewValue=H.length?m(H):"",a.$setValidity("mask",_),_?J?a.$viewValue:H:void 0):e}function f(){return q=!1,v(),angular.isDefined(K)?r.attr("placeholder",K):r.removeAttr("placeholder"),angular.isDefined(W)?r.attr("maxlength",W):r.removeAttr("maxlength"),r.val(a.$modelValue),a.$viewValue=a.$modelValue,!1}function h(){H=N=p(a.$modelValue||""),R=F=m(H),_=g(H),i.maxlength&&r.attr("maxlength",2*C[C.length-1]),K||r.attr("placeholder",B);for(var e=a.$modelValue,n=a.$formatters.length;n--;)e=a.$formatters[n](e);a.$viewValue=e||"",a.$render()}function d(){I||(r.bind("blur",y),r.bind("mousedown mouseup",E),r.bind("keydown",O),r.bind(Q.eventsToHandle.join(" "),x),I=!0)}function v(){I&&(r.unbind("blur",y),r.unbind("mousedown",E),r.unbind("mouseup",E),r.unbind("keydown",O),r.unbind("input",x),r.unbind("keyup",x),r.unbind("click",x),r.unbind("focus",x),I=!1)}function g(e){return e.length?e.length>=j:!0}function p(e){var n,t,i="",a=r[0],u=S.slice(),l=z,o=l+A(a),c="";return e=e.toString(),n=0,t=e.length-B.length,angular.forEach(T,function(r){var i=r.position;i>=l&&o>i||(i>=l&&(i+=t),e.substring(i,i+r.value.length)===r.value&&(c+=e.slice(n,i),n=i+r.value.length))}),e=c+e.slice(n),angular.forEach(e.split(""),function(e){u.length&&u[0].test(e)&&(i+=e,u.shift())}),i}function m(e){var n="",t=C.slice();return angular.forEach(B.split(""),function(r,i){e.length&&i===t[0]?(n+=e.charAt(0)||"_",e=e.substr(1),t.shift()):n+=r}),n}function b(e){var n,t=angular.isDefined(i.uiMaskPlaceholder)?i.uiMaskPlaceholder:i.placeholder;return angular.isDefined(t)&&t[e]?t[e]:(n=angular.isDefined(i.uiMaskPlaceholderChar)&&i.uiMaskPlaceholderChar?i.uiMaskPlaceholderChar:"_","space"===n.toLowerCase()?" ":n[0])}function k(){var e,n,t=B.split("");C&&!isNaN(C[0])&&angular.forEach(C,function(e){t[e]="_"}),e=t.join(""),n=e.replace(/[_]+/g,"_").split("_"),n=n.filter(function(e){return""!==e});var r=0;return n.map(function(n){var t=e.indexOf(n,r);return r=t+1,{value:n,position:t}})}function $(e){var n=0;if(C=[],S=[],B="",angular.isString(e)){j=0;var t=!1,r=0,i=e.split("");angular.forEach(i,function(e,i){Q.maskDefinitions[e]?(C.push(n),B+=b(i-r),S.push(Q.maskDefinitions[e]),n++,t||j++,t=!1):"?"===e?(t=!0,r++):(B+=e,n++)})}C.push(C.slice().pop()+1),T=k(),q=C.length>1?!0:!1}function y(){(Q.clearOnBlur||Q.clearOnBlurPlaceholder&&0===H.length&&i.placeholder)&&(z=0,L=0,_&&0!==H.length||(R="",r.val(""),e.$apply(function(){a.$viewValue=""}))),H!==U&&w(r[0]),U=H}function w(e){var n;angular.isFunction(window.Event)&&!e.fireEvent?(n=new Event("change",{view:window,bubbles:!0,cancelable:!1}),e.dispatchEvent(n)):"createEvent"in document?(n=document.createEvent("HTMLEvents"),n.initEvent("change",!1,!0),e.dispatchEvent(n)):e.fireEvent&&e.fireEvent("onchange")}function E(e){"mousedown"===e.type?r.bind("mouseout",M):r.unbind("mouseout",M)}function M(){L=A(this),r.unbind("mouseout",M)}function O(e){var n=8===e.which,t=P(this)-1||0;if(n){for(;t>=0;){if(V(t)){D(this,t+1);break}t--}Z=-1===t}}function x(n){n=n||{};var t=n.which,i=n.type;if(16!==t&&91!==t){var u,l=r.val(),o=F,c=!1,s=p(l),f=N,h=P(this)||0,d=z||0,v=h-d,g=C[0],b=C[s.length]||C.slice().shift(),k=L||0,$=A(this)>0,y=k>0,w=l.length>o.length||k&&l.length>o.length-k,E=l.length<o.length||k&&l.length===o.length-k,M=t>=37&&40>=t&&n.shiftKey,O=37===t,x=8===t||"keyup"!==i&&E&&-1===v,S=46===t||"keyup"!==i&&E&&0===v&&!y,T=(O||x||"click"===i)&&h>g;if(L=A(this),!M&&(!$||"click"!==i&&"keyup"!==i&&"focus"!==i)){if(x&&Z)return r.val(B),e.$apply(function(){a.$setViewValue("")}),void D(this,d);if("input"===i&&E&&!y&&s===f){for(;x&&h>g&&!V(h);)h--;for(;S&&b>h&&-1===C.indexOf(h);)h++;var j=C.indexOf(h);s=s.substring(0,j)+s.substring(j+1),s!==f&&(c=!0)}for(u=m(s),F=u,N=s,!c&&l.length>u.length&&(c=!0),r.val(u),c&&e.$apply(function(){a.$setViewValue(u)}),w&&g>=h&&(h=g+1),T&&h--,h=h>b?b:g>h?g:h;!V(h)&&h>g&&b>h;)h+=T?-1:1;(T&&b>h||w&&!V(d))&&h++,z=h,D(this,h)}}}function V(e){return C.indexOf(e)>-1}function P(e){if(!e)return 0;if(void 0!==e.selectionStart)return e.selectionStart;if(document.selection&&n(r[0])){e.focus();var t=document.selection.createRange();return t.moveStart("character",e.value?-e.value.length:0),t.text.length}return 0}function D(e,t){if(!e)return 0;if(0!==e.offsetWidth&&0!==e.offsetHeight)if(e.setSelectionRange)n(r[0])&&(e.focus(),e.setSelectionRange(t,t));else if(e.createTextRange){var i=e.createTextRange();i.collapse(!0),i.moveEnd("character",t),i.moveStart("character",t),i.select()}}function A(e){return e?void 0!==e.selectionStart?e.selectionEnd-e.selectionStart:document.selection?document.selection.createRange().text.length:0:0}var C,S,B,T,j,H,R,_,F,N,z,L,Z,q=!1,I=!1,K=i.placeholder,W=i.maxlength,G=a.$isEmpty;a.$isEmpty=function(e){return G(q?p(e||""):e)};var J=!1;i.$observe("modelViewValue",function(e){"true"===e&&(J=!0)});var Q={};i.uiOptions?(Q=e.$eval("["+i.uiOptions+"]"),Q=angular.isObject(Q[0])?function(e,n){for(var t in e)Object.prototype.hasOwnProperty.call(e,t)&&(void 0===n[t]?n[t]=angular.copy(e[t]):angular.isObject(n[t])&&!angular.isArray(n[t])&&(n[t]=angular.extend({},e[t],n[t])));return n}(t,Q[0]):t):Q=t,i.$observe("uiMask",u),angular.isDefined(i.uiMaskPlaceholder)?i.$observe("uiMaskPlaceholder",l):i.$observe("placeholder",l),angular.isDefined(i.uiMaskPlaceholderChar)&&i.$observe("uiMaskPlaceholderChar",o),a.$formatters.unshift(c),a.$parsers.unshift(s);var U=r.val();r.bind("mousedown mouseup",E),Array.prototype.indexOf||(Array.prototype.indexOf=function(e){if(null===this)throw new TypeError;var n=Object(this),t=n.length>>>0;if(0===t)return-1;var r=0;if(arguments.length>1&&(r=Number(arguments[1]),r!==r?r=0:0!==r&&r!==1/0&&r!==-(1/0)&&(r=(r>0||-1)*Math.floor(Math.abs(r)))),r>=t)return-1;for(var i=r>=0?r:Math.max(t-Math.abs(r),0);t>i;i++)if(i in n&&n[i]===e)return i;return-1})}}}}])}();